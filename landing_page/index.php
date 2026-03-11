<?php
// --- SEO & CONFIGURATION ---
$app_name = "TikTok Pro";
$app_version = "v1.0.0";
$author = "CRTYPUBG";
$description = "Download TikTok Pro for Desktop. Professional mobile emulation, ad-free experience, and high-bitrate video archiving. Optimized for Windows and macOS.";

// Stealth Download Handler
if (isset($_GET['access'])) {
    $token = $_GET['access'];
    $file = '';
    
    // Updated file paths to match the release bundle naming convention
    if ($token === 'win_x64_exe') {
        $file = '../src-tauri/target/release/bundle/nsis/TikTok Pro_1.0.0_x64-setup.exe';
        $filename = 'TikTok_Pro_Setup.exe';
    } else if ($token === 'win_x64_msi') {
        $file = '../src-tauri/target/release/bundle/msi/TikTok Pro_1.0.0_x64_en-US.msi';
        $filename = 'TikTok_Pro_Setup.msi';
    } else if ($token === 'mac_universal_dmg') {
        $file = '../src-tauri/target/release/bundle/dmg/TikTok Pro_1.0.0_universal.dmg';
        $filename = 'TikTok_Pro.dmg';
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
    <title><?php echo $app_name; ?> | High-Fidelity Desktop Experience</title>
    <link href="https://fonts.googleapis.com/css2?family=TikTok+Font:wght@400;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --tt-black: #121212;
            --tt-white: #ffffff;
            --tt-red: #fe2c55;
            --tt-cyan: #25f4ee;
            --tt-border: rgba(255, 255, 255, 0.12);
            --tt-bg-hover: rgba(255, 255, 255, 0.04);
            --tt-text-sub: rgba(255, 255, 255, 0.75);
            --tt-glass: rgba(255, 255, 255, 0.06);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--tt-black);
            color: var(--tt-white);
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* --- TikTok Layout Components --- */
        header {
            height: 60px;
            border-bottom: 1px solid var(--tt-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            background: var(--tt-black);
            position: fixed;
            top: 0; width: 100%; z-index: 1000;
        }

        .l-box { display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 1.5rem; letter-spacing: -1px; }
        .l-box img { width: 32px; filter: drop-shadow(0 0 5px rgba(254, 44, 85, 0.5)); }

        .s-box {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 92px;
            width: 360px;
            height: 40px;
            display: flex;
            align-items: center;
            padding: 0 16px;
            color: rgba(255,255,255,0.3);
            font-size: 0.9rem;
            cursor: text;
        }

        .h-actions { display: flex; gap: 16px; align-items: center; }
        .h-btn { background: var(--tt-red); color: white; border: none; padding: 8px 16px; border-radius: 4px; font-weight: 600; cursor: pointer; }

        aside {
            width: 240px;
            height: calc(100vh - 60px);
            position: fixed;
            top: 60px; left: 0;
            padding: 20px 8px;
            border-right: 1px solid var(--tt-border);
        }

        .s-item {
            padding: 10px 12px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            color: rgba(255,255,255,0.9);
        }
        .s-item:hover { background: var(--tt-bg-hover); }
        .s-item.active { color: var(--tt-red); }
        .s-item svg { width: 24px; height: 24px; }

        /* --- Main Content: Download Hub --- */
        main {
            margin-left: 240px;
            margin-top: 60px;
            height: calc(100vh - 60px);
            overflow-y: auto;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .dl-container {
            width: 100%;
            max-width: 800px;
            background: var(--tt-glass);
            border: 1px solid var(--tt-border);
            border-radius: 12px;
            padding: 60px 40px;
            text-align: center;
            backdrop-filter: blur(20px);
            position: relative;
            overflow: hidden;
            animation: slideUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        .dl-container::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle at center, rgba(254, 44, 85, 0.05) 0%, transparent 70%);
            z-index: -1;
        }

        .app-icon-large {
            width: 120px;
            height: 120px;
            background: #000;
            border-radius: 28px;
            margin: 0 auto 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 40px rgba(0,0,0,0.5);
            border: 1px solid var(--tt-border);
        }
        .app-icon-large img { width: 80px; }

        .dl-container h1 { font-size: 3rem; font-weight: 800; margin-bottom: 12px; letter-spacing: -2px; }
        .dl-container p { color: var(--tt-text-sub); font-size: 1.2rem; margin-bottom: 48px; max-width: 500px; margin-left: auto; margin-right: auto; }

        .dl-grid {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .dl-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--tt-border);
            padding: 32px;
            border-radius: 12px;
            width: 320px;
            transition: all 0.3s;
            text-decoration: none;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        .dl-card:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-5px);
            border-color: var(--tt-cyan);
        }

        .dl-card h3 { font-size: 1.4rem; font-weight: 700; }
        .dl-btn {
            background: var(--tt-white);
            color: var(--tt-black);
            padding: 12px 24px;
            border-radius: 4px;
            font-weight: 700;
            width: 100%;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 1px;
            transition: 0.2s;
        }
        .dl-card:hover .dl-btn { background: var(--tt-cyan); color: #000; }

        .v-tag { font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: var(--tt-cyan); opacity: 0.6; margin-top: 40px; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--tt-border); border-radius: 10px; }
    </style>
