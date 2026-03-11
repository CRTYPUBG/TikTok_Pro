<?php
// --- SEO & CONFIGURATION ---
$app_name = "TikTok Pro";
$app_version = "v1.0.0_STABLE";
$author = "CRTYPUBG";
$site_url = "https://tiktok.crty-dev.com";
$description = "Get the ultimate TikTok desktop experience. Ad-free, high-performance mobile emulation, and instant video download. Built for power users.";

// Stealth Download Handler (Same as before)
if (isset($_GET['access'])) {
    $token = $_GET['access'];
    $file = '';
    if ($token === 'win_x64_exe') { $file = '../download/TikTok_Pro_v1.0.0_x64-setup.exe'; $filename = 'TikTok_Pro_v1.0.0.exe'; }
    else if ($token === 'win_x64_msi') { $file = '../download/TikTok_Pro_v1.0.0_x64_en-US.msi'; $filename = 'TikTok_Pro_v1.0.0.msi'; }
    else if ($token === 'mac_universal_dmg') { $file = '../download/TikTok_Pro_v1.0.0.dmg'; $filename = 'TikTok_Pro_v1.0.0.dmg'; }

    if ($file && file_exists($file)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        readfile($file); exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download <?php echo $app_name; ?> | Tik Tok Desktop Optimized</title>
    <link href="https://fonts.googleapis.com/css2?family=TikTok+Font:wght@400;700&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --tt-red: #fe2c55;
            --tt-cyan: #25f4ee;
            --tt-black: #121212;
            --tt-grey: #161823;
            --tt-border: rgba(255, 255, 255, 0.12);
            --tt-text-main: #ffffff;
            --tt-text-sub: rgba(255, 255, 255, 0.75);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--tt-black);
            color: var(--tt-text-main);
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }

        /* --- Header: TikTok Native Style --- */
        nav {
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

        .logo-box {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .logo-box img { width: 32px; }

        .search-stub {
            background: rgba(255,255,255,0.1);
            border-radius: 92px;
            width: 360px;
            height: 40px;
            display: flex;
            align-items: center;
            padding: 0 16px;
            color: #8a8a8e;
            font-size: 0.9rem;
        }

        .nav-actions { display: flex; gap: 16px; align-items: center; }

        /* --- Sidebar: TikTok Native Style --- */
        aside {
            width: 240px;
            height: calc(100vh - 60px);
            position: fixed;
            top: 60px;
            left: 0;
            padding: 20px 8px;
            border-right: 1px solid var(--tt-border);
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .side-item {
            padding: 12px 8px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .side-item:hover { background: rgba(255,255,255,0.04); }
        .side-item.active { color: var(--tt-red); }

        .side-item svg { width: 24px; height: 24px; }

        /* --- Main Content --- */
        main {
            margin-left: 240px;
            margin-top: 60px;
            height: calc(100vh - 60px);
            overflow-y: scroll;
            scroll-snap-type: y mandatory;
            padding: 20px;
        }

        /* --- Video Post Simulation --- */
        .video-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto 24px;
            scroll-snap-align: start;
            display: flex;
            gap: 12px;
            padding-bottom: 24px;
            border-bottom: 0.5px solid var(--tt-border);
        }

        .avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--tt-red), var(--tt-cyan));
            flex-shrink: 0;
        }

        .content-box { flex-grow: 1; }

        .user-info { margin-bottom: 12px; }
        .user-info .nick { font-weight: 700; margin-right: 8px; }
        .user-info .handle { color: var(--tt-text-sub); font-size: 0.9rem; }

        .desc { margin-bottom: 16px; font-size: 1.1rem; }

        .player-mock {
            width: 100%;
            aspect-ratio: 9/16;
            background: #000;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 40px rgba(0,0,0,0.5);
        }

        .player-mock video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.4;
        }

        .download-overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(10px);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .node-tile {
            background: #fff;
            color: #000;
            padding: 16px 24px;
            border-radius: 4px;
            font-weight: 700;
            text-decoration: none;
            margin-bottom: 12px;
            width: 240px;
            text-align: center;
            transition: transform 0.2s, background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .node-tile:hover {
            transform: scale(1.05);
            background: var(--tt-cyan);
        }
        .node-tile.secondary {
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
        }

        .node-tile.secondary:hover { background: rgba(255,255,255,0.1); }

        .os-tag {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--tt-text-sub);
            margin-bottom: 8px;
        }

        /* --- Social Buttons stub --- */
        .social-bar {
            display: flex;
            flex-direction: column;
            gap: 20px;
            justify-content: flex-end;
            padding-bottom: 20px;
        }

        .social-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        ::-webkit-scrollbar { width: 0; }

    </style>
