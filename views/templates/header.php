<?php
// Mendapatkan path dasar secara otomatis
$path = dirname($_SERVER['SCRIPT_NAME']);
$base_path = rtrim($path, '/') . '/';

// Logika untuk menentukan halaman aktif
$current_page = $_GET['action'] ?? 'beranda';

// Cek session start (Hanya start jika belum aktif)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORMAWA POLTESA</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar Biru Gradasi Original */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 12px 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 1px;
            font-size: 1.4rem;
            color: #fff !important;
        }

        /* Nav Link Styling */
        .navbar-nav .nav-link {
            font-weight: 500;
            color: rgba(255,255,255,0.8) !important;
            transition: all 0.3s ease;
            position: relative;
            padding: 8px 15px !important;
        }

        .navbar-nav .nav-link:hover {
            color: #fff !important;
        }

        /* Animasi Indikator Halaman Aktif */
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            width: 0;
            height: 3px;
            background: #ffffff;
            border-radius: 10px;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            transform: translateX(-50%);
            opacity: 0;
        }

        .navbar-nav .nav-link.active {
            color: #fff !important;
            font-weight: 700;
        }

        .navbar-nav .nav-link.active::after {
            width: 70%;
            opacity: 1;
        }

        .navbar-nav .nav-link:hover::after {
            width: 40%;
            opacity: 0.5;
        }

        /* Buttons Styling */
        .btn-auth-login {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(5px);
            border-radius: 50px;
            transition: all 0.3s;
        }
        
        .btn-auth-login:hover {
            background: rgba(255, 255, 255, 0.3);
            color: #fff;
        }
        
        .btn-auth-register {
            background: #fff;
            color: var(--primary-color);
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s;
        }

        .btn-auth-register:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 12px;
        }
    </style>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            
            <a class="navbar-brand" href="<?php echo $base_path; ?>index.php">
                <i class="fas fa-graduation-cap me-2"></i>POLTESA
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'beranda' || $current_page == 'index') ? 'active' : ''; ?>" 
                           href="<?php echo $base_path; ?>index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'organisasi' || $current_page == 'detail') ? 'active' : ''; ?>" 
                           href="<?php echo $base_path; ?>index.php?action=organisasi">Organisasi</a>
                    </li>
                    
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'anggota'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>" 
                           href="<?php echo $base_path; ?>index.php?action=dashboard">Dashboard</a>
                    </li>
                    <?php endif; ?>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    
                    <?php if (isset($_SESSION['admin_id'])): ?>
                        <div class="dropdown">
                            <a class="btn btn-light text-primary px-4 rounded-pill dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-shield me-2"></i> 
                                <span>Admin</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end mt-3">
                                <li><a class="dropdown-item" href="index.php?action=admin_dashboard">Dashboard Admin</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="index.php?action=logout">Logout</a></li>
                            </ul>
                        </div>

                    <?php elseif (isset($_SESSION['anggota_id'])): ?>
                        <div class="dropdown">
                            <a class="btn btn-light text-primary px-4 rounded-pill dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-2"></i> 
                                <?php 
                                    // PERBAIKAN: Ambil nama dari session dengan aman
                                    $nama_display = isset($_SESSION['nama_lengkap']) ? explode(' ', $_SESSION['nama_lengkap'])[0] : 'Member'; 
                                ?>
                                <span><?php echo htmlspecialchars($nama_display); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end mt-3">
                                <li><a class="dropdown-item" href="index.php?action=riwayat"><i class="fas fa-history me-2 text-primary"></i>Riwayat Pendaftaran</a></li>
                                
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="index.php?action=logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </div>

                    <?php else: ?>
                        <a class="btn btn-auth-login px-4 py-2" href="<?php echo $base_path; ?>index.php?action=login">Masuk</a>
                        <a class="btn btn-auth-register px-4 py-2 shadow-sm" href="<?php echo $base_path; ?>index.php?action=register">Daftar</a>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </nav>
    
    <main style="min-height: 80vh;">