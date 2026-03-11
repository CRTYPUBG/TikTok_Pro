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
    <title><?php echo $app_name; ?> | Striking Minimalist Experience</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
    
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
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--bg);
            color: var(--fg);
            font-family: 'Outfit', 'TikTokFont', sans-serif;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* --- Header --- */
        nav {
            padding: 24px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0; width: 100%;
            z-index: 100;
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

        /* --- Hero --- */
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            padding: 0 24px;
        }

        .hero-title {
            font-size: clamp(3rem, 10vw, 7rem);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -3px;
            max-width: 1000px;
            margin-bottom: 40px;
            animation: fadeInDown 1s cubic-bezier(0.2, 1, 0.2, 1);
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-40px); }
            to { opacity: 1; transform: translateY(0); }
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

        .alt-os {
            margin-top: 40px;
            font-size: 1.1rem;
            font-weight: 600;
            color: rgba(255,255,255,0.8);
        }
        .alt-os a { color: white; text-decoration: none; border-bottom: 2px solid var(--secondary); margin-left: 8px; }

        /* --- Glow Effect --- */
        .bottom-glow {
            position: fixed;
            bottom: -300px;
            left: 50%;
            transform: translateX(-50%);
            width: 1400px;
            height: 600px;
            background: radial-gradient(circle at center, var(--glow) 0%, transparent 70%);
            opacity: 0.4;
            filter: blur(100px);
            z-index: -1;
            animation: glowPulse 8s infinite alternate;
        }

        @keyframes glowPulse {
            from { opacity: 0.3; transform: translateX(-50%) scale(1); }
            to { opacity: 0.5; transform: translateX(-50%) scale(1.1); }
        }

        /* --- Swiper inspired transitions --- */
        .fade-in {
            opacity: 0;
            animation: fadeIn 1.2s forwards;
            animation-delay: 0.5s;
        }

        @keyframes fadeIn {
            to { opacity: 1; }
        }

        /* --- Optimization for simple look --- */
        ::-webkit-scrollbar { width: 0; }
    </style>
</head>
<body>

    <nav>
        <div class="logo-box">
            <img src="../images/slapsh-logo-tt.png" alt="Logo">
            TikTok <span class="badge">Pro Studio</span>
        </div>
        <div class="nav-links">
            <a href="#" style="color: inherit; text-decoration: none;">Help Center</a>
            <a href="#" style="color: white; text-decoration: none;">Features</a>
        </div>
        <button class="nav-btn">Log in</button>
    </nav>

    <main>
        <h1 class="hero-title">Build the ultimate<br>TikTok experience</h1>
        
        <a href="index.php?access=win_x64_exe" class="dl-btn">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="white"><path d="M11.5 12.5V19h-5v-6.5h5zm0-8.5V11.5h-5V4h5zm1-8.5V11.5h5V4h-5zm0 8.5V19h5v-6.5h-5zm-5.5-2H2v-4.5h5V5zm0 10.5V14h-5v4.5h5zm1-10.5h5v4.5h-10.5v-4.5h5.5z" transform="scale(0.8) translate(2,2)"/></svg>
            Download for Windows
        </a>

        <div class="os-info fade-in">Only supports 64-bit Windows 10 and newer</div>

        <div class="alt-os fade-in">
            Also available for <a href="index.php?access=mac_universal_dmg" style="border-bottom-color: white;">macOS (Apple Silicon/Intel)</a>
        </div>
    </main>

    <div class="bottom-glow"></div>

    <script>
        // Smooth entrance
        document.addEventListener('DOMContentLoaded', () => {
            document.body.style.opacity = '1';
        });
    </script>
</body>
</html>
