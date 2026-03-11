# 🚀 TikTok Pro - Ultimate Desktop Optimization

The most advanced TikTok desktop experience. Engineered for performance, privacy, and power users.

![Banner](https://raw.githubusercontent.com/CRTYPUBG/TikTok_Pro/main/images/slapsh-logo-tt.png)

## ✨ Phase 5: Ultimate Features
- **🌌 Cyber Dynamics UI**: High-performance "Cyber Grid" and floating pulsing orbs background for a futuristic, premium aesthetic.
- **📼 Built-in Offline Player**: Play your downloaded videos instantly inside the application with zero external dependencies.
- **📊 Developer Insight HUD**: Real-time System Metrics (CPU/RAM usage) and backend log monitoring accessible via `--dev-crty-mode`.
- **🏗️ Multi-Platform Distribution**: Automated CI/CD pipeline generating **EXE, MSI, DMG, and PKG** via GitHub Actions.

## 🛠️ Core Technology Stack
- **Frontend**: React + Tailwind CSS v4 + TypeScript (Vite)
- **Backend**: Rust (Tauri v2)
- **Design**: Lucid Icons + Custom SVG Dynamics + Glassmorphism
- **System Diagnostics**: `sysinfo` (Rust) for real-time hardware monitoring.

## 📦 Build & Installation

### Local Development
```bash
# Install dependencies
npm install

# Start in Dev Mode with System HUD
npm run tauri dev -- --dev-crty-mode
```

### Manual Production Build
```bash
# Build frontend
npm run build

# Build Tauri Bundles
npm run tauri build
```

## 🚢 CI/CD Workflow
The project includes a pre-configured GitHub Actions workflow located in `.github/workflows/build.yml`. 
- **Trigger**: Any push to the `main` branch.
- **Output**: Multi-platform binaries (Windows/macOS) available in the GitHub Actions artifacts and releases.

---
*Designed & Engineered by **CRTYPUBG** // Final Boss Optimization*
