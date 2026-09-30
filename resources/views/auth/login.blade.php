<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Petugas - SIP Perpustakaan Digital</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-dark: #1e3a8a;
            --surface-dark: #0a0f1d;
            --border-glow: rgba(59, 130, 246, 0.35);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--surface-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
            color: #1e293b;
        }

        /* Ambient Glowing Background Background Orbs */
        .ambient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.45;
            pointer-events: none;
            z-index: 0;
            animation: orbFloat 10s ease-in-out infinite alternate;
        }

        .orb-1 {
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, #3b82f6 0%, #1d4ed8 70%);
            top: -100px;
            left: -100px;
        }

        .orb-2 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, #6366f1 0%, #4338ca 70%);
            bottom: -80px;
            right: -80px;
            animation-duration: 12s;
        }

        .orb-3 {
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, #06b6d4 0%, #0284c7 70%);
            top: 40%;
            left: 35%;
            opacity: 0.25;
            animation-duration: 15s;
        }

        @keyframes orbFloat {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(40px, 30px) scale(1.08); }
        }

        /* Split-Card Master Container */
        .premium-login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1020px;
            background: #ffffff;
            border-radius: 28px;
            box-shadow: 0 30px 70px -15px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.08);
            overflow: hidden;
            display: flex;
            min-height: 620px;
        }

        /* Left Side: Brand Showcase Panel */
        .brand-panel {
            flex: 1.1;
            background: linear-gradient(150deg, #0b1329 0%, #0f1d43 60%, #080d1a 100%);
            padding: 3.5rem 3rem;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 10% 20%, rgba(59, 130, 246, 0.15), transparent 50%),
                        radial-gradient(circle at 90% 80%, rgba(99, 102, 241, 0.15), transparent 50%);
            pointer-events: none;
        }

        .brand-logo-hero {
            width: 54px;
            height: 54px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: #ffffff;
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.25);
            flex-shrink: 0;
        }

        .feature-point {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .feature-icon-badge {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .feature-icon-1 { background: rgba(59, 130, 246, 0.18); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
        .feature-icon-2 { background: rgba(16, 185, 129, 0.18); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .feature-icon-3 { background: rgba(245, 158, 11, 0.18); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }

        .glass-stats-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1rem 1.25rem;
        }

        /* Right Side: Form Panel */
        .form-panel {
            flex: 1;
            padding: 3.5rem 3.25rem;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-control, .input-group-text {
            border-color: #e2e8f0;
            background-color: #f8fafc;
            color: #1e293b;
            font-size: 0.925rem;
            transition: all 0.2s ease;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 12px;
        }

        .form-control:focus {
            background-color: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .input-group .form-control:focus {
            z-index: 3;
        }

        .btn-submit-login {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: none;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.825rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
            transition: all 0.22s ease;
        }

        .btn-submit-login:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.45);
            color: #ffffff;
        }

        .btn-submit-login:active {
            transform: translateY(0);
        }

        /* Demo Account Cards */
        .demo-card-item {
            cursor: pointer;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.65rem 0.85rem;
            background-color: #f8fafc;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.65rem;
            flex: 1;
        }

        .demo-card-item:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
        }

        .demo-card-item.active-click {
            animation: pulseDemo 0.4s ease-in-out;
        }

        @keyframes pulseDemo {
            0% { transform: scale(0.97); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }

        /* Pulse Dot */
        .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: #10b981;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: livePulse 2s infinite;
        }

        @keyframes livePulse {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* Responsive Layout */
        @media (max-width: 900px) {
            .premium-login-container {
                flex-direction: column;
                max-width: 480px;
                min-height: auto;
            }
            .brand-panel {
                padding: 2.5rem 2rem;
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            }
            .brand-panel .feature-list,
            .brand-panel .glass-stats-card {
                display: none;
            }
            .form-panel {
                padding: 2.5rem 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Ambient Background Light Orbs -->
    <div class="ambient-orb orb-1"></div>
    <div class="ambient-orb orb-2"></div>
    <div class="ambient-orb orb-3"></div>

    <!-- Master Login Box -->
    <div class="premium-login-container">
        <!-- Left Side: Brand Showcase Panel -->
        <div class="brand-panel">
            <!-- Brand Top Header -->
            <div>
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="brand-logo-hero">
                        <i class="bi bi-book-half"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h4 class="fw-bold mb-0 text-white tracking-wide">SIP Perpustakaan</h4>
                            <span class="badge bg-primary bg-opacity-25 text-info border border-primary border-opacity-30 px-2 py-0.5" style="font-size: 0.68rem; font-weight: 700;">PRO</span>
                        </div>
                        <small class="text-white-50" style="font-size: 0.78rem;">Sistem Informasi Perpustakaan Digital</small>
                    </div>
                </div>

                <h3 class="fw-extrabold text-white lh-sm mb-3">
                    Manajemen Perpustakaan Cerdas, Cepat & Terintegrasi.
                </h3>
                <p class="text-secondary small mb-4" style="line-height: 1.6;">
                    Akses kontrol penuh operasional perpustakaan, pencatatan sirkulasi otomatis, katalog buku digital, serta rekapitulasi data anggota secara aman.
                </p>

                <!-- Feature Highlights -->
                <div class="feature-list pt-2">
                    <div class="feature-point">
                        <div class="feature-icon-badge feature-icon-1">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-white small">Sirkulasi Real-Time & Hitung Denda Otomatis</div>
                            <small class="text-secondary" style="font-size: 0.78rem;">Pencatatan peminjaman, jatuh tempo, serta denda keterlambatan akurat.</small>
                        </div>
                    </div>

                    <div class="feature-point">
                        <div class="feature-icon-badge feature-icon-2">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-white small">Katalog Buku Digital & Penempatan Rak</div>
                            <small class="text-secondary" style="font-size: 0.78rem;">Klasifikasi kategori buku, nomor ISBN, dan pemantauan stok fisik presisi.</small>
                        </div>
                    </div>

                    <div class="feature-point">
                        <div class="feature-icon-badge feature-icon-3">
                            <i class="bi bi-person-vcard-fill"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-white small">Cetak Kartu Anggota & Barcode Digital</div>
                            <small class="text-secondary" style="font-size: 0.78rem;">Dukungan kartu tanda anggota resmi berstandar barcode digital perpustakaan.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Mini Status Card -->
            <div class="glass-stats-card mt-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <span class="pulse-dot"></span>
                        <span class="text-white small fw-medium" style="font-size: 0.8rem;">Sistem Perpustakaan Aktif</span>
                    </div>
                    <span class="text-white-50 small" style="font-size: 0.75rem;"><i class="bi bi-shield-check text-success me-1"></i> Terenkripsi SSL</span>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form Panel -->
        <div class="form-panel">
            <div class="mb-4">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h3 class="fw-bold text-dark mb-0">Selamat Datang 👋</h3>
                </div>
                <p class="text-muted small">Silakan masukkan kredensial akun petugas untuk masuk ke sistem.</p>
            </div>

            <!-- Flash Info Session Alert -->
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show py-2.5 px-3 border-0 shadow-sm d-flex align-items-center gap-2 mb-3" role="alert" style="border-radius: 12px; background-color: #eff6ff; color: #1d4ed8;">
                    <i class="bi bi-info-circle-fill fs-5 text-primary"></i>
                    <div class="small fw-medium">{{ session('info') }}</div>
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Errors Alert -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show py-2.5 px-3 border-0 shadow-sm d-flex align-items-center gap-2 mb-3" role="alert" style="border-radius: 12px; background-color: #fef2f2; color: #b91c1c;">
                    <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                    <div class="small fw-medium">{{ $errors->first() }}</div>
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold text-secondary mb-1">Alamat Email Petugas</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0 rounded-start-3 text-muted"><i class="bi bi-envelope fs-6"></i></span>
                        <input type="email" class="form-control border-start-0 ps-1 rounded-end-3" id="email" name="email" value="{{ old('email', 'admin@sip.test') }}" required autofocus placeholder="contoh: admin@sip.test">
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label small fw-semibold text-secondary mb-0">Kata Sandi</label>
                        <span class="text-muted small" style="font-size: 0.75rem;">Default: <code>password</code></span>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text border-end-0 rounded-start-3 text-muted"><i class="bi bi-lock fs-6"></i></span>
                        <input type="password" class="form-control border-start-0 border-end-0 ps-1" id="password" name="password" value="password" required placeholder="Masukkan kata sandi">
                        <button class="btn btn-outline-light border border-start-0 rounded-end-3 text-muted" type="button" id="togglePasswordBtn" title="Tampilkan Kata Sandi">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                        <label class="form-check-label small text-secondary" for="remember">
                            Ingat sesi saya
                        </label>
                    </div>
                    <span class="small text-muted" style="font-size: 0.78rem;">
                        <i class="bi bi-shield-lock me-1"></i>Aman & Terverifikasi
                    </span>
                </div>

                <button type="submit" class="btn btn-submit-login w-100 d-flex align-items-center justify-content-center gap-2 mb-4">
                    <span>Masuk ke Sistem Perpustakaan</span>
                    <i class="bi bi-arrow-right fs-6"></i>
                </button>
            </form>

            <!-- Quick Demo Credentials Box -->
            <div class="pt-3 border-top">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <small class="text-muted fw-semibold" style="font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Akses Cepat Akun Demo:
                    </small>
                    <span class="badge bg-light text-muted border px-1.5 py-0.5" style="font-size: 0.65rem;">Klik untuk Isi</span>
                </div>
                <div class="d-flex gap-2">
                    <div class="demo-card-item" id="demoAdmin" onclick="setDemo('admin@sip.test', 'password', this)">
                        <div class="rounded-3 bg-danger bg-opacity-10 text-danger p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-shield-fill-check"></i>
                        </div>
                        <div class="overflow-hidden">
                            <div class="fw-bold text-dark small" style="font-size: 0.8rem;">Administrator</div>
                            <small class="text-muted text-truncate d-block" style="font-size: 0.7rem;">admin@sip.test</small>
                        </div>
                    </div>

                    <div class="demo-card-item" id="demoPetugas" onclick="setDemo('petugas@sip.test', 'password', this)">
                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <div class="overflow-hidden">
                            <div class="fw-bold text-dark small" style="font-size: 0.8rem;">Petugas Layanan</div>
                            <small class="text-muted text-truncate d-block" style="font-size: 0.7rem;">petugas@sip.test</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Small Info -->
            <div class="mt-4 pt-2 text-center text-muted" style="font-size: 0.75rem;">
                &copy; {{ date('Y') }} Sistem Informasi Perpustakaan (SIP). All rights reserved.
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Demo Credential Selector
        function setDemo(email, pass, element) {
            const emailInput = document.getElementById('email');
            const passInput = document.getElementById('password');

            emailInput.value = email;
            passInput.value = pass;

            // Trigger animation on clicked card
            document.querySelectorAll('.demo-card-item').forEach(el => el.classList.remove('active-click'));
            if (element) {
                element.classList.add('active-click');
                setTimeout(() => element.classList.remove('active-click'), 400);
            }

            // Flash focus on email input
            emailInput.focus();
        }

        // Toggle Password Visibility
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passInput = document.getElementById('password');
        const passIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passInput && passIcon) {
            toggleBtn.addEventListener('click', function () {
                if (passInput.type === 'password') {
                    passInput.type = 'text';
                    passIcon.classList.remove('bi-eye');
                    passIcon.classList.add('bi-eye-slash');
                } else {
                    passInput.type = 'password';
                    passIcon.classList.remove('bi-eye-slash');
                    passIcon.classList.add('bi-eye');
                }
            });
        }
    </script>
</body>
</html>
