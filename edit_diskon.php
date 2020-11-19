<script type="text/javascript">
    document.title = "Tambah Diskon";
    document.getElementById('barang').classList.add('active');
</script>
<style>
    /* enable absolute positioning */
    .inner-addon {
        position: relative;
    }

    /* style icon */
    .inner-addon .glyphicon {
        position: absolute;
        padding: 10px;
        pointer-events: none;
    }

    /* align icon */
    .left-addon .glyphicon {
        left: 0px;
    }

    .right-addon .glyphicon {
        right: 0px;
    }

    /* add padding  */
    .left-addon input {
        padding-left: 30px;
    }

    .right-addon input {
        padding-right: 30px;
    }
</style>

<div class="content">
    <div class="padding">
        <div class="bgwhite">
            <div class="padding">
                <h3 class="jdl">Tambah Barang</h3>
                <form class="form-input" id="myform" method="post" action="handler.php?action=edit_diskon">
                    <div class="form-grop">
                        <?php $f = $root->edit_diskon($_GET['id_barang']) ?>
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
                        <label for="jumlah_diskon">Masukkan Jumlah Diskon (Kosongkan Jika Ingin Menghapus Diskon)</label>
                        <input type="text" name="jumlah_diskon" value="<?= $f['jumlah_diskon'] ?>" placeholder="Contoh : 10 atau 10.2 Tanpa Masukkan %" id="jumlah_diskon" class="form-control">
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
            jumlah_potongan: {
                number: true
            }
        },
        messages: {
            number: "Harus Berupa Angka Bulat atau Desimal"
        }
    });
</script>