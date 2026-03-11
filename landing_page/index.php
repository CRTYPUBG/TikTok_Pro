<?php
// --- CONFIGURATION ---
$app_name = "TikTok Pro";
$app_version = "v1.0.0";
$author = "CRTY_LABS";
$description = "The ultimate power-user suite for TikTok. Stealth mode, HD archiving, and real-time performance tracking.";

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
    <title><?php echo $app_name; ?> Studio | Elite Desktop Experience</title>
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="author" content="<?php echo $author; ?>">
    
    <!-- Performance Optimization -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://unpkg.com">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://tiktok.crty-dev.com/">
    <meta property="og:title" content="<?php echo $app_name; ?> Studio | Elite Desktop Experience">
    <meta property="og:description" content="<?php echo $description; ?>">
    <meta property="og:image" content="https://tiktok.crty-dev.com/images/og-preview.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://tiktok.crty-dev.com/">
    <meta property="twitter:title" content="<?php echo $app_name; ?> Studio | Elite Desktop Experience">
    <meta property="twitter:description" content="<?php echo $description; ?>">
    <meta property="twitter:image" content="https://tiktok.crty-dev.com/images/og-preview.png">

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SoftwareApplication",
      "name": "<?php echo $app_name; ?>",
      "operatingSystem": "Windows 10, Windows 11, macOS 11.0+",
      "applicationCategory": "MultimediaApplication",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "USD"
      },
      "author": {
        "@type": "Organization",
        "name": "<?php echo $author; ?>"
      },
      "description": "<?php echo $description; ?>",
      "softwareVersion": "<?php echo $app_version; ?>"
    }
    </script>

    <style>
        :root {
            --primary: #ff0050; --secondary: #00f2ea; --bg-dark: #030303;
            --text-main: #ffffff; --text-dim: #a0a0a0;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { background: var(--bg-dark); color: var(--text-main); font-family: 'Inter', sans-serif; line-height: 1.6; overflow-x: hidden; }
        .cyber-grid { position: fixed; inset: 0; background-image: linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px); background-size: 50px 50px; pointer-events: none; z-index: 0; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; position: relative; z-index: 10; }
        header { height: 80px; display: flex; align-items: center; position: fixed; top: 0; width: 100%; z-index: 100; backdrop-filter: blur(10px); }
        .hero { min-height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding-top: 80px; }
        h1 { font-family: 'Outfit', sans-serif; font-size: clamp(3rem, 8vw, 6rem); font-weight: 900; line-height: 1; margin-bottom: 1.5rem; background: linear-gradient(135deg, #fff 0%, #a0a0a0 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-desc { font-size: 1.25rem; color: var(--text-dim); margin-bottom: 3rem; max-width: 500px; margin-inline: auto; }
        .badge { display: inline-block; padding: 0.5rem 1rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 100px; font-size: 0.75rem; font-weight: 600; color: var(--primary); margin-bottom: 2rem; }
        @media (max-width: 768px) { .hero { text-align: left; align-items: flex-start; } h1 { font-size: 3.5rem; } }
    </style>
    <link rel="stylesheet" href="assets/css/styles.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="assets/css/styles.css"></noscript>
    <!-- Lucide Icons (Non-blocking) -->
    <script src="https://unpkg.com/lucide@latest" defer></script>
</head>
<body>
    <!-- Background Layer -->
    <div class="cyber-grid"></div>
    <div class="orb orb-primary"></div>
    <div class="orb orb-secondary"></div>

    <header>
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <div class="brand" style="display: flex; align-items: center; gap: 12px; font-weight: 900; font-size: 1.5rem; letter-spacing: -1px;">
                <img src="images/slapsh-logo-tt.png" alt="<?php echo $app_name; ?> Logo" width="64" height="64" fetchpriority="high">
                <?php echo strtoupper($app_name); ?>
            </div>
            <nav style="display: flex; gap: 2rem;">
                <a href="#features" style="color: var(--text-dim); text-decoration: none; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Features</a>
                <a href="#download" style="color: var(--text-dim); text-decoration: none; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Download</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <span class="badge fade-up"><?php echo $app_version; ?> STABLE RELEASE</span>
                    <h1 class="fade-up">RETYPE THE<br>FLOW.</h1>
                    <p class="hero-desc fade-up"><?php echo $description; ?></p>
                    
                    <div class="btn-group fade-up" id="download">
                        <a href="?access=win_x64_exe" class="btn btn-primary" aria-label="Download TikTok Pro for Windows EXE">
                            <i data-lucide="download" aria-hidden="true"></i>
                            WINDOWS RELEASE
                        </a>
                        <a href="?access=mac_universal_dmg" class="btn btn-glass" aria-label="Download TikTok Pro for macOS DMG">
                            <i data-lucide="apple" aria-hidden="true"></i>
                            MACOS BUILD
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="features" id="features">
            <div class="container">
                <div class="grid">
                    <div class="feature-card fade-up">
                        <div class="icon-box">
                            <i data-lucide="shield"></i>
                        </div>
                        <h2>Stealth Mode</h2>
                        <p>Ghost-protocol browsing. Bypass region locks and algorithmic tracking with an integrated proxy layer.</p>
                    </div>

                    <div class="feature-card fade-up">
                        <div class="icon-box">
                            <i data-lucide="database"></i>
                        </div>
                        <h2>Media Vault</h2>
                        <p>Zero-loss binary capture. Archive high-fidelity videos directly to your local filesystem with metadata preservation.</p>
                    </div>

                    <div class="feature-card fade-up">
                        <div class="icon-box">
                            <i data-lucide="terminal"></i>
                        </div>
                        <h2>Dev HUD</h2>
                        <p>Experimental system diagnostics. Real-time resource monitoring and network telemetry for the curious mind.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p class="footer-text">DESIGNED BY <?php echo $author; ?> // SYSTEM_ID: <?php echo str_replace(' ', '_', strtoupper($app_name)); ?>_CORE</p>
        </div>
    </footer>

    <script src="assets/js/scripts.js"></script>
    <script>
        // Init Lucide after DOM and deferred scripts
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
