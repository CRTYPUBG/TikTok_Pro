use std::io::{Write, BufRead, BufReader};
use serde::{Deserialize, Serialize};
use std::fs::{self, OpenOptions};
use std::path::PathBuf;
use tauri::path::BaseDirectory;
use tauri::Manager;
use sysinfo::{System};
use tauri::webview::WebviewWindowBuilder;

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
            let ua = "Mozilla/5.0 (Linux; Android 13; Pixel 7 Build/TQ3A.230805.001) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/120.0 Mobile Safari/537.36";
            
            // Core Stealth & Patch Script - Persistence achieved via initialization_script
            let stealth_script = format!(r#"
                (function() {{
                    // Masking atob to prevent certain detection script crashes
                    const origAtob = window.atob;
                    window.atob = function(str) {{
                        try {{
                            return origAtob(str);
                        }} catch (e) {{
                            return ""; 
                        }}
                    }};

                    // Mute noisy console warnings from TikTok's internal scripts
                    const origWarn = console.warn;
                    console.warn = function() {{
                        if (arguments[0] && (typeof arguments[0] === 'string') && 
                           (arguments[0].includes('AppContext') || arguments[0].includes('environment'))) return;
                        origWarn.apply(console, arguments);
                    }};

                    // Advanced Mobile Fingerprinting Mock
                    Object.defineProperty(navigator, 'userAgent', {{
                        get: function () {{ return '{}'; }},
                        configurable: true
                    }});

                    Object.defineProperty(navigator, 'platform', {{
                        get: function () {{ return 'Linux armv8l'; }},
                        configurable: true
                    }});

                    Object.defineProperty(navigator, 'maxTouchPoints', {{
                        get: function () {{ return 5; }},
                        configurable: true
                    }});

                    Object.defineProperty(navigator, 'webdriver', {{
                        get: function () {{ return false; }},
                        configurable: true
                    }});

                    Object.defineProperty(navigator, 'vendor', {{
                        get: function () {{ return 'Google Inc.'; }},
                        configurable: true
                    }});

                    Object.defineProperty(navigator, 'deviceMemory', {{
                        get: function () {{ return 8; }},
                        configurable: true
                    }});

                    Object.defineProperty(navigator, 'hardwareConcurrency', {{
                        get: function () {{ return 8; }},
                        configurable: true
                    }});

                    // GPU + WebGL Emulator
                    const getParameter = WebGLRenderingContext.prototype.getParameter;
                    WebGLRenderingContext.prototype.getParameter = function(parameter) {{
                        if (parameter === 37445) return "Qualcomm";
                        if (parameter === 37446) return "Adreno (TM) 730";
                        return getParameter.apply(this, arguments);
                    }};

                    // Hide Tauri specific globals
                    try {{
                        delete window.__TAURI__;
                        delete window.__TAURI_METADATA__;
                        delete window.__TAURI_POST_MESSAGE__;
                    }} catch (e) {{}}

                    // Force mobile viewport meta tag & touch emulation
                    if (!document.querySelector('meta[name="viewport"]')) {{
                        const meta = document.createElement("meta");
                        meta.name = "viewport";
                        meta.content = "width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no";
                        document.head.appendChild(meta);
                    }}
                    document.body.style.touchAction = "manipulation";

                    // Screen Size Spoofing for Portrait Aspect Ratio
                    const mockScreen = {{
                        width: 390,
                        height: 844,
                        availWidth: 390,
                        availHeight: 844,
                        colorDepth: 24,
                        pixelDepth: 24,
                    }};
                    
                    Object.entries(mockScreen).forEach(([key, val]) => {{
                        Object.defineProperty(window.screen, key, {{
                            get: () => val,
                            configurable: true
                        }});
                    }});

                    Object.defineProperty(window, 'devicePixelRatio', {{
                         get: () => 3,
                         configurable: true
                    }});

                    // Android Emulator Swipe Behavior
                    window.addEventListener("wheel", e => {{
                        window.scrollBy(0, e.deltaY);
                    }}, {{ passive: true }});

                    // Fake touch event support
                    if (!('ontouchstart' in window)) {{
                        window.ontouchstart = null;
                        window.ontouchend = null;
                        window.ontouchmove = null;
                        window.ontouchcancel = null;
                    }}

                    // Banner & Modal Annoyance Suppression
                    const style = document.createElement('style');
                    style.textContent = `
                        /* Force hide common banner and modal containers */
                        [class*="Banner"], 
                        [class*="ModalContainer"], 
                        [class*="DivBannerContainer"],
                        [class*="TUXModal"],
                        [class*="AppBanner"],
                        [class*="OpenApp"],
                        [class*="ButtonCTAOpenApp"],
                        .euxhv7r0,
                        [class*="download-link"],
                        div[role="dialog"],
                        .tiktok-banner-container,
                        #tiktok-banner-app {{
                            display: none !important;
                            visibility: hidden !important;
                            pointer-events: none !important;
                            opacity: 0 !important;
                        }}
                        
                        /* Ensure the body remains scrollable if a modal tries to lock it */
                        body {{
                            overflow: auto !important;
                            position: static !important;
                        }}
                    `;
                    document.documentElement.appendChild(style);

                    // Reactive removal for dynamic elements
                    const observer = new MutationObserver((mutations) => {{
                        const targets = [
                            'Eksiksiz uygulama deneyimi yaşayın',
                            'Open TikTok',
                            'Uygulamayı aç',
                            'Şimdi değil'
                        ];
                        
                        document.querySelectorAll('div, button, span').forEach(el => {{
                            if (targets.some(t => el.textContent && el.textContent.includes(t))) {{
                                let parent = el;
                                // Seek up to find the container
                                for(let i=0; i<5; i++) {{
                                    if (parent && parent.parentElement) {{
                                        parent = parent.parentElement;
                                    }}
                                }}
                                if (parent) parent.style.display = 'none';
                            }}
                        }});
                    }});

                    observer.observe(document.documentElement, {{
                        childList: true,
                        subtree: true
                    }});
                }})();
            "#, ua);

            // Create main window manually with initialization script and network UA
            let window_builder = WebviewWindowBuilder::new(app, "main", tauri::WebviewUrl::App("index.html".into()))
                .title("TikTok Desktop Pro")
                .inner_size(390.0, 844.0)
                .user_agent(ua)
                .initialization_script(&stealth_script);
            
            let window = window_builder.build().expect("failed to build window");

            let args: Vec<String> = std::env::args().collect();
            if args.contains(&"--dev-crty-mode".to_string()) {
                log_event("SYSTEM", "Developer Mode Activated via CLI");
                #[cfg(debug_assertions)]
                window.open_devtools();
            }

            let window_clone = window.clone();
            std::thread::spawn(move || {
                std::thread::sleep(std::time::Duration::from_secs(3));
                let _ = window_clone.eval("window.location.href = 'https://www.tiktok.com/?app=musically'");
            });
            
            Ok(())
        })
        .invoke_handler(tauri::generate_handler![greet, download_video, get_download_history, open_file, is_dev_mode, get_logs, get_system_stats])
        .run(tauri::generate_context!())
        .expect("error while running tauri application");
}
