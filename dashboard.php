<?php
session_start();

// Cek apakah user sudah login dan levelnya admin
// Jika session belum ada, redirect ke login (sesuaikan dengan logika login Anda)
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit();
}

// Panggil file koneksi dan template
// Sesuaikan path 'includes/' jika file Anda masih di root (hapus 'includes/')
include 'config/koneksi.php'; 
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'includes/topbar.php'; 
?>

<style>
    /* Hilangkan garis bawah pada link */
    a.card-link {
        text-decoration: none !important;
    }
    
    /* Efek animasi saat mouse diarahkan ke card */
    .hover-scale {
        transition: transform 0.2s ease-in-out;
    }
    .hover-scale:hover {
        transform: scale(1.03); /* Membesar 3% */
        box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23) !important;
        cursor: pointer;
    }
</style>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Super Admin</h1>
    </div>

    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <a href="kelola_organisasi.php" class="card-link">
                <div class="card border-left-primary shadow h-100 py-2 hover-scale">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Data Master</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Kelola Organisasi</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-university fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <a href="tambah_organisasi.php" class="card-link">
                <div class="card border-left-success shadow h-100 py-2 hover-scale">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Registrasi Baru</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Tambah Organisasi</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-plus-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <a href="kelola_pendaftaran.php" class="card-link">
                <div class="card border-left-info shadow h-100 py-2 hover-scale">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Verifikasi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Kelola Pendaftaran</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <a href="kelola_pengguna.php" class="card-link">
                <div class="card border-left-warning shadow h-100 py-2 hover-scale">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Manajemen User</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Kelola Pengguna</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users-cog fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Selamat Datang, Admin!</h6>
                </div>
                <div class="card-body">
                    <p>Silakan pilih menu di atas atau di sidebar untuk mengelola data Organisasi Mahasiswa Politeknik Negeri Sambas.</p>
                </div>
            </div>
        </div>
    </div>

</div>
<?php include 'includes/footer.php'; ?>