<script type="text/javascript">
    document.title = "Tambah Diskon";
    document.getElementById('barang').classList.add('active');
</script>
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<script src="assets/bootstrap/js/bootstrap.min.js"></script>

<div class="content">
    <div class="padding">
        <div class="bgwhite">
            <div class="padding">
                <h3 class="jdl">Tambah Barang</h3>
                <form id="myform" method="post" action="handler.php?action=edit_diskon">
                    <div class="form-grop">
                        <?php $f = $root->edit_diskon($_GET['id_barang']) ?>
                        <?php $barang = $root->con->query("SELECT nama_barang FROM barang WHERE id_barang = '$_GET[id_barang]'");
                        $data = $barang->fetch_assoc();
                        ?>
                        <label for="id_barang"> Nama Barang :</label>
                        <br>
                        <input type="text" id="nama_barang" value="<?= $data['nama_barang'] ?>" class="form-control" readonly disabled>
                        <input type="hidden" name="id_barang" id="id_barang" readonly value="<?= $f['id_barang'] ?>">

                    </div>
                    <br>
                    <div class="form-group">
                        <label for="jumlah_diskon">Masukkan Jumlah Diskon (Kosongkan Jika Ingin Menghapus Diskon)</label>

                        <div class="input-group mb-2">
                            <input type="number" name="jumlah_diskon" min="0" max="100" value="<?= $f['jumlah_diskon'] ?>" step="0.01" placeholder="Contoh : 10 atau 20 Tanpa Masukkan %" id="jumlah_diskon" class="form-control">
                            <div class="input-group-prepend">
                                <div class="input-group-text">%</div>
                            </div>
                        </div>
                        <p class="text-danger" id="err_jumlah_diskon"></p>
                    </div>
                    <button class="btn btn-primary" type="submit" id="submit"><i class="fa fa-save"></i> Simpan</button>
                    <a href="barang.php" class="btn btn-danger"><i class="fas fa-times"></i> Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(() => {
        // validasi jumlah diskon
        $("#jumlah_diskon").keyup((e) => {
            e.preventDefault();
            var jumlahDiskon = $("#jumlah_diskon").val();
            $.ajax({
                url: "handler.php?action=validasi_diskon",
                type: "POST",
                data: {
                    jumlah_diskon: jumlahDiskon
                },
                success: function(res) {
                    if (res == 0) {
                        document.getElementById("err_jumlah_diskon").innerHTML = "Diskon Tidak Boleh Melebihi 100%";
                        $("#submit").prop('disabled', true);
                    } else {
                        $("#submit").prop('disabled', false);
                        document.getElementById("err_jumlah_diskon").innerHTML = "";
                    }
                }
            })
        })
    })
</script>