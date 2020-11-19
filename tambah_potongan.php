<script type="text/javascript">
    document.title = "Tambah Diskon";
    document.getElementById('barang').classList.add('active');
</script>
<div class="content">
    <div class="padding">
        <div class="bgwhite">
            <div class="padding">
                <h3 class="jdl">Tambah Potongan Barang</h3>
                <form class="form-input" id="myform" method="post" action="handler.php?action=tambah_potongan">
                    <div class="form-grop">
                        <label for="id_barang"> Pilih Barang :</label>
                        <br>
                        <select style="width: 372px;cursor: pointer;" required="required" class="form-control chosen" id="id_barang" name="id_barang">
                            <?php

                            $data = $root->con->query("select * from barang");
                            while ($f = $data->fetch_assoc()) {
                                echo "<option value='$f[id_barang]'>$f[nama_barang] (stock : $f[stok] | Harga : " . number_format($f['harga_jual']) . ")</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <br>
                    <!-- <div class="form-group"> -->
                    <div class="inner-addon right-addon">
                        <!-- <i class="glyphicon glyphicon-user"></i>
                            <input type="text" class="form-control" /> -->
                        <label for="jumlah_potongan">Masukkan Jumlah Potongan</label>
                        <input type="number" name="jumlah_potongan" placeholder="Contoh : 10000 atau 25000" id="jumlah_potongan" class="form-control">
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
    $('.chosen').chosen({
        width: '80%',
        height: '40%',
        allow_single_deselect: true
    });

    $("#myform").validate({
        rules: {
            jumlah_diskon: {
                required: true,
                number: true
            }
        },
        messages: {
            required: "Jumlah Diskon Harus Diisi",
            number: "Harus Berupa Angka Bulat atau Desimal"
        }
    });
</script>