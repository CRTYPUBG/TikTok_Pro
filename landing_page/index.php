<?php
// --- SEO & CONFIGURATION ---
$app_name = "TikTok Pro";
$app_version = "v1.0.0_STABLE";
$author = "CRTYPUBG";
$site_url = "https://tiktok.crty-dev.com";
$description = "The ultimate TikTok desktop experience. High-performance mobile emulation, ad-free viewing, and local video archiving. Optimized for power users by CRTYPUBG.";
$keywords = "TikTok Pro, TikTok Desktop Download, TikTok PC Optimizer, TikTok Video Downloader, TikTok Mobile Emulation PC";

// Stealth Download Handler
if (isset($_GET['access'])) {
    $token = $_GET['access'];
    $file = '';
    
    if ($token === 'win_x64_exe') {
        $file = '../download/TikTok_Pro_v1.0.0_x64.exe';
        $filename = 'TikTok_Pro_v1.0.0_x64.exe';
    } else if ($token === 'win_x64_msi') {
        $file = '../download/TikTok_Pro_v1.0.0_x64.msi';
        $filename = 'TikTok_Pro_v1.0.0_x64.msi';
    } else if ($token === 'mac_universal_dmg') {
        $file = '../download/TikTok_Pro_v1.0.0.dmg';
        $filename = 'TikTok_Pro_v1.0.0.dmg';
    } else if ($token === 'mac_universal_pkg') {
        $file = '../download/TikTok_Pro_v1.0.0.pkg';
        $filename = 'TikTok_Pro_v1.0.0.pkg';
    }

    if ($file && file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $app_name; ?> | Official Desktop Optimization</title>
    
    <!-- Meta Tags -->
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="keywords" content="<?php echo $keywords; ?>">
    <meta name="author" content="<?php echo $author; ?>">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #ff0050;
            --secondary: #00f2ea;
            --bg: #030303;
            --text: #ffffff;
            --glass: rgba(255, 255, 255, 0.03);
            --border: rgba(255, 255, 255, 0.08);
            --accent-glow: rgba(255, 0, 80, 0.15);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Outfit', sans-serif;
            overflow-x: hidden;
            line-height: 1.5;
        }

        /* --- Animations --- */
        @keyframes float { 0%, 100% { transform: translateY(0) rotate(0); } 50% { transform: translateY(-20px) rotate(2deg); } }
        @keyframes pulse-glow { 0%, 100% { opacity: 0.5; } 50% { opacity: 0.8; } }
        @keyframes grid-move { 0% { transform: translateY(0); } 100% { transform: translateY(40px); } }

        /* --- Background Layers --- */
        .cyber-bg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            background: radial-gradient(circle at 50% 50%, #111 0%, #030303 100%);
        }

        .grid-overlay {
            position: absolute;
            top: -100%; left: 0; width: 100%; height: 200%;
            background-image: 
                linear-gradient(to right, var(--border) 1px, transparent 1px),
                linear-gradient(to bottom, var(--border) 1px, transparent 1px);
            background-size: 40px 40px;
            mask-image: linear-gradient(to bottom, transparent, black, transparent);
            animation: grid-move 4s linear infinite;
            opacity: 0.3;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            z-index: -1;
            animation: pulse-glow 4s ease-in-out infinite;
        }
        .orb-1 { top: 10%; right: 10%; width: 500px; height: 500px; background: var(--accent-glow); }
        .orb-2 { bottom: 10%; left: 5%; width: 400px; height: 400px; background: rgba(0, 242, 234, 0.1); }

        /* --- Components --- */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
        }

        header {
            padding: 3rem 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -1px;
        }

        .logo img {
            width: 32px;
            filter: drop-shadow(0 0 8px var(--primary));
        }

        .sys-badge {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            color: #444;
            padding: 0.4rem 0.8rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--glass);
        }

        .hero {
            padding: 8rem 0 6rem;
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: clamp(3rem, 8vw, 6rem);
            font-weight: 800;
            line-height: 0.95;
            letter-spacing: -4px;
            background: linear-gradient(to bottom, #fff 30%, #555 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 2rem;
        }

        .hero p {
            font-size: 1.25rem;
            color: #888;
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* --- Download Module --- */
        .download-interface {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-bottom: 8rem;
        }

        .os-card {
            background: var(--glass);
            border: 1px solid var(--border);
            padding: 2.5rem;
            border-radius: 32px;
            backdrop-filter: blur(20px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .os-card:hover {
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-8px);
            background: rgba(255, 255, 255, 0.05);
        }

        .os-info { text-align: left; }
        .os-info h3 { font-size: 1.5rem; margin-bottom: 0.5rem; }
        .os-info span { font-size: 0.8rem; color: #555; text-transform: uppercase; letter-spacing: 2px; }

        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .btn {
            padding: 1.2rem;
            border-radius: 16px;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            transition: all 0.3s;
            font-size: 0.95rem;
        }

        .btn-primary { 
            background: #fff; 
            color: #000; 
            border: none;
        }
        .btn-primary:hover { 
            background: var(--secondary);
            transform: scale(1.02);
        }

        .btn-outline {
            border: 1px solid var(--border);
            color: #fff;
            background: rgba(255, 255, 255, 0.02);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            text-transform: uppercase;
        }
        .btn-outline:hover {
            border-color: #fff;
            background: rgba(255, 255, 255, 0.08);
        }

        /* --- Dashboard Simulation --- */
        .dashboard-shards {
            background: var(--glass);
            border: 1px solid var(--border);
            border-radius: 32px;
            padding: 4rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 4rem;
            margin-bottom: 8rem;
            backdrop-filter: blur(40px);
        }

        .stat-shrd {
            text-align: center;
        }
        .stat-shrd .val {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            font-family: 'JetBrains Mono', monospace;
            display: block;
            margin-bottom: 0.5rem;
        }
        .stat-shrd .lbl {
            font-size: 0.7rem;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        footer {
            padding: 6rem 0;
            text-align: center;
            border-top: 1px solid var(--border);
        }

        .footer-logo {
            opacity: 0.3;
            margin-bottom: 2rem;
            filter: grayscale(1);
        }

        .copyright {
            font-size: 0.7rem;
            color: #333;
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: 2px;
        }

        /* --- Media Queries --- */
        @media (max-width: 768px) {
            .hero h1 { font-size: 3.5rem; }
            .dashboard-shards { gap: 2rem; padding: 2rem; }
        }
    </style>
</head>
<body>
    <div class="cyber-bg">
        <div class="grid-overlay"></div>
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <div class="container">
        <header>
            <div class="logo">
                <img src="../images/slapsh-logo-tt.png" alt="Logo">
                <span>TikTok Pro</span>
            </div>
            <div class="sys-badge">OSX_WIN_STABLE_<?php echo date("Y"); ?></div>
        </header>

        <main>
            <section class="hero">
                <h1>The Future of<br>TikTok Desktop.</h1>
                <p>Pure mobile fidelity. Zero tracking. High-bitrate archiving. Designed for developers and power users.</p>
                
                <div class="download-interface">
                    <!-- Windows Node -->
                    <article class="os-card">
                        <div class="os-info">
                            <span>Platform: Windows</span>
                            <h3>Windows 10 / 11</h3>
                        </div>
                        <div class="btn-group">
                            <a href="index.php?access=win_x64_exe" class="btn btn-primary">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                                GET_EXECUTABLE
                            </a>
                            <a href="index.php?access=win_x64_msi" class="btn btn-outline">
                                INIT_MSI_PACKAGE
                            </a>
                        </div>
                    </article>

                    <!-- macOS Node -->
                    <article class="os-card">
                        <div class="os-info">
                            <span>Platform: Darwin</span>
                            <h3>Apple Silicon / Intel</h3>
                        </div>
                        <div class="btn-group">
                            <a href="index.php?access=mac_universal_dmg" class="btn btn-primary">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 19V5M5 12l7 7 7-7"/></svg>
                                MOUNT_DMG_IMAGE
                            </a>
                            <a href="index.php?access=mac_universal_pkg" class="btn btn-outline">
                                INSTALL_PKG_CORE
                            </a>
                        </div>
                    </article>
                </div>
            </section>

            <section class="dashboard-shards">
                <div class="stat-shrd">
                    <span class="val">1.2ms</span>
                    <span class="lbl">LATENCY_CORE</span>
                </div>
                <div class="stat-shrd">
                    <span class="val">99.9%</span>
                    <span class="lbl">BITRATE_EFFICIENCY</span>
                </div>
                <div class="stat-shrd">
                    <span class="val">0.0</span>
                    <span class="lbl">TRACKING_LEAK</span>
                </div>
                <div class="stat-shrd">
                    <span class="val"><?php echo date("sh"); ?></span>
                    <span class="lbl">ACTIVE_HOST_NODES</span>
                </div>
            </section>
        </main>

        <footer>
            <div class="logo footer-logo">
                <img src="../images/slapsh-logo-tt.png" alt="Logo" width="24">
            </div>
            <div class="copyright">
                © <?php echo date("Y"); ?> CRTY_DEFENSE. ALL SYSTEMS ENCRYPTED.
            </div>
        </footer>
    </div>
</body>
</html>
