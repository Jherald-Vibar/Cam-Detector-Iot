<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>THEODORE</title>
    <link rel="icon" href="{{ asset('theodore_logo.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-primary: #0a0a0a;
            --bg-secondary: #1a1a1a;
            --bg-card: #242424;
            --text-primary: #ffffff;
            --text-secondary: #b3b3b3;
            --accent: #e50914;
            --accent-hover: #f40612;
            --border: #333333;
        }

        [data-theme="light"] {
            --bg-primary: #ffffff;
            --bg-secondary: #f5f5f5;
            --bg-card: #ffffff;
            --text-primary: #000000;
            --text-secondary: #666666;
            --border: #e0e0e0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .page {
            display: none;
        }

        .page.active {
            display: block;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
        }

        .logo {
            font-size: 32px;
            font-weight: 700;
            color: var(--accent);
            cursor: pointer;
        }

        .header-actions {
            display: flex;
            gap: 15px;
        }

        .icon-btn {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-primary);
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .icon-btn:hover {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
        }

        .theme-toggle .sun-icon {
            display: none;
        }

        [data-theme="light"] .theme-toggle .sun-icon {
            display: block;
        }

        [data-theme="light"] .theme-toggle .moon-icon {
            display: none;
        }

        .page-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }

        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        .video-section {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #4ade80;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #4ade80;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .video-container {
            position: relative;
            width: 100%;
            aspect-ratio: 16/9;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .video-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .video-overlay {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(0, 0, 0, 0.7);
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 12px;
            color: white;
            z-index: 10;
        }

        .video-timestamp {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: var(--accent);
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            color: white;
            z-index: 10;
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .alerts-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
        }

        .alerts-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .alerts-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .alerts-date {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .alerts-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .alert-item {
            display: flex;
            gap: 12px;
            padding: 12px;
            background: var(--bg-secondary);
            border-radius: 8px;
            border-left: 3px solid var(--accent);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .alert-item:hover {
            transform: translateX(2px);
        }

        .alert-left {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .alert-dot {
            width: 8px;
            height: 8px;
            background: var(--accent);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .alert-time {
            font-size: 11px;
            color: var(--text-secondary);
        }

        .alert-text {
            flex: 1;
            font-size: 14px;
        }

        .dpad {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
        }

        .dpad-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            max-width: 250px;
            margin: 0 auto 20px auto;
        }

        .grid-item {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dpad-btn {
            width: 100%;
            height: 100%;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            font-size: 20px;
            font-weight: 600;
        }

        .dpad-btn:hover {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
            transform: scale(1.05);
        }

        .dpad-btn:active {
            transform: scale(0.95);
        }

        .dpad-btn.center {
            background: var(--accent);
            color: white;
            font-size: 10px;
        }

        .dpad-btn.center:hover {
            background: var(--accent-hover);
        }

        .slider-container {
            background: var(--bg-secondary);
            border-radius: 8px;
            padding: 15px;
        }

        .slider-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .slider-value {
            color: var(--accent);
            font-weight: 600;
        }

        input[type="range"] {
            width: 100%;
            height: 6px;
            background: var(--border);
            border-radius: 3px;
            outline: none;
            -webkit-appearance: none;
            margin-bottom: 15px;
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            background: var(--accent);
            border-radius: 50%;
            cursor: pointer;
        }

        input[type="range"]::-moz-range-thumb {
            width: 18px;
            height: 18px;
            background: var(--accent);
            border-radius: 50%;
            cursor: pointer;
            border: none;
        }

        .loading-state, .error-state {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #000;
            color: #999;
            font-size: 14px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid var(--border);
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 15px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .btn {
            padding: 8px 16px;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn:hover {
            background: var(--accent-hover);
        }

        .logout-btn {
            margin-top: 20px;
        }

        .logout-btn button {
            width: 100%;
            padding: 12px;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-primary);
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-btn button:hover {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
        }

        .page-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .back-arrow {
            width: 40px;
            height: 40px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .back-arrow:hover {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
        }

        .page-title.small {
            font-size: 24px;
            margin: 0;
        }

        .settings-layout {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 30px;
        }

        @media (max-width: 1024px) {
            .settings-layout {
                grid-template-columns: 1fr;
            }
        }

        .settings-sidebar {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sidebar-item.active {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
        }

        .sidebar-item:hover:not(.active) {
            background: var(--bg-secondary);
        }

        .sidebar-text {
            font-size: 14px;
            font-weight: 500;
        }

        .content-area {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 30px;
        }

        @media (max-width: 1024px) {
            .content-area {
                display: none;
            }

            .content-area.expanded {
                display: block;
            }
        }

        .content-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
            font-size: 20px;
            font-weight: 600;
        }

        .form-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .form-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
        }

        .form-card.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: flex;
            align-items: center;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 10px;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            background: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: 6px;
            color: var(--text-primary);
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent);
        }

        .password-section {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .section-subtitle {
            font-size: 13px;
            color: var(--text-secondary);
        }

        .password-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .password-row {
                grid-template-columns: 1fr;
            }
        }

        .password-field {
            display: flex;
            align-items: center;
            background: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 0 12px;
            transition: all 0.3s ease;
        }

        .password-field:focus-within {
            border-color: var(--accent);
        }

        .password-input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 12px 0;
            color: var(--text-primary);
        }

        .password-input:focus {
            outline: none;
            border: none;
        }

        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-cancel {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-primary);
            padding: 12px 30px;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: var(--bg-secondary);
        }

        .btn-save {
            background: var(--accent);
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            border: none;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            background: var(--accent-hover);
        }

        .servo-status {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px;
            margin-top: 15px;
            font-size: 12px;
            text-align: center;
            color: var(--text-secondary);
        }

        .stream-controls {
            display: flex;
            gap: 8px;
        }

        .stream-btn {
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Poppins', sans-serif;
        }

        .stream-btn.start {
            background: #22c55e;
            color: white;
        }

        .stream-btn.start:hover {
            background: #16a34a;
        }

        .stream-btn.stop {
            background: var(--accent);
            color: white;
        }

        .stream-btn.stop:hover {
            background: var(--accent-hover);
        }

        .camera-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 10px;
        }

        .fps-indicator {
            color: var(--accent);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Home Page -->
        <div id="homePage" class="page active">
            <div class="container">
               <div class="header">
                <div class="logo" onclick="navigateTo('home')" style="cursor: pointer; display: flex; justify-content: center; align-items: center;">
                    <img src="{{ asset('theodore_logo.svg') }}" alt="Theodore Logo" style="width: 160px; height: 80px; object-fit: contain;">
                </div>
                    <div class="header-actions">
                        <button class="icon-btn theme-toggle" onclick="toggleTheme()">
                            <span class="moon-icon">🌙</span>
                            <span class="sun-icon">☀️</span>
                        </button>
                        <button class="icon-btn" onclick="navigateTo('settings')">
                            ⚙️ Settings
                        </button>
                    </div>
                </div>

                <h1 class="page-title">Home</h1>

                <div class="content-grid">
                    <div class="video-section">
                        <div class="section-header">
                            <div class="section-title">
                                📹 Live Camera Feed
                            </div>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div class="status-badge">
                                    <span class="status-dot"></span>
                                    <span id="streamStatus">Snapshot Mode</span>
                                </div>
                                <div class="stream-controls">
                                    <button class="stream-btn start" onclick="startSnapshotMode()">Start</button>
                                    <button class="stream-btn stop" onclick="stopSnapshotMode()">Stop</button>
                                </div>
                            </div>
                        </div>

                        <div class="video-container">
                            <img id="cameraSnapshot" src="" alt="Camera Feed" style="display: none;">

                            <div id="loadingMsg" class="loading-state">
                                <div class="spinner"></div>
                                <span>Starting camera...</span>
                            </div>

                            <div id="errorMsg" class="error-state" style="display: none;">
                                <span style="font-size: 32px; margin-bottom: 10px;">📷</span>
                                <span>Camera Offline</span>
                                <button onclick="startSnapshotMode()" class="btn" style="margin-top: 15px;">Retry</button>
                            </div>

                            <div class="video-overlay" id="timestamp">Loading...</div>
                            <div class="video-timestamp">M1</div>
                        </div>

                        <div class="camera-info">
                            <span>Connected to: <strong id="cameraIp">Loading...</strong></span>
                            <span>Mode: <span id="connectionMode">Snapshot</span> | FPS: <span class="fps-indicator" id="currentFps">10</span></span>
                        </div>
                    </div>

                    <div class="sidebar">
                        <div class="alerts-card">
                            <div class="alerts-header">
                                <div class="alerts-title">
                                    🔔 Alerts
                                </div>
                                <div class="alerts-date">Mon, Oct 25</div>
                            </div>
                            <div class="alerts-list">
                                <div class="alert-item">
                                    <div class="alert-left">
                                        <div class="alert-dot"></div>
                                        <div class="alert-time">07:00PM</div>
                                    </div>
                                    <div class="alert-text">Fire Detected</div>
                                </div>
                                <div class="alert-item">
                                    <div class="alert-left">
                                        <div class="alert-dot"></div>
                                        <div class="alert-time">06:45PM</div>
                                    </div>
                                    <div class="alert-text">Motion Detected</div>
                                </div>
                                <div class="alert-item">
                                    <div class="alert-left">
                                        <div class="alert-dot"></div>
                                        <div class="alert-time">06:30PM</div>
                                    </div>
                                    <div class="alert-text">Smoke Detected</div>
                                </div>
                            </div>
                        </div>

                        <div class="dpad">
                            <div class="dpad-title">Camera Control</div>

                            <div class="grid-container">
                                <div class="grid-item"></div>
                                <div class="grid-item">
                                    <button class="dpad-btn" onclick="moveServo('up')">↑</button>
                                </div>
                                <div class="grid-item"></div>

                                <div class="grid-item">
                                    <button class="dpad-btn" onclick="moveServo('left')">←</button>
                                </div>
                                <div class="grid-item">
                                    <button class="dpad-btn center" onclick="moveServo('center')">CENTER</button>
                                </div>
                                <div class="grid-item">
                                    <button class="dpad-btn" onclick="moveServo('right')">→</button>
                                </div>

                                <div class="grid-item"></div>
                                <div class="grid-item">
                                    <button class="dpad-btn" onclick="moveServo('down')">↓</button>
                                </div>
                                <div class="grid-item"></div>
                            </div>

                            <div class="slider-container">
                                <div class="slider-label">
                                    <span>Pan (Horizontal)</span>
                                    <span class="slider-value" id="panAngle">90°</span>
                                </div>
                                <input type="range" id="panSlider" min="0" max="180" value="90" oninput="updateServoAngle('pan', this.value)">

                                <div class="slider-label">
                                    <span>Tilt (Vertical)</span>
                                    <span class="slider-value" id="tiltAngle">90°</span>
                                </div>
                                <input type="range" id="tiltSlider" min="0" max="180" value="90" oninput="updateServoAngle('tilt', this.value)">
                            </div>

                            <div class="servo-status" id="servoStatus">
                                Ready
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ngrok Configuration Section -->
                <div style="margin-top: 30px;">
                    <div class="video-section">
                        <div class="section-header">
                            <div class="section-title">
                                🌐 Remote Access Configuration
                            </div>
                        </div>

                        <div style="background: var(--bg-secondary); border-radius: 8px; padding: 20px;">
                            <div style="margin-bottom: 15px;">
                                <strong>Current Status:</strong>
                                <span id="ngrokStatusText" style="color: var(--text-secondary);">Loading...</span>
                            </div>

                            <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                                <input
                                    type="text"
                                    id="ngrokUrlInput"
                                    placeholder="https://your-url.ngrok-free.app"
                                    style="flex: 1; padding: 12px; background: var(--bg-primary); border: 1px solid var(--border); border-radius: 6px; color: var(--text-primary); font-family: 'Poppins', sans-serif; font-size: 14px;"
                                >
                                <button class="btn" onclick="updateNgrokUrl()">Update ngrok URL</button>
                                <button class="btn" onclick="removeNgrokUrl()" style="background: var(--bg-secondary); border: 1px solid var(--border); color: var(--text-primary);">
                                    Use Local IP
                                </button>
                            </div>

                            <div style="font-size: 12px; color: var(--text-secondary); line-height: 1.6;">
                                <strong>💡 How to use ngrok for remote access:</strong><br>
                                1. Open terminal/cmd on the computer with ESP32-CAM<br>
                                2. Run: <code style="background: var(--bg-primary); padding: 2px 6px; border-radius: 3px;">ngrok http {{ $cameraIp ?? '192.168.68.112' }}:80</code><br>
                                3. Copy the HTTPS URL (e.g., https://abc123.ngrok-free.app)<br>
                                4. Paste it above and click "Update ngrok URL"<br>
                                5. Now you can access your camera from anywhere!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Page (abbreviated for space) -->
        <div id="settingsPage" class="page">
            <div class="container">
                <div class="header">
                    <div class="logo" onclick="navigateTo('home')">THEODORE</div>
                    <button class="icon-btn theme-toggle" onclick="toggleTheme()">
                        <span class="moon-icon">🌙</span>
                        <span class="sun-icon">☀️</span>
                    </button>
                </div>
                <div class="page-header">
                    <div class="back-arrow" onclick="navigateTo('home')">
                        <span style="font-size: 20px;">←</span>
                    </div>
                    <span class="page-title small">Settings</span>
                </div>

                <div class="settings-layout">
                    <div class="settings-sidebar">
                        <div class="sidebar-item active">
                            <span style="font-size: 20px;">👤</span>
                            <span class="sidebar-text">My Account</span>
                        </div>
                        <div class="logout-btn">
                            <button onclick="confirmLogout()">Log-out</button>
                        </div>
                    </div>

                    <div class="content-area expanded">
                        <div class="content-header">
                            <span style="font-size: 20px;">👤</span>
                            <span>My Account</span>
                        </div>

                       <form method="POST" action="{{ route('account.update') }}" id="accountForm" onsubmit="saveSettings(event)">
                            @csrf
                            @method('PUT')

                            <div class="form-grid">
                                <div class="form-row">
                                    <div class="form-card">
                                        <label class="form-label">Username</label>
                                        <input type="text" name="username" class="form-input" placeholder="Enter username"
                                            value="{{ Auth::user()->name }}" required>
                                    </div>
                                    <div class="form-card">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-input" placeholder="Enter email"
                                            value="{{ Auth::user()->email }}" required>
                                    </div>
                                </div>

                                <div class="form-card full-width">
                                    <div class="password-section">
                                        <div class="section-title">Change Password</div>
                                        <div class="section-subtitle">Leave blank if you don't want to change password</div>
                                        <div class="password-row">
                                            <div style="flex: 1;">
                                                <label class="form-label">Current Password</label>
                                                <div class="password-field">
                                                    <span style="font-size: 16px; margin-right: 8px;">🔒</span>
                                                    <input type="password" name="current_password" class="form-input password-input"
                                                        placeholder="Enter current password" id="currentPassword">
                                                    <span style="font-size: 16px; cursor: pointer; margin-left: 8px;"
                                                        onclick="togglePassword('currentPassword')">👁️</span>
                                                </div>
                                            </div>
                                            <div style="flex: 1;">
                                                <label class="form-label">New Password</label>
                                                <div class="password-field">
                                                    <span style="font-size: 16px; margin-right: 8px;">🔒</span>
                                                    <input type="password" name="new_password" class="form-input password-input"
                                                        placeholder="Enter new password" id="newPassword">
                                                    <span style="font-size: 16px; cursor: pointer; margin-left: 8px;"
                                                        onclick="togglePassword('newPassword')">👁️</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="margin-top: 15px;">
                                            <label class="form-label">Confirm New Password</label>
                                            <div class="password-field">
                                                <span style="font-size: 16px; margin-right: 8px;">🔒</span>
                                                <input type="password" name="new_password_confirmation" class="form-input password-input"
                                                    placeholder="Confirm new password" id="confirmPassword">
                                                <span style="font-size: 16px; cursor: pointer; margin-left: 8px;"
                                                    onclick="togglePassword('confirmPassword')">👁️</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="action-buttons">
                                <button type="button" class="btn btn-cancel" onclick="navigateTo('home')">Cancel</button>
                                <button type="submit" class="btn btn-save">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="display: none;">
        @csrf
    </form>

    <script>
    // ==================== GLOBAL VARIABLES ====================
    const camSnapshot = document.getElementById('cameraSnapshot');
    const loadingMsg = document.getElementById('loadingMsg');
    const errorMsg = document.getElementById('errorMsg');
    const timestampDiv = document.getElementById('timestamp');

    // These will be set dynamically based on ngrok status
    let baseUrl = '';
    let snapshotUrl = '';
    let usingNgrok = false;

    // Snapshot mode variables
    let snapshotInterval = null;
    let currentFps = 15;
    let frameCount = 0;
    let lastFrameTime = Date.now();
    let isSnapshotModeActive = false;
    let consecutiveErrors = 0;
    let maxConsecutiveErrors = 3;

    // Servo Control Variables
    let currentPanAngle = 90;
    let currentTiltAngle = 90;
    const servoStep = 15;

    // ==================== FETCH STREAM URL ====================
    async function fetchStreamUrl() {
        try {
            const response = await fetch('/api/stream-url');
            const data = await response.json();

            if (data.status === 'success') {
                baseUrl = data.base_url;
                snapshotUrl = data.snapshot_url;
                usingNgrok = data.using_ngrok;

                console.log('📡 Stream URL fetched:', {
                    baseUrl,
                    snapshotUrl,
                    usingNgrok,
                    mode: data.mode
                });

                // Update UI
                const displayText = usingNgrok ? 'Remote (ngrok)' : baseUrl.replace('http://', '');
                document.getElementById('cameraIp').textContent = displayText;
                document.getElementById('connectionMode').textContent = usingNgrok ? 'Stream' : 'Snapshot';

                return true;
            }
            throw new Error('Failed to fetch stream URL');
        } catch (error) {
            console.error('❌ Error fetching stream URL:', error);
            // Fallback to local IP
            const fallbackIp = "{{ $cameraIp ?? '192.168.68.112' }}";
            baseUrl = `http://${fallbackIp}`;
            snapshotUrl = `${baseUrl}/snapshot`;
            usingNgrok = false;
            document.getElementById('cameraIp').textContent = fallbackIp;
            return false;
        }
    }

    // ==================== NAVIGATION ====================
    function navigateTo(pageName) {
        const pages = document.querySelectorAll('.page');
        pages.forEach(page => page.classList.remove('active'));

        const targetPage = document.getElementById(pageName + 'Page');
        if (targetPage) {
            targetPage.classList.add('active');
        }
    }

    // ==================== THEME TOGGLE ====================
    function toggleTheme() {
        const html = document.documentElement;
        const currentTheme = html.getAttribute('data-theme');

        if (currentTheme === 'light') {
            html.removeAttribute('data-theme');
            localStorage.setItem('theme', 'dark');
        } else {
            html.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
        }
    }

    // Load saved theme
    function loadTheme() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    }

    // ==================== SNAPSHOT MODE FUNCTIONS ====================
    async function startSnapshotMode() {
        if (isSnapshotModeActive) return;

        console.log('📸 Starting camera feed at ' + currentFps + ' FPS...');

        // Fetch the current stream URL first
        await fetchStreamUrl();

        isSnapshotModeActive = true;
        consecutiveErrors = 0;

        // Show loading initially
        loadingMsg.style.display = 'flex';
        errorMsg.style.display = 'none';
        camSnapshot.style.display = 'none';

        // Clear any existing intervals
        if (snapshotInterval) {
            clearInterval(snapshotInterval);
            snapshotInterval = null;
        }
        if (window.streamFpsInterval) {
            clearInterval(window.streamFpsInterval);
            window.streamFpsInterval = null;
        }

        // For MJPEG stream (ngrok), we don't need an interval - use DIRECT URL
        if (usingNgrok) {
            console.log('✅ Using direct ngrok stream (no proxy)');
            updateSnapshot(); // This will set up the continuous stream
            document.getElementById('streamStatus').textContent = 'Remote - Live MJPEG Stream';
        } else {
            // For local snapshots, use interval
            const intervalMs = 1000 / currentFps;
            snapshotInterval = setInterval(() => {
                updateSnapshot();
            }, intervalMs);
            updateSnapshot();
            document.getElementById('streamStatus').textContent = `Local - ${currentFps} FPS`;
            console.log(`✅ Snapshot mode active - ${currentFps} FPS via Local`);
        }
    }

    function stopSnapshotMode() {
        if (snapshotInterval) {
            clearInterval(snapshotInterval);
            snapshotInterval = null;
        }
        if (window.streamFpsInterval) {
            clearInterval(window.streamFpsInterval);
            window.streamFpsInterval = null;
        }

        isSnapshotModeActive = false;
        consecutiveErrors = 0;
        camSnapshot.style.display = 'none';
        camSnapshot.src = ''; // Clear the stream
        loadingMsg.style.display = 'flex';

        document.getElementById('streamStatus').textContent = 'Stopped';

        console.log('⏹️ Camera feed stopped');
    }

    function updateSnapshot() {
        if (!snapshotUrl) {
            console.error('❌ No snapshot URL available');
            showError();
            return;
        }

        const timestamp = Date.now();

        // For ngrok, use DIRECT stream URL (no proxy!)
        if (usingNgrok) {
            const streamUrl = snapshotUrl.replace('/snapshot', '/stream');

            console.log('📺 Using DIRECT MJPEG stream:', streamUrl);

            // Set up the image element ONCE for MJPEG stream
            if (camSnapshot.src !== streamUrl) {
                camSnapshot.onload = function() {
                    console.log('✅ Stream connected successfully');
                    camSnapshot.style.display = 'block';
                    loadingMsg.style.display = 'none';
                    errorMsg.style.display = 'none';
                    consecutiveErrors = 0;
                    updateTimestamp();
                };

                camSnapshot.onerror = function(e) {
                    console.error('❌ Stream failed:', {
                        streamUrl: streamUrl,
                        error: e,
                        hint: 'Check if ngrok is running and ESP32 is accessible'
                    });
                    consecutiveErrors++;
                    if (consecutiveErrors >= maxConsecutiveErrors) {
                        showError();
                    }
                };

                // Set the src to start the MJPEG stream DIRECTLY
                camSnapshot.src = streamUrl;
            }

            // Update timestamp periodically for stream mode
            updateTimestamp();

            // Update FPS counter for stream
            if (!window.streamFpsInterval) {
                window.streamFpsInterval = setInterval(updateFpsCounter, 100);
            }

            // Clear the snapshot interval since MJPEG is continuous
            if (snapshotInterval) {
                clearInterval(snapshotInterval);
                snapshotInterval = null;
            }

        } else {
            // For local IP, use snapshot mode
            const snapshotWithTimestamp = `${snapshotUrl}?t=${timestamp}&fps=${currentFps}`;

            console.log('🖼️ Fetching snapshot directly:', snapshotWithTimestamp);

            const img = new Image();

            img.onload = function() {
                camSnapshot.src = this.src;
                camSnapshot.style.display = 'block';
                loadingMsg.style.display = 'none';
                errorMsg.style.display = 'none';
                consecutiveErrors = 0;

                updateTimestamp();
                updateFpsCounter();
            };

            img.onerror = function(e) {
                console.error('❌ Local Snapshot failed:', {
                    url: snapshotWithTimestamp,
                    error: e
                });
                consecutiveErrors++;
                if (consecutiveErrors >= maxConsecutiveErrors) {
                    showError();
                }
            };

            img.src = snapshotWithTimestamp;
        }
    }

    function updateFpsCounter() {
        frameCount++;
        const currentTime = Date.now();
        const elapsed = currentTime - lastFrameTime;

        if (elapsed >= 1000) {
            const actualFps = Math.round((frameCount * 1000) / elapsed);
            document.getElementById('currentFps').textContent = actualFps;
            frameCount = 0;
            lastFrameTime = currentTime;
        }
    }

    function updateTimestamp() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        timestampDiv.textContent = timeStr;
    }

    function showError() {
        stopSnapshotMode();
        camSnapshot.style.display = 'none';
        loadingMsg.style.display = 'none';
        errorMsg.style.display = 'flex';
        console.log('❌ Camera connection failed');
    }

    // ==================== SERVO CONTROL FUNCTIONS ====================
    function moveServo(direction) {
        let newPan = currentPanAngle;
        let newTilt = currentTiltAngle;

        switch(direction) {
            case 'up':
                newTilt = Math.min(180, currentTiltAngle + servoStep);
                break;
            case 'down':
                newTilt = Math.max(0, currentTiltAngle - servoStep);
                break;
            case 'left':
                newPan = Math.max(0, currentPanAngle - servoStep);
                break;
            case 'right':
                newPan = Math.min(180, currentPanAngle + servoStep);
                break;
            case 'center':
                newPan = 90;
                newTilt = 90;
                break;
        }

        updateServoPosition(newPan, newTilt);
    }

    function updateServoAngle(axis, value) {
        const angle = parseInt(value);

        if (axis === 'pan') {
            document.getElementById('panAngle').textContent = angle + '°';
            currentPanAngle = angle;
            sendServoCommand(angle, currentTiltAngle);
        } else if (axis === 'tilt') {
            document.getElementById('tiltAngle').textContent = angle + '°';
            currentTiltAngle = angle;
            sendServoCommand(currentPanAngle, angle);
        }
    }

    function updateServoPosition(pan, tilt) {
        currentPanAngle = pan;
        currentTiltAngle = tilt;

        document.getElementById('panSlider').value = pan;
        document.getElementById('tiltSlider').value = tilt;
        document.getElementById('panAngle').textContent = pan + '°';
        document.getElementById('tiltAngle').textContent = tilt + '°';

        sendServoCommand(pan, tilt);
    }

    function sendServoCommand(pan, tilt) {
        const statusDiv = document.getElementById('servoStatus');
        statusDiv.textContent = `Moving to Pan: ${pan}° | Tilt: ${tilt}°`;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

        fetch('/api/servo/move', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ pan: pan, tilt: tilt })
        })
        .then(response => response.json())
        .then(data => {
            console.log(`Servo command sent: Pan=${pan}°, Tilt=${tilt}°`);
            if (data.status === 'success') {
                statusDiv.textContent = `✅ Position: Pan ${data.pan}° | Tilt ${data.tilt}°`;
            } else {
                throw new Error(data.message || 'Failed to move camera');
            }
        })
        .catch(error => {
            console.error('Servo command failed:', error);
            statusDiv.textContent = '❌ Failed to move camera';
        });
    }

    // ==================== LOGOUT ====================
    function confirmLogout() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out of your session",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e50914',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, logout',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Logging out...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                setTimeout(() => {
                    document.getElementById('logoutForm').submit();
                }, 500);
            }
        });
    }

    // ==================== SETTINGS FUNCTIONS ====================
    function saveSettings(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        Swal.fire({
            title: 'Saving Changes...',
            text: 'Please wait',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch('/user/account-update', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(result => {
            if (result.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: result.message,
                    confirmButtonColor: '#e50914'
                }).then(() => { navigateTo('home'); });
            } else {
                throw new Error(result.message || 'Failed to update account');
            }
        })
        .catch(error => {
            console.error('Update error:', error);
            let errorMessage = 'Something went wrong. Please try again.';
            if (error.errors) {
                errorMessage = Object.values(error.errors).flat().join('<br>');
            } else if (error.message) {
                errorMessage = error.message;
            }
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                html: errorMessage,
                confirmButtonColor: '#e50914'
            });
        });
    }

    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    function initAccordion() {
        const sidebarItem = document.querySelector('.sidebar-item');
        const contentArea = document.querySelector('.content-area');

        if (!sidebarItem || !contentArea) return;

        if (window.innerWidth <= 1024) {
            sidebarItem.addEventListener('click', function() {
                this.classList.toggle('active');
                contentArea.classList.toggle('expanded');
            });
        } else {
            contentArea.classList.add('expanded');
            sidebarItem.classList.add('active');
        }
    }

    // ==================== NGROK FUNCTIONS ====================
    async function updateNgrokUrl() {
        const url = document.getElementById('ngrokUrlInput').value.trim();

        if (!url) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing URL',
                text: 'Please enter a ngrok URL',
                confirmButtonColor: '#e50914'
            });
            return;
        }

        if (!url.startsWith('https://') || !url.includes('ngrok')) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid URL',
                text: 'Please enter a valid ngrok HTTPS URL (e.g., https://abc123.ngrok-free.app)',
                confirmButtonColor: '#e50914'
            });
            return;
        }

        Swal.fire({
            title: 'Updating...',
            text: 'Please wait',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => { Swal.showLoading(); }
        });

        try {
            const response = await fetch('/api/ngrok/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ ngrok_url: url })
            });

            const data = await response.json();

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: 'ngrok URL updated. Restarting stream...',
                    confirmButtonColor: '#e50914',
                    timer: 1500,
                    showConfirmButton: false
                }).then(async () => {
                    stopSnapshotMode();
                    await fetchStreamUrl();
                    await checkNgrokStatus();
                    setTimeout(() => { startSnapshotMode(); }, 500);
                });
            } else {
                throw new Error(data.message || 'Failed to update');
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: error.message,
                confirmButtonColor: '#e50914'
            });
        }
    }

    async function removeNgrokUrl() {
        Swal.fire({
            title: 'Switch to Local IP?',
            text: 'This will use your local network IP instead of ngrok',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#e50914',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, switch',
            cancelButtonText: 'Cancel'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch('/api/ngrok/remove', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    });

                    const data = await response.json();

                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Switched!',
                            text: 'Now using local IP. Restarting stream...',
                            confirmButtonColor: '#e50914',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(async () => {
                            stopSnapshotMode();
                            await fetchStreamUrl();
                            await checkNgrokStatus();
                            setTimeout(() => { startSnapshotMode(); }, 500);
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: error.message,
                        confirmButtonColor: '#e50914'
                    });
                }
            }
        });
    }

    async function checkNgrokStatus() {
        try {
            const response = await fetch('/api/stream-url');
            const data = await response.json();

            if (data.status === 'success') {
                if (data.using_ngrok) {
                    document.getElementById('ngrokStatusText').innerHTML =
                        '✅ <span style="color: #4ade80;">Using ngrok:</span> ' + data.base_url;
                    document.getElementById('ngrokUrlInput').value = data.base_url;
                } else {
                    document.getElementById('ngrokStatusText').innerHTML =
                        '📡 Using local IP: ' + data.base_url;
                    document.getElementById('ngrokUrlInput').value = '';
                }
            }
        } catch (error) {
            console.log('Could not check ngrok status:', error);
        }
    }

    // ==================== INITIALIZATION ====================
    document.addEventListener('DOMContentLoaded', async function() {
        loadTheme();
        initAccordion();

        console.log('🚀 Initializing camera system...');

        await fetchStreamUrl();
        await checkNgrokStatus();

        console.log('📡 Using URL:', snapshotUrl);
        console.log('🎯 Servo control ready');

        setTimeout(() => { startSnapshotMode(); }, 1000);
        setInterval(updateTimestamp, 1000);

        setInterval(() => {
            if (isSnapshotModeActive && errorMsg.style.display === 'flex') {
                console.log('🔄 Auto-reconnecting...');
                startSnapshotMode();
            }
        }, 5000);
    });

    window.addEventListener('resize', function() {
        const contentArea = document.querySelector('.content-area');
        const sidebarItem = document.querySelector('.sidebar-item');

        if (!contentArea || !sidebarItem) return;

        if (window.innerWidth > 1024) {
            contentArea.classList.add('expanded');
            sidebarItem.classList.add('active');
        } else {
            contentArea.classList.remove('expanded');
            sidebarItem.classList.remove('active');
        }
    });
    </script>
</body>
</html>
