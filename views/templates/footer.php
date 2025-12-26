<footer class="bg-dark text-white pt-5 mt-5">
    <div class="container">
        <div class="row justify-content-between">
            
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold text-uppercase text-warning mb-3">ORMAWA POLTESA</h5>
                <p class="small text-white-50">
                    Sistem Informasi Organisasi Mahasiswa Politeknik Negeri Sambas. Wadah aspirasi dan kegiatan mahasiswa yang terintegrasi.
                </p>
                <ul class="list-unstyled small text-white-50">
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2 text-warning"></i> Kawasan Pendidikan Tinggi, Jl. Sejangkung, Sebayan, Sambas, Kalimantan Barat 79463</li>
                    <li class="mb-2"><i class="fas fa-phone me-2 text-warning"></i> (0562) 6303000</li>
                    <li class="mb-2"><i class="fas fa-envelope me-2 text-warning"></i> info@poltesa.ac.id</li>
                </ul>
            </div>

            <div class="col-md-3 mb-4">
                <h5 class="fw-bold text-uppercase text-warning mb-3">Menu Cepat</h5>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="index.php" class="text-white-50 text-decoration-none hover-warning">Beranda</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-warning">Daftar Ormawa</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-warning">Berita & Kegiatan</a></li>
                    <li class="mb-2"><a href="index.php?action=login" class="text-white-50 text-decoration-none hover-warning">Login Mahasiswa</a></li>
                </ul>
            </div>

            <div class="col-md-4 mb-4">
                <h5 class="fw-bold text-uppercase text-warning mb-3">Lokasi Kampus</h5>
                <div class="rounded overflow-hidden shadow-sm border border-secondary" style="height: 250px; position: relative;">
                    <iframe 
                        width="100%" 
                        height="100%" 
                        frameborder="0" 
                        scrolling="no" 
                        marginheight="0" 
                        marginwidth="0" 
                        src="https://maps.google.com/maps?q=Politeknik%20Negeri%20Sambas&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        style="border:0;" 
                        allowfullscreen=""
                        aria-hidden="false" 
                        tabindex="0">
                    </iframe>
                </div>
                <div class="mt-2 text-end">
                    <a href="https://goo.gl/maps/PlaceID_Here" target="_blank" class="text-warning small text-decoration-none">
                        <i class="fas fa-external-link-alt me-1"></i> Buka di Aplikasi Maps
                    </a>
                </div>
            </div>

        </div>

        <div class="row mt-4">
            <div class="col-12 text-center py-3 border-top border-secondary">
                <small class="text-white-50">
                    © <?php echo date('Y'); ?> <strong>Politeknik Negeri Sambas</strong>. All Rights Reserved.
                </small>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .hover-warning:hover {
        color: #ffc107 !important;
        padding-left: 5px;
        transition: all 0.3s ease;
    }
</style>

</body>
</html>