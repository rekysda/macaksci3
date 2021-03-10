<div class="col-lg-6">
    <div class="card">
        <div class="card-header"><?= $title; ?></div>
        <div class="card-body">
            <form action="<?= base_url('mahasiswa/responseBot'); ?>" method="post">
                <div class="mb-3">
                    <label for="pesan" class="form-label">Pesan</label>
                    <input type="text" class="form-control" id="pesan" aria-describedby="pesanHelp" name="pesan">
                    <div id="pesanHelp" class="form-text">Respon bot</div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>