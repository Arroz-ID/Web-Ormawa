<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card shadow-lg border-0 overflow-hidden my-5">
                <div class="row g-0">
<div class="col-lg-6 d-none d-lg-block bg-login-image" 
     style="background-image: url('assets/images/logo poltesa.jpg'); 
            background-size: 100%; /* Logo hanya mengisi 50% area agar tajam */
            background-repeat: no-repeat; /* Jangan diulang-ulang */
            background-position: center; /* Posisi di tengah */
            background-color: #ffffffff;"> </div>

                    <div class="col-lg-6">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                                <h2 class="h4 text-gray-900 fw-bold mb-2">Selamat Datang!</h2>
                                <p class="text-muted mb-4">Silakan login untuk mengakses sistem ORMAWA</p>
                            </div>

                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($success)): ?>
                                <div class="alert alert-success alert-dismissible fade show">
                                    <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="index.php?action=login" class="needs-validation" novalidate>
                                
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="identifier" name="identifier" 
                                           value="<?php echo $_POST['identifier'] ?? ''; ?>" 
                                           placeholder="Email / NIM / Username" required autofocus>
                                    <label for="identifier">Email / NIM / Username</label>
                                </div>

                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Password" required>
                                    <label for="password">Password</label>
                                </div>

                                <div class="d-grid gap-2 mb-4">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block shadow-sm">
                                        <i class="fas fa-sign-in-alt me-2"></i>Masuk
                                    </button>
                                </div>

                            </form>
                            <hr>
                            <div class="text-center">
                                <p class="small mb-0">Belum punya akun? 
                                    <a href="index.php?action=register" class="text-decoration-none fw-bold">Daftar Anggota Baru</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>