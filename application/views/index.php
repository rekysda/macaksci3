<div class="col-lg-12">
    <?= $this->session->flashdata('success'); ?>
    <ul class="list-group">
        <?php if ($mahasiswa == null) { ?>
            <h3><span class="fw-normal">Anda mencari :</span> <?= $k; ?></h3>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Maaf!</strong> Data yang anda cari tidak ditemukan..
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>
        <?php if ($this->input->get('k') && $mahasiswa) { ?>
            <h3><span class="fw-normal">Anda mencari :</span> <?= $k; ?></h3>
        <?php } ?>
    </ul>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama</th>
                <th scope="col">NIM</th>
                <th scope="col">Alamat</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <!-- <?= $k; ?> -->
        <?php $no = $this->uri->segment(2);
        foreach ($mahasiswa as $mhs => $m) { ?>
            <tbody>
                <tr>
                    <th scope="row"><?= ++$no; ?></th>
                    <?php if ($this->input->get('k')) { ?>
                        <td class="text-capitalize"><?= highlight_phrase(htmlentities($m->nama), $this->input->get('k'), '<b class="bg-warning">', '</b>'); ?></td>
                        <td><?= highlight_phrase(htmlentities($m->nim), $this->input->get('k'), '<b class="bg-warning">', '</b>'); ?></td>
                        <td class="text-capitalize"><?= highlight_phrase(htmlentities($m->alamat), $this->input->get('k'), '<b class="bg-warning">', '</b>'); ?></td>
                    <?php } ?>
                    <?php if ($this->input->get('k') == null) { ?>
                        <td class="text-capitalize"><?= htmlentities($m->nama); ?></td>
                        <td><?= htmlentities($m->nim); ?></td>
                        <td class="text-capitalize"><?= htmlentities($m->alamat); ?></td>
                    <?php } ?>
                    <td>
                        <a href="<?= base_url('mahasiswa/') . $m->id; ?>" class="btn btn-success btn-sm me-2">Detail</a>
                        <a href="<?= base_url('ubah/') . $m->id; ?>" class="btn btn-warning btn-sm me-2">Ubah</a>
                        <a href="<?= base_url('hapus/') . $m->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('hapus data <?= $m->nama; ?>?')">Hapus</a>
                    </td>
                </tr>
            </tbody>
        <?php } ?>
    </table>
    <a href="<?= base_url('tambah-mahasiswa'); ?>" class="btn btn-outline-primary float-start">Tambah mahasiswa</a>
    <?= $this->pagination->create_links(); ?>
</div>