</head>
<body>

<header>
    <div class="l-box">
        <img src="../images/slapsh-logo-tt.png" alt="Logo">
        <span>TikTok Pro</span>
    </div>
    <div class="s-box">Search for the future of TikTok...</div>
    <div class="h-actions">
        <div style="font-weight: 600; cursor: pointer;">Upload</div>
        <button class="h-btn">Log in</button>
    </div>
</header>

<aside>
    <div class="s-item active">
        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4 10v12h5v-6h6v6h5V10L12 2z"/></svg>
        For You
    </div>
    <div class="s-item">
        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4zm0-2a4 4 0 100-8 4 4 0 000 8z"/></svg>
        Following
    </div>
    <div class="s-item">
        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M11 11.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5zM22 12a10 10 0 11-20 0 10 10 0 0120 0zm-2 0a8 8 0 10-16 0 8 8 0 0016 0z"/></svg>
        Explore
    </div>
    <div class="s-item">
        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M4 11h5V5H4v6zm0 7h5v-6H4v6zm7 0h5v-6h-5v6zm0-13v6h5V5h-5z"/></svg>
        LIVE
    </div>
</aside>

<main>
    <div class="dl-container">
        <div class="app-icon-large">
            <img src="../images/tt-icon-app.png" alt="App Icon">
        </div>
        <h1>Next-Gen TikTok.</h1>
        <p>The ultimate desktop optimization. Zero tracking, high-fidelity mobile emulation, and advanced local archiving.</p>
        
        <div class="dl-grid">
            <!-- Windows Node -->
            <a href="index.php?access=win_x64_exe" class="dl-card">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.18 11.68 17 3H3v18h14l.18-8.68"></path><path d="M17 3v18"></path><path d="M12 17v-4"></path><path d="M12 10V6"></path><path d="M17 12h5"></path></svg>
                <h3>Windows OS</h3>
                <div style="font-size: 0.8rem; color: var(--tt-text-sub);">Latest x64 Stable Build</div>
                <div class="dl-btn">Download Setup</div>
            </a>

            <!-- macOS Node -->
            <a href="index.php?access=mac_universal_dmg" class="dl-card">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20.94c1.88-1.55 3-4.04 3-6.94 0-3.87-2.01-7-4.5-7S6 10.13 6 14c0 2.9 1.12 5.39 3 6.94"></path><path d="M18 20c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2H6C4.9 4 4 4.9 4 6v12c0 1.1.9 2 2 2h12z"></path></svg>
                <h3>macOS Darwin</h3>
                <div style="font-size: 0.8rem; color: var(--tt-text-sub);">Universal M1/M2/Intel</div>
                <div class="dl-btn">Mount DMG Image</div>
            </a>
        </div>

        <div class="v-tag">[TikTok_Pro_v1.0.0_STABLE] [ENCRYPTED_DISTRIBUTION]</div>
    </div>

    <div style="margin-top: 40px; color: rgba(255,255,255,0.2); font-size: 0.75rem; text-align: center;">
        © 2026 CRTY_LABS. Optimized for high-performance desktop environments.
    </div>
</main>

<script>
    // Subtle interactivity
    document.addEventListener('mousemove', (e) => {
        const x = (e.clientX / window.innerWidth - 0.5) * 10;
        const y = (e.clientY / window.innerHeight - 0.5) * 10;
        document.querySelector('.dl-container').style.transform = `translate(${x}px, ${y}px)`;
    });
</script>

</body>
</html>
