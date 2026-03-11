<?php
// --- CONFIGURATION ---
$app_name = "TikTok Pro";
$app_version = "v1.0.0";
$author = "CRTY_LABS";
$description = "Build the ultimate TikTok experience. Optimized desktop suite for power users.";

// Download Handler
if (isset($_GET['access'])) {
    $token = $_GET['access'];
    $file = '';
    
    if ($token === 'win_x64_exe') { $file = 'download/TikTok_Pro_v1.0.0_x64-setup.exe'; $filename = 'TikTok_Pro_v1.0.0.exe'; }
    else if ($token === 'win_x64_msi') { $file = 'download/TikTok_Pro_v1.0.0_x64_en-US.msi'; $filename = 'TikTok_Pro_v1.0.0.msi'; }
    else if ($token === 'mac_universal_dmg') { $file = 'download/TikTok_Pro_v1.0.0.dmg'; $filename = 'TikTok_Pro_v1.0.0.dmg'; }

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
    <title><?php echo $app_name; ?> | Striking Minimalist Experience</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        @font-face {
            font-display: fallback;
            font-family: TikTokFont;
            font-style: normal;
            font-weight: 400;
            src: url(https://sf16-website-login.neutral.ttwstatic.com/obj/tiktok_web_login_static/tiktok_fonts/TikTokFont-Regular.woff2?_default_font=1&v=2) format("woff2");
        }

        :root {
            --bg: #000000;
            --fg: #ffffff;
            --primary: #fe2c55;
            --secondary: #25f4ee;
            --glow: #00768c;
            --border: rgba(255, 255, 255, 0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--bg);
            color: var(--fg);
            font-family: 'Outfit', 'TikTokFont', sans-serif;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        /* --- Header --- */
        nav {
            padding: 24px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0; width: 100%;
            z-index: 1000;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
        }

        .logo-box {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 900;
            font-size: 1.4rem;
            letter-spacing: -1px;
        }
        .logo-box img { width: 32px; }
        .logo-box .badge {
            background: var(--primary);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
        }

        .nav-links {
            display: flex;
            gap: 32px;
            font-size: 0.9rem;
            font-weight: 600;
            color: rgba(255,255,255,0.7);
        }

        .nav-btn {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            padding: 8px 24px;
            border-radius: 92px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }
        .nav-btn:hover { background: rgba(255,255,255,0.2); }

        /* --- Global Sections --- */
        section {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 100px 24px;
            position: relative;
        }

        /* --- Hero --- */
        #hero {
            z-index: 10;
        }

        .hero-title {
            font-size: clamp(3rem, 10vw, 7.5rem);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -3px;
            max-width: 1000px;
            margin-bottom: 40px;
            background: linear-gradient(to bottom, #fff 50%, rgba(255,255,255,0.4));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .dl-btn {
            background: var(--primary);
            color: white;
            padding: 24px 60px;
            border-radius: 92px;
            font-size: 1.4rem;
            font-weight: 800;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: transform 0.3s cubic-bezier(0.2, 1, 0.2, 1), box-shadow 0.3s;
            box-shadow: 0 10px 40px rgba(254, 44, 85, 0.4);
        }
        .dl-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 60px rgba(254, 44, 85, 0.6);
        }

        .os-info {
            margin-top: 24px;
            font-size: 1rem;
            color: rgba(255,255,255,0.5);
            font-weight: 500;
        }

        /* --- Product Frames (Scroll Reveal) --- */
        .feature-container {
            width: 100%;
            max-width: 900px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 40px;
            padding: 2px;
            position: relative;
            transform: translateY(100px);
            opacity: 0;
            transition: transform 1.2s cubic-bezier(0.2, 0.8, 0.2, 1), opacity 0.8s ease;
        }

        .feature-container.reveal {
            transform: translateY(0);
            opacity: 1;
        }

        .feature-inner {
            background: #0a0a0a;
            border-radius: 38px;
            padding: 80px 40px;
            overflow: hidden;
            position: relative;
        }

        .feature-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 24px;
            display: block;
        }

        .feature-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 24px;
            letter-spacing: -2px;
        }

        .feature-desc {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.6);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* --- Visual Frames --- */
        .mockup-frame {
            margin-top: 60px;
            width: 100%;
            aspect-ratio: 16/9;
            background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, transparent 100%);
            border: 1px solid var(--border);
            border-radius: 20px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .mockup-frame::after {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            background: radial-gradient(circle at center, var(--glow) 0%, transparent 60%);
            opacity: 0.1;
            animation: slowRotate 20s infinite linear;
        }

        @keyframes slowRotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        .stat-grid {
            margin-top: 40px;
            display: flex;
            justify-content: center;
            gap: 40px;
        }

        .stat-box { text-align: left; }
        .stat-val { font-family: 'JetBrains Mono', monospace; font-size: 1.5rem; font-weight: 700; color: #fff; }
        .stat-txt { font-size: 0.75rem; color: #555; text-transform: uppercase; letter-spacing: 1px; margin-top: 4px; }

        /* --- Ambient Effects --- */
        .ambient-glow {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 120vw;
            height: 120vh;
            background: radial-gradient(circle at center, var(--glow) 0%, transparent 80%);
            opacity: 0.05;
            z-index: -2;
            pointer-events: none;
        }

        .bottom-glow {
            position: fixed;
            bottom: -300px;
            left: 50%;
            transform: translateX(-50%);
            width: 1400px;
            height: 600px;
            background: radial-gradient(circle at center, var(--glow) 0%, transparent 70%);
            opacity: 0.3;
            filter: blur(100px);
            z-index: -1;
            pointer-events: none;
        }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }
    </style>
</head>
<body>

    <div class="ambient-glow"></div>
    <div class="bottom-glow"></div>

    <nav>
        <div class="logo-box">
            <img src="../images/slapsh-logo-tt.png" alt="Logo">
            TikTok <span class="badge">Pro Studio</span>
        </div>
        <div class="nav-links">
            <a href="#hero" style="color: white; text-decoration: none;">Download</a>
            <a href="#features" style="color: inherit; text-decoration: none;">Optimization</a>
        </div>
        <button class="nav-btn">Log in</button>
    </nav>

    <!-- HERO SECTION -->
    <section id="hero">
        <h1 class="hero-title">Build the ultimate<br>TikTok experience</h1>
        
        <a href="index.php?access=win_x64_exe" class="dl-btn">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="white"><path d="M11.5 12.5V19h-5v-6.5h5zm0-8.5V11.5h-5V4h5zm1-8.5V11.5h5V4h-5zm0 8.5V19h5v-6.5h-5zm-5.5-2H2v-4.5h5V5zm0 10.5V14h-5v4.5h5zm1-10.5h5v4.5h-10.5v-4.5h5.5z" transform="scale(0.8) translate(2,2)"/></svg>
            Download for Windows
        </a>

        <div class="os-info">Only supports 64-bit Windows 10 and newer</div>

        <div style="margin-top: 60px; color: rgba(255,255,255,0.4); font-size: 0.9rem; font-family: 'JetBrains Mono';">
            SCROLL TO DISCOVER OPTIMIZATIONS ↓
        </div>
    </section>

    <!-- FEATURE SECTION 1: STEALTH -->
    <section id="features">
        <div class="feature-container reveal-target">
            <div class="feature-inner">
                <span class="feature-label">Security & Identity</span>
                <h2 class="feature-title">Pure Anonymous Core.</h2>
                <p class="feature-desc">Advanced HTTP header manipulation and atob hardening. Your desktop identity remains completely invisible to analytics engines.</p>
                
                <div class="mockup-frame">
                    <div style="font-family: 'JetBrains Mono', monospace; font-size: 0.9rem; color: var(--secondary);">
                        [IDENTITY_SPOOFED_SUCCESSFULLY]<br>
                        [USER_AGENT: TIKTOK_MOBILE_IOS_16]<br>
                        [TRACKING_SCRIPTS_NULLIFIED]
                    </div>
                </div>

                <div class="stat-grid">
                    <div class="stat-box">
                        <div class="stat-val">100%</div>
                        <div class="stat-txt">STEALTH_RATING</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-val">0ms</div>
                        <div class="stat-txt">INJECTION_LAG</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURE SECTION 2: ARCHIVE -->
    <section>
        <div class="feature-container reveal-target">
            <div class="feature-inner">
                <span class="feature-label">Media Archiving</span>
                <h2 class="feature-title">High-Fidelity Archiver.</h2>
                <p class="feature-desc">Archive every video in high-bitrate original formats. One-click synchronization with your local digital vault.</p>
                
                <div class="mockup-frame" style="background: linear-gradient(135deg, rgba(254,44,85,0.05) 0%, transparent 100%);">
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 20px;">
                        <div style="width: 200px; height: 10px; background: rgba(255,255,255,0.1); border-radius: 5px; overflow: hidden;">
                            <div style="width: 75%; height: 100%; background: var(--primary); animation: progressLoop 3s infinite;"></div>
                        </div>
                        <span style="font-family: 'JetBrains Mono'; font-size: 0.8rem; color: #999;">ARCHIVING VIDEO_ID: 73841923...</span>
                    </div>
                </div>

                <div class="stat-grid">
                    <div class="stat-box">
                        <div class="stat-val">∞</div>
                        <div class="stat-txt">DOWNLOAD_LIMIT</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-val">RAW</div>
                        <div class="stat-txt">OUTPUT_FORMAT</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FINAL CTA -->
    <section>
        <div style="max-width: 800px;">
            <h2 style="font-size: 5rem; font-weight: 900; letter-spacing: -3px; margin-bottom: 40px;">Ready for the<br>ultimate build?</h2>
            <div style="display: flex; gap: 20px; justify-content: center;">
                <a href="index.php?access=win_x64_msi" class="nav-btn" style="padding: 16px 40px; font-size: 1.1rem; border-color: white;">Get MSI Installer</a>
                <a href="index.php?access=mac_universal_dmg" class="nav-btn" style="padding: 16px 40px; font-size: 1.1rem; border-color: var(--secondary); color: var(--secondary);">MacOS DMG</a>
            </div>
            <p style="margin-top: 40px; opacity: 0.3; font-family: 'JetBrains Mono'; font-size: 0.8rem;">TikTok Pro v1.0.0 Stable Build</p>
        </div>
    </section>

    <script>
        // --- Intersection Observer for Scroll Animations ---
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.2
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('reveal');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal-target').forEach(el => {
            observer.observe(el);
        });

        // --- Progress bar loop simulation ---
        window.onload = () => {
            console.log('TikTok Pro Portfolio Loaded.');
        };
    </script>

    <?php if(isset($_GET['status'])): ?>
    <script>
        // Simple notification stub
    </script>
    <?php endif; ?>

</body>
</html>
