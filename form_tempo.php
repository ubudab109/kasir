<h3 class="jdl">Entry Transaksi Baru</h3>



<table class="table">
    <thead class="thead-inverse">
        <tr>
            <th>Pilih Barang</th>
            <!-- <th>Stok Barang</th>
							<th>Harga</th> -->
            <th>Jumlah Beli</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <form class="form-inline" id="subt" method="post" style="padding-top: 30px;">

            <tr class="no-border">
                <td>
                    <div class="input-group">
                        <input type="hidden" class="form-control mb-2 mr-sm-2" id="stock" readonly disabled>
                        <input type="hidden" class="form-control mb-2 mr-sm-2" id="harga_jual" readonly disabled>
                        <select style="width: 372px;cursor: pointer;" required="required" class="chosen" id="id_barang" name="id_barang">
                            <option value="" selected disabled>Pilih Barang </option>
                            <?php
                            include 'root.php';
                            session_start();
                            $data = $root->con->query("select * from barang");
                            while ($f = $data->fetch_assoc()) {
                                echo "<option value='$f[id_barang]' data-img='images/produk/$f[foto_produk]'>$f[nama_barang] (stock : $f[stok] | Harga : " . number_format($f['harga_jual']) . ")</option>";
                            }
                            ?>
                        </select>
                        <p class="text-danger" id="err_nama"></p>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <input required="required" min="0" max="100" class="form-control mb-2 mr-sm-2" id="jumlah_beli" type="number" name="jumlah">
                        <p class="text-danger" id="err_jumlah"></p>
                        <input type="hidden" class="form-control mb-2 mr-sm-2" id="trx" name="trx" value="<?php echo date("d") . "/AF/" . $_SESSION['id'] . "/" . date("y") ?>">
                    </div>
                </td>
                <td>
                    <button class="btnblue" type="submit" id="simpan"><i class="fa fa-save"></i> Simpan</button>
                </td>
            </tr>
        </form>

    </tbody>
</table>
<div class="gambar">
    <img src="images/produk/" id="gambar" alt="" style="display: block; margin-left: auto; margin-right: auto; width: 20%;">
</div>
<script>
    $('.chosen').chosen({
        width: '80%',
        height: '40%',
        allow_single_deselect: true
    });

    $(document).ready(function() {
        $('#id_barang').change((e) => {
            e.preventDefault();
            var selected = $(this).find('option:selected');
            var extra = selected.data('img');
            console.log(extra);
            $('#gambar').attr('src', extra);
        })
    })
</script>