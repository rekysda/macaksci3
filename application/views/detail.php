<div class="col-lg-6">
    <div class="card">
        <div class="card-header">Detail mahasiswa</div>
        <div class="card-body">
            <h5 class="card-title text-capitalize">
                <?= htmlentities($mhs->nama); ?>
            </h5>
            <p class="card-subtitle mb-2 text-muted"><?= htmlentities($mhs->email); ?></p>
            <p class="card-text"><?= htmlentities($mhs->nim); ?></p>
            <p class="card-text text-capitalize"><?= htmlentities($mhs->alamat); ?></p>
            <a href="<?= base_url('/'); ?>" class="btn btn-secondary">Kembali</a>
            <a href="<?= base_url('/mahasiswa/update/') . $mhs->id; ?>" class="btn btn-warning">Ubah</a>
        </div>
    </div>
</div>