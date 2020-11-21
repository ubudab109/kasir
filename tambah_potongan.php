<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript">
    document.title = "Tambah Diskon";
    document.getElementById('barang').classList.add('active');
</script>
<div class="content">
    <div class="padding">
        <div class="bgwhite">
            <div class="padding">
                <h3 class="jdl">Tambah Potongan Barang</h3>
                <form id="myform" method="post" action="handler.php?action=tambah_potongan">
                    <div class="form-grop">
                        <label for="id_barang"> Pilih Barang :</label>
                        <br>
                        <select style="width: 372px;cursor: pointer;" required="required" class="form-control chosen" id="id_barang" name="id_barang">
                            <option value="">Pilih Barang:</option>
                            <?php

                            $data = $root->con->query("select * from barang");
                            while ($f = $data->fetch_assoc()) {
                                echo "<option value='$f[id_barang]'>$f[nama_barang] (stock : $f[stok] | Harga : " . number_format($f['harga_jual']) . ")</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="jumlah_potongan">Jumlah Potongan Dalam Rupiah :</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Rp. </div>
                            </div>
                            <input type="number" name="jumlah_potongan" placeholder="Contoh : 10000 atau 25000" id="jumlah_potongan" class="form-control">
                        </div>
                    </div>

                    <div id="error_barang"></div>
                    <div id="error_potongan"></div>
                    <button class="btn btn-primary" type="submit" id="simpan"><i class="fa fa-save"></i> Simpan</button>
                    <a href="barang.php" class="btn btn-danger" style="background: #f33155"><i class="fas fa-times"></i> Batal</a>
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
    $("#simpan").hide();

    $("#jumlah_potongan").keyup((e) => {
        e.preventDefault();
        var jumlah = $("#jumlah_potongan").val();
        var idBarang = $("#id_barang").find(":selected").val()
        // console.log(idBarang);
        if (jumlah == "") {
            $("#simpan").hide();

        } else {
            if (idBarang == "") {
                $("#simpan").prop("disable", true);
                document.getElementById("error_barang").innerHTML = "<p style='color:red'>Harap Pilih Barang Terlebih Dahulu</p>";
                // $("#error_barang").append(p);
            } else {
                // $("#error_barang").inner;
                document.getElementById("error_barang").innerHTML = "";
                $.ajax({
                    url: "potongan_validate.php?id_barang=" + idBarang,
                    type: "POST",
                    data: {
                        jumlah_potongan: jumlah
                    },
                    success: function(res) {
                        if (res == 0) {
                            document.getElementById("error_potongan").innerHTML = "<p style='color:red'>Potongan Harga Tidak Boleh Melebihi Harga Barang</p>";
                            $("#simpan").hide();
                        } else {
                            document.getElementById("error_potongan").innerHTML = "";

                            $("#simpan").show();
                        }
                    }

                })
            }
        }

    })
</script>