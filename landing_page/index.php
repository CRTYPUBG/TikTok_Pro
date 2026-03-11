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
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Primary Meta Tags -->
    <title><?php echo $app_name; ?> | Official Desktop Optimization by <?php echo $author; ?></title>
    <meta name="title" content="<?php echo $app_name; ?> | Premium TikTok Desktop Client">
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="keywords" content="<?php echo $keywords; ?>">
    <meta name="author" content="<?php echo $author; ?>">
    <link rel="canonical" href="<?php echo $site_url; ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $site_url; ?>">
    <meta property="og:title" content="<?php echo $app_name; ?> - Desktop Optimization">
    <meta property="og:description" content="<?php echo $description; ?>">
    <meta property="og:image" content="<?php echo $site_url; ?>/images/og-preview.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo $site_url; ?>">
    <meta property="twitter:title" content="<?php echo $app_name; ?> - Premium Desktop Experience">
    <meta property="twitter:description" content="<?php echo $description; ?>">
    <meta property="twitter:image" content="<?php echo $site_url; ?>/images/og-preview.png">

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SoftwareApplication",
      "name": "<?php echo $app_name; ?>",
      "operatingSystem": "Windows, macOS",
      "applicationCategory": "SocialNetworkingApplication",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "USD"
      },
      "author": {
        "@type": "Person",
        "name": "<?php echo $author; ?>"
      },
      "description": "<?php echo $description; ?>",
      "softwareVersion": "<?php echo $app_version; ?>"
    }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ff0050;
            --secondary: #00f2ea;
            --bg: #030303;
            --text: #ffffff;
            --glass: rgba(255, 255, 255, 0.02);
            --border: rgba(255, 255, 255, 0.08);
            --hacker-green: #00ff41;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--bg);
            color: var(--text);
            overflow-x: hidden;
            font-family: 'Plus Jakarta Sans', sans-serif;
            line-height: 1.6;
        }

        .scanlines {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), 
                        linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06));
            background-size: 100% 2px, 3px 100%;
            z-index: 100;
            pointer-events: none;
            opacity: 0.3;
        }

        .background-svg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            opacity: 0.15;
            filter: blur(120px);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        header {
            padding: 2rem 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: -1px;
            text-transform: uppercase;
        }

        .logo img {
            width: 28px;
            height: 28px;
            filter: drop-shadow(0 0 5px var(--primary));
        }

        .hero {
            padding: 8rem 0 6rem;
            text-align: center;
        }

        .hero .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--glass);
            border: 1px solid var(--border);
            border-radius: 100px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            color: var(--hacker-green);
            margin-bottom: 2rem;
            text-transform: uppercase;
        }

        .hero .status-badge span {
            width: 6px; height: 6px; background: var(--hacker-green); border-radius: 50%;
            box-shadow: 0 0 10px var(--hacker-green);
            animation: pulse 1.5s infinite;
        }

        h1 {
            font-size: 4.5rem;
            font-weight: 800;
            background: linear-gradient(to bottom, #fff, #444);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
            letter-spacing: -3px;
            line-height: 1.1;
        }

        .hero p {
            font-size: 1.1rem;
            color: #888;
            max-width: 600px;
            margin: 0 auto 4rem;
        }

        .download-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 1.1rem 2.8rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.9rem;
            border: 1px solid var(--border);
        }

        .btn-primary { background: #fff; color: #000; border: none; }
        .btn-primary:hover { transform: scale(1.05); box-shadow: 0 0 30px rgba(255, 255, 255, 0.2); }
        .btn-secondary { background: rgba(255, 255, 255, 0.03); color: #fff; backdrop-filter: blur(10px); }
        .btn-secondary:hover { background: rgba(255, 255, 255, 0.08); border-color: rgba(255, 255, 255, 0.3); }

        .features {
            padding: 6rem 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: var(--glass);
            border: 1px solid var(--border);
            padding: 3rem;
            border-radius: 24px;
            backdrop-filter: blur(40px);
            transition: all 0.3s;
            position: relative;
        }

        .feature-card:hover {
            border-color: rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.03);
            transform: translateY(-5px);
        }

        .feature-icon {
            margin-bottom: 2rem;
            width: 54px; height: 54px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 16px;
            color: var(--primary);
            border: 1px solid var(--border);
        }

        .feature-card h2 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            font-family: 'JetBrains Mono', monospace;
            text-transform: uppercase;
        }

        .feature-card p { color: #888; font-size: 0.95rem; }

        footer {
            padding: 4rem 0 3rem;
            text-align: center;
            border-top: 1px solid var(--border);
            color: #444;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
        @media (max-width: 768px) { h1 { font-size: 2.8rem; } .hero { padding: 4rem 0; } }
    </style>
</head>
<body>
    <div class="scanlines"></div>
    <svg class="background-svg" viewBox="0 0 1000 1000" preserveAspectRatio="none" aria-hidden="true">
        <circle cx="200" cy="200" r="300" fill="var(--primary)" />
        <circle cx="800" cy="800" r="350" fill="var(--secondary)" />
    </svg>

    <div class="container">
        <header role="banner">
            <div class="logo">
                <img src="../images/slapsh-logo-tt.png" alt="TikTok Pro Shield Logo">
                <span>Core_System.pro</span>
            </div>
            <p style="font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; color: #444;"><?php echo $app_version; ?></p>
        </header>

        <main>
            <section class="hero" aria-labelledby="main-title">
                <div class="status-badge" role="status">
                    <span></span> Access Granted: Encrypted Session
                </div>
                <h1 id="main-title">The Ultimate TikTok<br>Experience on PC.</h1>
                <p>Bypass desktop restrictions with high-fidelity mobile emulation. Built for performance, privacy, and seamless archiving.</p>
                
                <div class="download-group">
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <a href="index.php?access=win_x64_exe" class="btn btn-primary" title="Download Windows EXE">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                            Windows_EXE
                        </a>
                        <a href="index.php?access=win_x64_msi" class="btn btn-secondary" style="padding: 0.8rem 2rem; font-size: 0.8rem;" title="Download Windows MSI">
                            Init_MSI_Installer
                        </a>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <a href="index.php?access=mac_universal_dmg" class="btn btn-secondary" title="Download macOS DMG">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 19V5M5 12l7 7 7-7"/></svg>
                            macOS_DMG
                        </a>
                        <a href="index.php?access=mac_universal_pkg" class="btn btn-secondary" style="padding: 0.8rem 2rem; font-size: 0.8rem;" title="Download macOS PKG">
                            Init_PKG_Binary
                        </a>
                    </div>
                </div>
            </section>

            <section id="features" class="features" aria-label="Application Features">
                <article class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="Mobile Emulation Icon">
                            <path d="M9 12a3 3 0 1 0 6 0 3 3 0 0 0-6 0Z"></path>
                            <path d="M15 12V4L9 8v8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                        </svg>
                    </div>
                    <h2>Mobile_Emulation</h2>
                    <p>Deep-level User-Agent spoofing and viewport synchronization ensure a pixel-perfect mobile experience on desktop hardware.</p>
                </article>

                <article class="feature-card">
                    <div class="feature-icon" style="color: var(--secondary)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-label="Binary Download Icon">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                        </svg>
                    </div>
                    <h2>Binary_Extraction</h2>
                    <p>Save content directly to your local drive with high-bitrate binary capture. No watermarks, no metadata stripping.</p>
                </article>

                <article class="feature-card">
                    <div class="feature-icon" style="color: #fff">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-label="Secure Sessions Icon">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <h2>Parallel_System</h2>
                    <p>Manage multiple account states in isolated sandbox environments. Switch identities instantly without session interrupts.</p>
                </article>
            </section>
        </main>

        <footer role="contentinfo">
            <?php echo strtoupper($author); ?>_DEFENSE_PROTOTYPE // BUILD_ID: <?php echo date("Ymd"); ?> // NO_TRACKING_ACTIVE
        </footer>
    </div>
</body>
</html>
