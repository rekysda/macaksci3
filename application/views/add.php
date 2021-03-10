<div class="col-lg-6">
    <div class="card">
        <div class="card-header">
            <?= $title; ?>
        </div>
        <div class="card-body">
            <?= form_open('/mahasiswa/add'); ?>
            <div class="mb-3 text-capitalize">
                <label for="nim" class="form-label">nim</label>
                <input type="number" class="form-control <?= (form_error('nim')) ? 'is-invalid' : ''; ?>" name="nim" id="nim" placeholder="nim" value="<?= set_value('nim'); ?>">
                <div id="validationServer03Feedback" class="invalid-feedback">
                    <?= form_error('nim'); ?>
                </div>
            </div>
            <div class="mb-3 text-capitalize">
                <label for="nama" class="form-label">nama</label>
                <input type="text" class="form-control <?= (form_error('nama')) ? 'is-invalid' : ''; ?>" name="nama" id="nama" placeholder="nama" value="<?= set_value('nama'); ?>">
                <div id="validationServer03Feedback" class="invalid-feedback">
                    <?= form_error('nama'); ?>
                </div>
            </div>
            <div class="mb-3 text-capitalize">
                <label for="email" class="form-label">email</label>
                <input type="text" class="form-control <?= (form_error('email')) ? 'is-invalid' : ''; ?>" name="email" id="email" placeholder="email" value="<?= set_value('email'); ?>">
                <div id="validationServer03Feedback" class="invalid-feedback">
                    <?= form_error('email'); ?>
                </div>
            </div>
            <div class="mb-3 text-capitalize">
                <label for="alamat" class="form-label">alamat</label>
                <input type="text" class="form-control <?= (form_error('alamat')) ? 'is-invalid' : ''; ?>" name="alamat" id="alamat" placeholder="alamat" value="<?= set_value('alamat'); ?>">
                <div id="validationServer03Feedback" class="invalid-feedback">
                    <?= form_error('alamat'); ?>
                </div>
            </div>
            <a href="<?= base_url(); ?>" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
    </div>
</div>