use std::io::{Write, BufRead, BufReader};
use serde::{Deserialize, Serialize};
use std::fs::{self, OpenOptions};
use std::path::PathBuf;
use tauri::path::BaseDirectory;
use tauri::Manager;
use sysinfo::{System};

#[derive(Serialize, Deserialize, Clone, Debug)]
pub struct DownloadMetadata {
    pub file_name: String,
    pub url: String,
    pub timestamp: String,
    pub path: String,
}

#[derive(Serialize, Deserialize, Clone, Debug)]
pub struct LogEntry {
    pub event_type: String,
    pub message: String,
    pub timestamp: String,
}

#[derive(Serialize, Deserialize, Clone, Debug)]
pub struct SystemStats {
    pub cpu_usage: f32,
    pub memory_used: u64,
    pub memory_total: u64,
}

fn get_app_data_dir() -> PathBuf {
    let mut path = dirs::data_dir().unwrap_or_else(|| PathBuf::from("."));
    path.push("tiktok_app_log");
    if !path.exists() {
        let _ = fs::create_dir_all(&path);
    }
    path
}

fn log_event(event_type: &str, message: &str) {
    let mut path = get_app_data_dir();
    path.push("logs.json");

    let entry = LogEntry {
        event_type: event_type.to_string(),
        message: message.to_string(),
        timestamp: chrono::Local::now().to_rfc3339(),
    };

    if let Ok(mut file) = OpenOptions::new().append(true).create(true).open(&path) {
        if let Ok(json) = serde_json::to_string(&entry) {
            let _ = writeln!(file, "{}", json);
        }
    }
}

#[tauri::command]
async fn is_dev_mode() -> bool {
    let args: Vec<String> = std::env::args().collect();
    args.contains(&"--dev-crty-mode".to_string())
}

#[tauri::command]
async fn get_logs() -> Result<Vec<LogEntry>, String> {
    let mut path = get_app_data_dir();
    path.push("logs.json");
    
    if !path.exists() {
        return Ok(vec![]);
    }

    let file = fs::File::open(&path).map_err(|e| e.to_string())?;
    let reader = BufReader::new(file);
    let mut logs = Vec::new();

    for line in reader.lines() {
        if let Ok(l) = line {
            if let Ok(entry) = serde_json::from_str(&l) {
                logs.push(entry);
            }
        }
    }
    
    Ok(logs.into_iter().rev().take(50).collect())
}

#[tauri::command]
async fn get_system_stats() -> Result<SystemStats, String> {
    let mut sys = System::new_all();
    sys.refresh_all();
    
    // Refresh twice for CPU accuracy if needed, but for a quick snapshot one refresh + some delay or interval is fine.
    // In a production app we might keep the System struct in state.
    std::thread::sleep(std::time::Duration::from_millis(100));
    sys.refresh_cpu_all();

    Ok(SystemStats {
        cpu_usage: sys.global_cpu_usage(),
        memory_used: sys.used_memory(),
        memory_total: sys.total_memory(),
    })
}

#[tauri::command]
async fn get_download_history() -> Result<Vec<DownloadMetadata>, String> {
    let mut path = get_app_data_dir();
    path.push("archive.json");
    
    if !path.exists() {
        return Ok(vec![]);
    }
    
    let data = fs::read_to_string(&path).map_err(|e| e.to_string())?;
    let history: Vec<DownloadMetadata> = serde_json::from_str(&data).map_err(|e| e.to_string())?;
    Ok(history)
}

#[tauri::command]
async fn open_file(app: tauri::AppHandle, path: String) -> Result<(), String> {
    use tauri_plugin_opener::OpenerExt;
    log_event("UI_ACTION", &format!("Opening path: {}", path));
    app.opener().open_path(path, None::<&str>).map_err(|e| e.to_string())?;
    Ok(())
}

#[tauri::command]
async fn download_video(app: tauri::AppHandle, url: String, filename: String) -> Result<String, String> {
    log_event("DOWNLOAD_START", &format!("Target: {}", url));
    
    let client = reqwest::Client::new();
    let response = client.get(&url).send().await.map_err(|e| {
        log_event("DOWNLOAD_ERROR", &format!("Network failure: {}", e));
        e.to_string()
    })?;
    
    if !response.status().is_success() {
        log_event("DOWNLOAD_ERROR", &format!("Bad status: {}", response.status()));
        return Err(format!("Failed to download: {}", response.status()));
    }

    let bytes = response.bytes().await.map_err(|e| e.to_string())?;
    
    let download_dir = app.path().resolve("", BaseDirectory::Download).map_err(|e| e.to_string())?;
    let mut file_path = download_dir.clone();
    file_path.push(&filename);
    
    let mut file = std::fs::File::create(&file_path).map_err(|e| e.to_string())?;
    file.write_all(&bytes).map_err(|e| e.to_string())?;

    let mut config_path = get_app_data_dir();
    config_path.push("archive.json");
    
    let mut history: Vec<DownloadMetadata> = if config_path.exists() {
        let data = fs::read_to_string(&config_path).map_err(|e| e.to_string())?;
        serde_json::from_str(&data).unwrap_or_else(|_| vec![])
    } else {
        vec![]
    };

    history.push(DownloadMetadata {
        file_name: filename.clone(),
        url: url.clone(),
        timestamp: chrono::Local::now().to_rfc3339(),
        path: file_path.to_string_lossy().to_string(),
    });

    let json_data = serde_json::to_string_pretty(&history).map_err(|e| e.to_string())?;
    fs::write(config_path, json_data).map_err(|e| e.to_string())?;
    
    log_event("DOWNLOAD_SUCCESS", &format!("Saved to: {:?}", file_path));
    Ok(format!("Successfully saved to {:?}", file_path))
}

#[tauri::command]
fn greet(name: &str) -> String {
    format!("Hello, {}! You've been greeted from Rust!", name)
}

#[cfg_attr(mobile, tauri::mobile_entry_point)]
pub fn run() {
    tauri::Builder::default()
        .plugin(tauri_plugin_opener::init())
        .setup(|app| {
            let window = app.get_webview_window("main").unwrap();
            
            let args: Vec<String> = std::env::args().collect();
            if args.contains(&"--dev-crty-mode".to_string()) {
                log_event("SYSTEM", "Developer Mode Activated via CLI");
                #[cfg(debug_assertions)]
                window.open_devtools();
            }

            let ua = "Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, Gecko) Mobile/20A5340a TikTok";
            
            let _ = window.eval(&format!(r#"
                const originalConsoleWarn = console.warn;
                console.warn = function() {{
                    if (arguments[0] && (arguments[0].includes('AppContext') || arguments[0].includes('environment'))) return;
                    originalConsoleWarn.apply(console, arguments);
                }};

                window.atob = (function(orig) {{
                    return function(str) {{
                        try {{
                            return orig(str);
                        }} catch (e) {{
                            console.error('Handled malformed atob:', str);
                            return "";
                        }}
                    }};
                }})(window.atob);

                Object.defineProperty(navigator, 'userAgent', {{
                    get: function () {{ return '{}'; }}
                }});
            "#, ua));

            let window_clone = window.clone();
            std::thread::spawn(move || {
                std::thread::sleep(std::time::Duration::from_secs(3));
                let _ = window_clone.eval("window.location.href = 'https://www.tiktok.com'");
            });
            
            Ok(())
        })
        .invoke_handler(tauri::generate_handler![greet, download_video, get_download_history, open_file, is_dev_mode, get_logs, get_system_stats])
        .run(tauri::generate_context!())
        .expect("error while running tauri application");
}
