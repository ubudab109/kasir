<script type="text/javascript">
    document.title = "Tambah Diskon";
    document.getElementById('barang').classList.add('active');
</script>
<div class="content">
    <div class="padding">
        <div class="bgwhite">
            <div class="padding">
                <h3 class="jdl">Tambah Barang</h3>
                <form class="form-input" id="myform" method="post" action="handler.php?action=edit_potongan">
                    <div class="form-grop">
                        <?php $f = $root->edit_potongan($_GET['id_barang']) ?>
                        <?php $barang = $root->con->query("SELECT nama_barang FROM barang WHERE id_barang = '$_GET[id_barang]'");
                        $data = $barang->fetch_assoc();
                        ?>
                        <label for="id_barang"> Nama Barang :</label>
                        <br>
                        <input type="text" id="nama_barang" value="<?= $data['nama_barang'] ?>" readonly disabled>
                        <input type="hidden" name="id_barang" id="id_barang" readonly value="<?= $f['id_barang'] ?>">
                    </div>
                    <br>
                    <!-- <div class="form-group"> -->
                    <div class="inner-addon right-addon">
                        <!-- <i class="glyphicon glyphicon-user"></i>
                            <input type="text" class="form-control" /> -->
                        <label for="jumlah_potongan">Masukkan Jumlah Potongan (Kosongkan Jika Ingin Menghapus Potongan)</label>
                        <input type="text" name="jumlah_potongan" value="<?= $f['jumlah_potongan'] ?>" placeholder="Contoh : 10 atau 10.2 Tanpa Masukkan %" id="jumlah_potongan" class="form-control">
                        <!-- <i class="fas fa-percentage"></i> -->
                        <!-- </div> -->



                    </div>
                    <button class="btnblue" type="submit"><i class="fa fa-save"></i> Simpan</button>
                    <a href="barang.php" class="btnblue" style="background: #f33155"><i class="fas fa-times"></i> Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $("#myform").validate({
        rules: {
            jumlah_diskon: {
                number: true
            }
        },
        messages: {
            number: "Harus Berupa Angka Bulat atau Desimal"
        }
    });
</script>