</head>
<body>

<nav>
    <div class="logo-box">
        <img src="../images/slapsh-logo-tt.png" alt="Tik Tok">
        <span>TikTok Pro</span>
    </div>
    <div class="search-stub">Search for "Optimization"...</div>
    <div class="nav-actions">
        <button style="background:var(--tt-red); border:none; color:#fff; padding:8px 16px; border-radius:4px; font-weight:600;">Get App</button>
        <div style="width:32px; height:32px; background:#444; border-radius:50%;"></div>
    </div>
</nav>

<aside>
    <div class="side-item active">
        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4 10v12h5v-6h6v6h5V10L12 2z"/></svg>
        For You
    </div>
    <div class="side-item">
        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4zm0-2a4 4 0 100-8 4 4 0 000 8z"/></svg>
        Following
    </div>
    <div class="side-item">
        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M11 11.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5zM22 12a10 10 0 11-20 0 10 10 0 0120 0zm-2 0a8 8 0 10-16 0 8 8 0 0016 0z"/></svg>
        Explore
    </div>
</aside>

<main>
    <!-- Entry Post -->
    <div class="video-container">
        <div class="avatar"></div>
        <div class="content-box">
            <div class="user-info">
                <span class="nick">TikTok Pro Official</span>
                <span class="handle">@tiktok_pro_build</span>
            </div>
            <p class="desc">Experience TikTok like never before. Mobile performance on Desktop. Ad-Free. Archive everything. #TikTokPro #DesktopOptimization</p>
            <div class="player-mock">
                <div class="download-overlay">
                    <span class="os-tag">Secure Build Distributed</span>
                    <a href="index.php?access=win_x64_exe" class="node-tile">
                        DOWNLOAD EXE (WIN)
                    </a>
                    <a href="index.php?access=win_x64_msi" class="node-tile secondary">
                        INIT MSI INSTALLER
                    </a>
                    <div style="margin-top: 24px; color: var(--tt-cyan); font-weight:700; font-family:'JetBrains Mono', monospace; font-size:0.8rem;">
                        [SYSTEM_STABLE_V1.0.0]
                    </div>
                </div>
            </div>
        </div>
        <div class="social-bar">
            <div class="social-btn">❤️</div>
            <div class="social-btn">💬</div>
            <div class="social-btn">🔖</div>
            <div class="social-btn">🔗</div>
        </div>
    </div>

    <!-- Secondary Node (Mac) -->
    <div class="video-container">
        <div class="avatar" style="background: #fff"></div>
        <div class="content-box">
            <div class="user-info">
                <span class="nick">Apple Silicon Build</span>
                <span class="handle">@darwin_core</span>
            </div>
            <p class="desc">Universal binary for macOS. Native M1/M2/M3 support. Ultra-low latency video engine. #MacPro #TikTokDesktop</p>
            <div class="player-mock" style="background: #111;">
                <div class="download-overlay">
                    <span class="os-tag">Darwin Core Image</span>
                    <a href="index.php?access=mac_universal_dmg" class="node-tile">
                        MOUNT DMG (MACOS)
                    </a>
                </div>
            </div>
        </div>
        <div class="social-bar">
            <div class="social-btn">❤️</div>
            <div class="social-btn">💬</div>
            <div class="social-btn">🔖</div>
            <div class="social-btn">🔗</div>
        </div>
    </div>
</main>

<script>
    // Smooth pulse for the active item
    const activeItem = document.querySelector('.side-item.active');
    setInterval(() => {
        activeItem.style.opacity = activeItem.style.opacity === '0.7' ? '1' : '0.7';
    }, 1500);

    // Fade in effect on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.video-container').forEach(v => {
        v.style.opacity = '0';
        v.style.transform = 'translateY(40px)';
        v.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
        observer.observe(v);
    });
</script>

</body>
</html>
