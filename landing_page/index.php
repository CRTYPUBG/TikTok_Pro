<?php
// --- CONFIGURATION ---
$app_name = "TikTok Pro";
$app_version = "v1.0.0";
$author = "CRTY_LABS";
$description = "The definitive TikTok desktop suite. Built with 2026 design principles for power users and developers.";

// Download Handler (Sanitized)
if (isset($_GET['access'])) {
    $token = $_GET['access'];
    $file = '';
    
    // Using production-ready paths
    if ($token === 'win_x64_exe') {
        $file = '../src-tauri/target/release/bundle/nsis/TikTok Pro_1.0.0_x64-setup.exe';
        $filename = 'TikTok_Pro_Setup.exe';
    } else if ($token === 'win_x64_msi') {
        $file = '../src-tauri/target/release/bundle/msi/TikTok Pro_1.0.0_x64_en-US.msi';
        $filename = 'TikTok_Pro_v1.0.0.msi';
    } else if ($token === 'mac_universal_dmg') {
        $file = '../src-tauri/target/release/bundle/dmg/TikTok Pro_1.0.0_universal.dmg';
        $filename = 'TikTok_Pro_v1.0.0.dmg';
    }

    if ($file && file_exists($file)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
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
    <title><?php echo $app_name; ?> | Next-Gen TikTok Desktop</title>
    
    <!-- Design Assets -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg: #030303;
            --fg: #ffffff;
            --primary: #fe2c55;
            --secondary: #25f4ee;
            --card-bg: rgba(255, 255, 255, 0.03);
            --card-border: rgba(255, 255, 255, 0.08);
            --glass: rgba(255, 255, 255, 0.05);
            --neon-glow: rgba(254, 44, 85, 0.2);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--bg);
            color: var(--fg);
            font-family: 'Outfit', sans-serif;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* --- Background Matrix --- */
        .neon-canvas {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            background: radial-gradient(circle at 50% 50%, #111 0%, #030303 100%);
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            animation: moveOrb 20s infinite alternate;
        }
        .orb-red { width: 600px; height: 600px; background: var(--primary); top: -100px; right: -100px; }
        .orb-blue { width: 500px; height: 500px; background: var(--secondary); bottom: -100px; left: -100px; animation-delay: -5s; }

        @keyframes moveOrb {
            0% { transform: translate(0, 0); }
            100% { transform: translate(100px, 100px); }
        }

        /* --- Layout --- */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 100px 24px;
        }

        header {
            text-align: center;
            margin-bottom: 80px;
        }

        header .badge {
            display: inline-block;
            padding: 6px 12px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 92px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--secondary);
            margin-bottom: 24px;
        }

        header h1 {
            font-size: clamp(3rem, 10vw, 5.5rem);
            font-weight: 800;
            line-height: 0.9;
            letter-spacing: -4px;
            background: linear-gradient(to bottom, #fff 40%, rgba(255,255,255,0.4));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        /* --- Bento Grid --- */
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(3, 200px);
            gap: 20px;
        }

        .bento-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 28px;
            padding: 32px;
            backdrop-filter: blur(12px);
            transition: all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .bento-card:hover { border-color: rgba(255,255,255,0.2); transform: translateY(-5px); background: rgba(255,255,255,0.05); }

        .bento-card h3 { font-size: 1.4rem; font-weight: 700; margin-bottom: 8px; }
        .bento-card p { font-size: 0.95rem; color: rgba(255,255,255,0.6); line-height: 1.4; }

        .bento-card .icon { 
            width: 40px; height: 40px; 
            margin-bottom: 24px; 
            background: rgba(255,255,255,0.03); 
            border-radius: 12px; 
            display: flex; align-items: center; justify-content: center;
        }

        /* Specific Cards */
        .card-dl { grid-column: span 2; grid-row: span 2; background: linear-gradient(135deg, rgba(254, 44, 85, 0.05), transparent); display: flex; align-items: center; justify-content: center; text-align: center; gap: 32px; }
        .card-dl h2 { font-size: 2.5rem; letter-spacing: -2px; margin-bottom: 24px; }
        
        .dl-btns { display: flex; flex-direction: column; gap: 12px; width: 100%; max-width: 280px; }
        .btn {
            padding: 18px;
            border-radius: 16px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .btn-main { background: #fff; color: #000; }
        .btn-main:hover { background: var(--secondary); transform: scale(1.02); }
        .btn-sec { border: 1px solid var(--card-border); color: #fff; }
        .btn-sec:hover { background: rgba(255,255,255,0.05); border-color: #fff; }

        .card-stats { grid-column: span 2; background: var(--bg); border: 1px dashed var(--card-border); }
        .stats-row { display: flex; height: 100%; align-items: center; justify-content: space-around; }
        .stat-item { text-align: center; }
        .stat-val { font-family: 'JetBrains Mono', monospace; font-size: 2.5rem; font-weight: 700; color: var(--secondary); display: block; }
        .stat-lbl { font-size: 0.7rem; text-transform: uppercase; color: #444; letter-spacing: 2px; }

        .card-wide { grid-column: span 2; }
        .card-tall { grid-row: span 2; }

        /* Icon Colors */
        .ic-red { color: var(--primary); }
        .ic-cyan { color: var(--secondary); }

        footer {
            margin-top: 100px;
            text-align: center;
            opacity: 0.2;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            letter-spacing: 2px;
        }

        @media (max-width: 900px) {
            .bento-grid { grid-template-columns: 1fr 1fr; grid-template-rows: auto; }
            .card-dl { grid-column: span 2; padding: 60px 24px; }
            .card-stats { grid-column: span 2; padding: 40px 0; }
        }
    </style>
</head>
<body>

    <div class="neon-canvas">
        <div class="orb orb-red"></div>
        <div class="orb orb-blue"></div>
    </div>

    <div class="container">
        <header>
            <span class="badge">Experimental Suite 1.0</span>
            <h1>TikTok Pro</h1>
            <p style="margin-top: 32px; color: #555; max-width: 600px; margin-left: auto; margin-right: auto; font-size: 1.25rem;">The definitivo desktop integration. Optimized for high-bitrate streaming and local archiving.</p>
        </header>

        <div class="bento-grid">
            <!-- DOWNLOAD CORE -->
            <div class="bento-card card-dl">
                <div style="width: 100%;">
                    <h2>Native Core.</h2>
                    <div class="dl-btns">
                        <a href="index.php?access=win_x64_exe" class="btn btn-main">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                            Get Windows EXE
                        </a>
                        <a href="index.php?access=mac_universal_dmg" class="btn btn-sec">
                            Mount Apple DMG
                        </a>
                    </div>
                </div>
            </div>

            <!-- STATS HUB -->
            <div class="bento-card card-stats">
                <div class="stats-row">
                    <div class="stat-item">
                        <span class="stat-val">1.0ms</span>
                        <span class="stat-lbl">CORE_LATENCY</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-val">Zero</span>
                        <span class="stat-lbl">TRACKING_LEAK</span>
                    </div>
                </div>
            </div>

            <!-- FEATURE: MOBILE ENGINE -->
            <div class="bento-card">
                <div class="icon ic-cyan">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 4h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zM12 18v2"/></svg>
                </div>
                <h3>Mobile SDK</h3>
                <p>Native mobile emulation bypasses all desktop limiters.</p>
            </div>

            <!-- FEATURE: AD FREE -->
            <div class="bento-card">
                <div class="icon ic-red">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m11 17 3 3 7-7"/><path d="m18 18 3 3"/><path d="M14 11V9a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2v-2"/></svg>
                </div>
                <h3>Pure View</h3>
                <p>Algorithmic ad-stripping for an uninterrupted feed.</p>
            </div>

            <!-- FEATURE: ARCHIVE -->
            <div class="bento-card card-wide">
                <div class="icon ic-cyan">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                </div>
                <h3>Deep Archive Engine</h3>
                <p>One-click video archiving with automatic metadata synchronization. Store your favorites locally in high-bitrate formats.</p>
            </div>

            <!-- FEATURE: STEALTH -->
            <div class="bento-card card-tall">
                <div class="icon ic-red">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h3>Stealth Mode</h3>
                <p>Advanced HTTP header manipulation and atob hardening. Your desktop identity remains completely anonymous to TikTok's analytics engine.</p>
            </div>

        </div>

        <footer>
            © 2026 CRTY_DEFENSE. ALL SYSTEMS OPERATIONAL.
        </footer>
    </div>

    <script>
        // Smooth parallax for bento cards
        document.addEventListener('mousemove', (e) => {
            const cards = document.querySelectorAll('.bento-card');
            const xOffset = (window.innerWidth / 2 - e.pageX) / 100;
            const yOffset = (window.innerHeight / 2 - e.pageY) / 100;
            
            cards.forEach(card => {
                if (!card.classList.contains('card-stats')) {
                    card.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
                }
            });
        });
    </script>
</body>
</html>
