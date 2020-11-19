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
                <h3 class="jdl">Tambah Diskon Barang</h3>
                <form id="myform" method="post" action="handler.php?action=tambah_diskon">
                    <div class="form-grop">
                        <label for="id_barang"> Pilih Barang :</label>
                        <br>
                        <select style="width: 372px;cursor: pointer;" required="required" onchange="selected()" class="form-control chosen" id="id_barang" name="id_barang">
                            <option value="" selected>Pilih Barang</option>
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
                        <label for="jumlah_diskon">Masukkan Jumlah Diskon</label>
                        <div class="input-group mb-2">
                            <input type="number" name="jumlah_diskon" min="0" max="100" step="0.01" placeholder="Contoh : 10 atau 20 Tanpa Masukkan %" id="jumlah_diskon" class="form-control">
                            <div class="input-group-prepend">
                                <div class="input-group-text">%</div>
                            </div>
                        </div>
                        <p class="text-danger" id="err_jumlah_diskon"></p>
                    </div>

                    <button class="btn btn-primary" type="submit" id="submit"><i class="fa fa-save"></i> Simpan</button>
                    <a href="barang.php" class="btn btn-primary" style="background: #f33155"><i class="fas fa-times"></i> Batal</a>
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

    $(document).ready(() => {
        // validasi dropdown
        $("#submit").prop('disabled', true);

        selected();
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

    function selected() {

        var id = $("#id_barang").find(":selected").val();
        if (id == "") {
            $("#submit").prop('disabled', true);
        } else {
            $("#submit").prop('disabled', false);
        }
    }
</script>