import { useState, useEffect, useRef } from "react";
import { Home, Download, User, Settings, Loader2, Folder, Play, ExternalLink, Trash2, Terminal, Bug, X } from "lucide-react";
import { invoke } from "@tauri-apps/api/core";
import { convertFileSrc } from "@tauri-apps/api/core";
import { cn } from "@/lib/utils";
import logo from "./assets/logo.png";
import "./App.css";

interface DownloadMetadata {
  file_name: string;
  url: string;
  timestamp: string;
  path: string;
}

interface LogEntry {
  event_type: string;
  message: string;
  timestamp: string;
}

interface SystemStats {
  cpu_usage: number;
  memory_used: number;
  memory_total: number;
}

const SidebarItem = ({ icon: Icon, label, active, onClick }: { icon: any, label: string, active?: boolean, onClick?: () => void }) => (
  <div 
    onClick={onClick}
    className={cn(
      "flex items-center gap-3 px-4 py-3 cursor-pointer transition-all duration-300 rounded-xl group",
      active ? "bg-white/10 text-white" : "text-gray-400 hover:text-white hover:bg-white/5"
    )}
  >
    <Icon className={cn("w-5 h-5 transition-transform group-hover:scale-110", active && "text-[#ff0050]")} />
    <span className="font-medium">{label}</span>
  </div>
);

