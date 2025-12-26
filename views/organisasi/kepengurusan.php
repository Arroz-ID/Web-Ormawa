<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-sitemap me-2"></i>
                        Struktur Kepengurusan <?php echo htmlspecialchars($organisasi['nama_organisasi']); ?>
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Filter by Division -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="filterDivisi" class="form-label">Filter berdasarkan Divisi:</label>
                            <select class="form-select" id="filterDivisi">
                                <option value="">Semua Divisi</option>
                                <?php 
                                $divisions = [];
                                foreach ($kepengurusan as $pengurus) {
                                    if ($pengurus['nama_divisi'] && !in_array($pengurus['nama_divisi'], $divisions)) {
                                        $divisions[] = $pengurus['nama_divisi'];
                                        echo '<option value="' . htmlspecialchars($pengurus['nama_divisi']) . '">' . 
                                             htmlspecialchars($pengurus['nama_divisi']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="searchPengurus" class="form-label">Cari Pengurus:</label>
                            <input type="text" class="form-control" id="searchPengurus" placeholder="Nama atau jabatan...">
                        </div>
                    </div>

                    <?php if (empty($kepengurusan)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Struktur Kepengurusan</h5>
                            <p class="text-muted">Struktur kepengurusan organisasi ini belum diatur.</p>
                        </div>
                    <?php else: ?>
                        <!-- Group by Jabatan Level -->
                        <?php
                        $groupedPengurus = [];
                        foreach ($kepengurusan as $pengurus) {
                            $level = $pengurus['level_jabatan'];
                            if (!isset($groupedPengurus[$level])) {
                                $groupedPengurus[$level] = [];
                            }
                            $groupedPengurus[$level][] = $pengurus;
                        }

                        // Define order of levels
                        $levelOrder = ['Ketua', 'Wakil', 'Sekretaris', 'Bendahara', 'Koordinator', 'Anggota'];
                        ?>

                        <?php foreach ($levelOrder as $level): ?>
                            <?php if (isset($groupedPengurus[$level])): ?>
                                <div class="mb-5">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">
                                        <i class="fas fa-<?php echo getLevelIcon($level); ?> me-2"></i>
                                        <?php echo $level; ?>
                                    </h5>
                                    
                                    <div class="row pengurus-group" data-level="<?php echo strtolower($level); ?>">
                                        <?php foreach ($groupedPengurus[$level] as $pengurus): ?>
                                            <div class="col-lg-4 col-md-6 mb-3 pengurus-item" 
                                                 data-divisi="<?php echo htmlspecialchars($pengurus['nama_divisi'] ?? ''); ?>"
                                                 data-nama="<?php echo strtolower(htmlspecialchars($pengurus['nama_lengkap'])); ?>"
                                                 data-jabatan="<?php echo strtolower(htmlspecialchars($pengurus['nama_jabatan'])); ?>">
                                                <div class="card h-100 border">
                                                    <div class="card-body text-center">
                                                        <!-- Photo -->
                                                        <div class="mb-3">
                                                            <?php if ($pengurus['foto_profil']): ?>
                                                                <img src="assets/images/<?php echo $pengurus['foto_profil']; ?>" 
                                                                     alt="<?php echo htmlspecialchars($pengurus['nama_lengkap']); ?>" 
                                                                     class="rounded-circle" 
                                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                                            <?php else: ?>
                                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                                                     style="width: 80px; height: 80px;">
                                                                    <i class="fas fa-user fa-2x"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>

                                                        <!-- Info -->
                                                        <h6 class="card-title mb-1"><?php echo htmlspecialchars($pengurus['nama_lengkap']); ?></h6>
                                                        <p class="text-primary mb-1 fw-bold"><?php echo htmlspecialchars($pengurus['nama_jabatan']); ?></p>
                                                        
                                                        <?php if ($pengurus['nama_divisi']): ?>
                                                            <p class="text-muted small mb-2"><?php echo htmlspecialchars($pengurus['nama_divisi']); ?></p>
                                                        <?php endif; ?>

                                                        <div class="small text-muted">
                                                            <div class="mb-1">
                                                                <i class="fas fa-id-card me-1"></i>
                                                                <?php echo htmlspecialchars($pengurus['nim']); ?>
                                                            </div>
                                                            <div>
                                                                <i class="fas fa-graduation-cap me-1"></i>
                                                                <?php echo htmlspecialchars($pengurus['jurusan']); ?> - <?php echo $pengurus['angkatan']; ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Periode -->
                                                    <div class="card-footer bg-transparent text-center">
                                                        <small class="text-muted">
                                                            Periode: <?php echo date('M Y', strtotime($pengurus['periode_mulai'])); ?> - 
                                                            <?php echo date('M Y', strtotime($pengurus['periode_selesai'])); ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Filter functionality
document.getElementById('filterDivisi').addEventListener('change', function() {
    filterPengurus();
});

document.getElementById('searchPengurus').addEventListener('input', function() {
    filterPengurus();
});

function filterPengurus() {
    const selectedDivisi = document.getElementById('filterDivisi').value.toLowerCase();
    const searchTerm = document.getElementById('searchPengurus').value.toLowerCase();
    
    document.querySelectorAll('.pengurus-item').forEach(item => {
        const divisi = item.dataset.divisi.toLowerCase();
        const nama = item.dataset.nama;
        const jabatan = item.dataset.jabatan;
        
        const divisiMatch = !selectedDivisi || divisi.includes(selectedDivisi);
        const searchMatch = !searchTerm || nama.includes(searchTerm) || jabatan.includes(searchTerm);
        
        if (divisiMatch && searchMatch) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// Show all when page loads
document.addEventListener('DOMContentLoaded', function() {
    filterPengurus();
});
</script>

<?php
// Helper function untuk icon berdasarkan level jabatan
function getLevelIcon($level) {
    switch ($level) {
        case 'Ketua': return 'crown';
        case 'Wakil': return 'user-shield';
        case 'Sekretaris': return 'file-alt';
        case 'Bendahara': return 'money-bill-wave';
        case 'Koordinator': return 'users-cog';
        case 'Anggota': return 'user';
        default: return 'user';
    }
}
?>

<?php include __DIR__ . '/../templates/footer.php'; ?>