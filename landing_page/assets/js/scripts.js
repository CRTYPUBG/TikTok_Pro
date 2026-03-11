document.addEventListener('DOMContentLoaded', () => {
    // Scroll Reveal Animation
    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-up').forEach(el => {
        observer.observe(el);
    });

    // Download Tracking Logic (Simulation)
    const downloadBtns = document.querySelectorAll('.btn');
    downloadBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const platform = btn.innerText.includes('WINDOWS') ? 'Windows' : 'macOS';
            console.log(`[TRACKING] Download started for: ${platform}`);
            
            // In a real scenario, we would send this to an API
            /*
            fetch('api/track_download.php', {
                method: 'POST',
                body: JSON.stringify({ platform: platform, timestamp: new Date().toISOString() })
            });
            */
            
            // Visual feedback
            const originalText = btn.innerHTML;
            btn.style.opacity = '0.5';
            btn.innerHTML = 'PREPARING...';
            
            setTimeout(() => {
                btn.style.opacity = '1';
                btn.innerHTML = originalText;
                lucide.createIcons(); // Re-init icons if needed
            }, 1000);
        });
    });

    // Smooth Scroll for Nav Links
    document.querySelectorAll('nav a').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Parallax Orb Effect
    document.addEventListener('mousemove', (e) => {
        const orbs = document.querySelectorAll('.orb');
        const x = e.clientX / window.innerWidth;
        const y = e.clientY / window.innerHeight;
        
        orbs.forEach((orb, index) => {
            const factor = (index + 1) * 20;
            orb.style.transform = `translate(${x * factor}px, ${y * factor}px)`;
        });
    });
});