const HistoryItem = ({ item, onOpen, onPlay }: { item: DownloadMetadata, onOpen: (path: string) => void, onPlay: (path: string) => void }) => {
    const date = new Date(item.timestamp).toLocaleDateString();
    const time = new Date(item.timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    return (
        <div className="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all group overflow-hidden relative">
            <div 
                onClick={() => onPlay(item.path)}
                className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0 border border-primary/20 cursor-pointer hover:bg-primary/20 transition-all z-10"
            >
                <Play className="w-5 h-5 text-primary" />
            </div>
            <div className="flex-1 min-w-0 text-left z-10">
                <div className="font-semibold text-sm truncate">{item.file_name}</div>
                <div className="text-[10px] text-gray-500 font-mono mt-1 flex items-center gap-2">
                    <span>{date} at {time}</span>
                    <span className="w-1 h-1 rounded-full bg-gray-700"></span>
                    <span className="truncate max-w-[150px]">{item.url}</span>
                </div>
            </div>
            <div className="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                <button 
                    onClick={() => onOpen(item.path)}
                    className="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition-colors"
                    title="Open in Folder"
                >
                    <Folder className="w-4 h-4" />
                </button>
                <a 
                    href={item.url} 
                    target="_blank" 
                    rel="noreferrer"
                    className="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition-colors"
                    title="View Source"
                >
                    <ExternalLink className="w-4 h-4" />
                </a>
            </div>
        </div>
    );
};

const VideoPlayer = ({ path, onClose }: { path: string, onClose: () => void }) => {
    const videoSrc = convertFileSrc(path);
    
    return (
        <div className="fixed inset-0 bg-black/90 backdrop-blur-3xl z-[100] flex items-center justify-center p-8 animate-in fade-in zoom-in duration-300">
            <button 
                onClick={onClose}
                className="absolute top-8 right-8 p-3 bg-white/10 hover:bg-white/20 rounded-full transition-all text-white z-[110]"
            >
                <X className="w-6 h-6" />
            </button>
            <div className="w-full max-w-4xl aspect-video bg-black rounded-3xl overflow-hidden shadow-2xl border border-white/10 relative group">
                <video 
                    src={videoSrc} 
                    controls 
                    autoPlay 
                    className="w-full h-full object-contain"
                />
            </div>
        </div>
    );
};

const DebugHUD = ({ logs, stats, onClose }: { logs: LogEntry[], stats: SystemStats | null, onClose: () => void }) => (
    <div className="absolute bottom-6 right-6 w-96 max-h-[500px] bg-black/95 border border-primary/30 rounded-2xl backdrop-blur-3xl z-50 flex flex-col shadow-2xl animate-in slide-in-from-bottom-5 duration-300">
        <div className="p-4 border-b border-white/10 flex items-center justify-between">
            <div className="flex items-center gap-2 text-primary font-mono text-[10px] tracking-widest uppercase">
                <Terminal className="w-3 h-3" />
                <span>Dev_System_HUD</span>
            </div>
            <button onClick={onClose} className="p-1 hover:bg-white/10 rounded-md transition-colors">
                <X className="w-3 h-3 text-gray-500" />
            </button>
        </div>
        
        {/* Metrics Bar */}
        {stats && (
            <div className="px-4 py-3 bg-white/5 border-b border-white/5 grid grid-cols-2 gap-4">
                <div className="space-y-1">
                    <div className="text-[8px] text-gray-500 font-mono uppercase tracking-tighter">CPU_Usage</div>
                    <div className="flex items-center gap-2">
                        <div className="flex-1 h-1 bg-gray-800 rounded-full overflow-hidden">
                            <div className="h-full bg-primary transition-all duration-500" style={{ width: `${stats.cpu_usage}%` }}></div>
                        </div>
                        <span className="text-[9px] font-mono text-primary">{stats.cpu_usage.toFixed(1)}%</span>
                    </div>
                </div>
                <div className="space-y-1">
                    <div className="text-[8px] text-gray-500 font-mono uppercase tracking-tighter">RAM_Load</div>
                    <div className="flex items-center gap-2">
                        <div className="flex-1 h-1 bg-gray-800 rounded-full overflow-hidden">
                            <div className="h-full bg-secondary transition-all duration-500" style={{ width: `${(stats.memory_used / stats.memory_total) * 100}%` }}></div>
                        </div>
                        <span className="text-[9px] font-mono text-secondary">{(stats.memory_used / (1024 * 1024 * 1024)).toFixed(1)}GB</span>
                    </div>
                </div>
            </div>
        )}

        <div className="flex-1 overflow-y-auto p-4 space-y-2 font-mono text-[9px] custom-scrollbar">
            {logs.map((log, i) => (
                <div key={i} className="flex gap-2">
                    <span className="text-gray-600">[{new Date(log.timestamp).toLocaleTimeString([], { hour12: false })}]</span>
                    <span className={cn(
                        log.event_type === "DOWNLOAD_ERROR" ? "text-red-500" :
                        log.event_type === "DOWNLOAD_SUCCESS" ? "text-green-500" :
                        "text-primary/70"
                    )}>{log.event_type}</span>
                    <span className="text-gray-400 truncate">{log.message}</span>
                </div>
            ))}
        </div>
    </div>
);

function App() {
  const [loading, setLoading] = useState(true);
  const [activeTab, setActiveTab] = useState("home");
  const [isDev, setIsDev] = useState(false);
  const [showHud, setShowHud] = useState(false);
  
  // States
  const [playingPath, setPlayingPath] = useState<string | null>(null);
  const [videoUrl, setVideoUrl] = useState("");
  const [downloadStatus, setDownloadStatus] = useState<"idle" | "loading" | "success" | "error">("idle");
  const [statusMsg, setStatusMsg] = useState("");
  const [history, setHistory] = useState<DownloadMetadata[]>([]);
  const [logs, setLogs] = useState<LogEntry[]>([]);
  const [systemStats, setSystemStats] = useState<SystemStats | null>(null);
  
  const hudInterval = useRef<any>(null);

  const fetchHistory = async () => {
    try {
        const data = await invoke<DownloadMetadata[]>("get_download_history");
        setHistory([...data].reverse()); 
    } catch (err) {
        console.error("Failed to fetch history:", err);
    }
  };

  const fetchLogsAndStats = async () => {
    try {
        const [logData, statData] = await Promise.all([
            invoke<LogEntry[]>("get_logs"),
            invoke<SystemStats>("get_system_stats")
        ]);
        setLogs(logData);
        setSystemStats(statData);
    } catch (err) {
        console.error("Failed to fetch diagnostics:", err);
    }
  };

  const checkDevMode = async () => {
    const dev = await invoke<boolean>("is_dev_mode");
    setIsDev(!!dev);
    if (dev) setShowHud(true);
  };

  useEffect(() => {
    const timer = setTimeout(() => setLoading(false), 3000);
    fetchHistory();
    checkDevMode();
    return () => clearTimeout(timer);
  }, []);

  useEffect(() => {
    if (showHud) {
        fetchLogsAndStats();
        hudInterval.current = setInterval(fetchLogsAndStats, 2000);
    } else {
        clearInterval(hudInterval.current);
    }
    return () => clearInterval(hudInterval.current);
  }, [showHud]);

  useEffect(() => {
    if (activeTab === "downloads") {
        fetchHistory();
    }
  }, [activeTab]);

  const handleDownload = async () => {
    if (!videoUrl) return;
    setDownloadStatus("loading");
    setStatusMsg("Establishing downlink...");
    
    try {
      const filename = `tiktok_pro_${Date.now()}.mp4`;
      await invoke<string>("download_video", { url: videoUrl, filename });
      setDownloadStatus("success");
      setStatusMsg("Binary capture completed.");
      setVideoUrl("");
      fetchHistory();
      if (showHud) fetchLogsAndStats();
    } catch (error) {
      setDownloadStatus("error");
      setStatusMsg(typeof error === "string" ? error : "Core signal failure. Try again.");
    }
  };

  const openFileFolder = async (path: string) => {
    try {
        await invoke("open_file", { path });
    } catch (err) {
        console.error("Failed to open file:", err);
    }
  };

  if (loading) {
    return (
      <div className="flex flex-col items-center justify-center w-full h-screen bg-black overflow-hidden splash-container relative">
        <div className="cyber-grid opacity-20"></div>
        <img src={logo} className="w-40 h-auto splash-logo mb-8 z-10" alt="TikTok Logo" />
        <div className="w-52 h-1 bg-gray-800 rounded-full overflow-hidden z-10">
          <div className="h-full bg-gradient-to-r from-[#ff0050] to-[#00f2ea] animate-loading"></div>
        </div>
        <div className="mt-4 text-[10px] text-primary/50 font-mono tracking-[0.2em] uppercase animate-pulse z-10">
            Initalizing_Core_System
        </div>
      </div>
    );
  }

  return (
    <div className="flex h-screen bg-[#030303] text-white font-sans selection:bg-[#ff0050]/30 overflow-hidden relative">
      {/* Background Dynamics */}
      <div className="cyber-grid opacity-40"></div>
      <div className="orb orb-primary"></div>
      <div className="orb orb-secondary"></div>
      
      {/* Dev Mode HUD */}
      {showHud && <DebugHUD logs={logs} stats={systemStats} onClose={() => setShowHud(false)} />}
      
      {/* Video Player Overlay */}
      {playingPath && <VideoPlayer path={playingPath} onClose={() => setPlayingPath(null)} />}

      {/* Sidebar */}
      <aside className="w-64 border-r border-white/5 flex flex-col p-4 bg-black/40 backdrop-blur-3xl z-20 shrink-0">
        <div className="flex items-center gap-3 px-4 mb-10">
          <img src={logo} className="w-8 h-8" alt="Logo" />
          <span className="text-xl font-bold tracking-tight bg-gradient-to-r from-white to-gray-400 bg-clip-text text-transparent">TikTok Pro</span>
        </div>

        <nav className="flex-1 space-y-2">
          <SidebarItem icon={Home} label="Feed" active={activeTab === "home"} onClick={() => setActiveTab("home")} />
          <SidebarItem icon={Download} label="Downloads" active={activeTab === "downloads"} onClick={() => setActiveTab("downloads")} />
          <SidebarItem icon={User} label="Accounts" active={activeTab === "accounts"} onClick={() => setActiveTab("accounts")} />
        </nav>

        <div className="mt-auto pt-4 border-t border-white/5 space-y-2">
          {isDev && (
              <SidebarItem icon={Terminal} label="System HUD" active={showHud} onClick={() => setShowHud(!showHud)} />
          )}
          <SidebarItem icon={Settings} label="Settings" active={activeTab === "settings"} onClick={() => setActiveTab("settings")} />
          <div className="px-4 py-2 text-[10px] text-gray-500 font-mono tracking-widest uppercase">
            Designed by CRTYPUBG
          </div>
        </div>
      </aside>

      {/* Main Content Area */}
      <main className="flex-1 relative overflow-hidden bg-transparent z-10">
        {activeTab === "home" ? (
          <div className="w-full h-full relative z-10 glass-card">
             <iframe 
                src="https://www.tiktok.com/@home"
                className="w-full h-full border-none"
                title="TikTok"
             />
          </div>
        ) : activeTab === "downloads" ? (
          <div className="w-full h-full grid grid-cols-1 lg:grid-cols-[1fr_400px] animate-in fade-in duration-500 relative z-10">
            {/* Download Interface */}
            <div className="flex flex-col items-center justify-center p-12 border-r border-white/5">
                <div className="w-20 h-20 bg-white/5 rounded-2xl flex items-center justify-center mb-6 border border-white/10 shadow-2xl backdrop-blur-3xl glass-card">
                    <Download className="w-10 h-10 text-secondary" />
                </div>
                <h1 className="text-3xl font-bold mb-2">Video Archive</h1>
                <p className="text-gray-400 max-w-sm mb-10 text-center">
                    Universal binary capture for TikTok videos. Zero watermarks, maximum fidelity.
                </p>
                
                <div className="w-full max-w-md space-y-4">
                    <input 
                    type="text"
                    placeholder="Capture URL..."
                    value={videoUrl}
                    onChange={(e) => setVideoUrl(e.target.value)}
                    className="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 outline-none focus:border-primary/50 transition-all text-sm placeholder:text-gray-600 font-mono backdrop-blur-3xl glass-card"
                    />
                
                    <button 
                        onClick={handleDownload}
                        disabled={!videoUrl || downloadStatus === "loading"}
                        className="w-full py-4 rounded-2xl bg-white text-black font-bold hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50 disabled:scale-100 flex items-center justify-center gap-2 shadow-xl shadow-white/5"
                    >
                        {downloadStatus === "loading" ? (
                        <>
                            <Loader2 className="w-5 h-5 animate-spin" />
                            CRYPTO_LINK_ACTIVE...
                        </>
                        ) : (
                        <>
                            <Download className="w-5 h-5" />
                            INIT_CAPTURE
                        </>
                        )}
                    </button>
                    
                    {downloadStatus !== "idle" && (
                        <div className={cn(
                        "mt-6 p-4 rounded-xl flex items-center gap-3 text-[10px] font-mono uppercase tracking-wider animate-in fade-in slide-in-from-top-2",
                        downloadStatus === "success" ? "bg-green-500/10 text-green-400 border border-green-500/20" : 
                        downloadStatus === "error" ? "bg-red-500/10 text-red-400 border border-red-500/20" : 
                        "bg-white/5 text-gray-400 border border-white/10"
                        )}>
                        <span className="flex-1 text-left">{statusMsg}</span>
                        </div>
                    )}
                </div>
            </div>

            {/* Archive History */}
            <div className="flex flex-col h-full bg-black/10 backdrop-blur-3xl">
                <div className="p-6 border-bottom border-white/5 flex items-center justify-between">
                    <h2 className="text-sm font-bold font-mono tracking-widest uppercase text-gray-400">Archive_Log</h2>
                    <span className="text-[10px] bg-white/5 px-2 py-1 rounded-md text-gray-500">{history.length} ITEMS</span>
                </div>
                <div className="flex-1 overflow-y-auto p-6 space-y-3 custom-scrollbar">
                    {history.length > 0 ? (
                        history.map((item, idx) => (
                            <HistoryItem 
                                key={idx} 
                                item={item} 
                                onOpen={openFileFolder} 
                                onPlay={(path) => setPlayingPath(path)} 
                            />
                        ))
                    ) : (
                        <div className="h-full flex flex-col items-center justify-center text-center opacity-20">
                            <Trash2 className="w-8 h-8 mb-4" />
                            <p className="text-[10px] font-mono uppercase tracking-[0.2em]">Archive_Empty</p>
                        </div>
                    )}
                </div>
            </div>
          </div>
        ) : activeTab === "settings" ? (
            <div className="w-full h-full flex flex-col items-center justify-center p-8 text-center animate-in fade-in zoom-in duration-500 relative z-10">
                <div className="w-20 h-20 bg-white/5 rounded-2xl flex items-center justify-center mb-6 border border-white/10 backdrop-blur-3xl glass-card">
                    <Settings className="w-10 h-10 text-gray-400" />
                </div>
                <h2 className="text-2xl font-bold mb-2">System Diagnostics</h2>
                <p className="text-gray-400 max-w-sm mb-8">
                    Configure your instance and report anomalies to the core developer.
                </p>
                <div className="w-full max-w-sm grid gap-3">
                    <div className="p-4 rounded-xl bg-white/5 border border-white/10 flex items-center justify-between backdrop-blur-3xl glass-card">
                        <span className="text-xs font-mono uppercase">Version_Control</span>
                        <span className="text-xs text-primary">v1.0.0_STABLE</span>
                    </div>
                    <button 
                        onClick={() => alert("Bug report logic triggered. Logs are being prepared...")}
                        className="p-4 rounded-xl bg-primary/20 hover:bg-primary/30 text-primary border border-primary/30 flex items-center justify-center gap-2 transition-all backdrop-blur-3xl glass-card"
                    >
                        <Bug className="w-4 h-4" />
                        <span className="text-xs font-bold uppercase">Report_Core_Anomaly</span>
                    </button>
                </div>
            </div>
        ) : (
          <div className="w-full h-full flex flex-col items-center justify-center p-8 text-center animate-in fade-in zoom-in duration-500 relative z-10">
            <div className="w-20 h-20 bg-white/5 rounded-2xl flex items-center justify-center mb-6 border border-white/10 backdrop-blur-3xl glass-card">
               <User className="w-10 h-10 text-primary" />
            </div>
            <h2 className="text-2xl font-bold mb-2">Accounts Feature</h2>
            <p className="text-gray-400 max-w-sm">
              Multi-account sandboxing is currently being hardened.
            </p>
          </div>
        )}
      </main>
    </div>
  );
}

export default App;
