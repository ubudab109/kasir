<table class="datatable" style="width: 100%;">
    <thead>
        <tr>
            <th width="35px">NO</th>
            <th>ID Barang</th>
            <th>Nama Barang</th>
            <th>Jumlah Beli</th>
            <th>Harga Barang</th>
            <th>Diskon/Potongan</th>
            <th>Total Harga</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody id="contenth">
        <?php
        include "root.php";
        session_start();
        $trx = date("d") . "/AF/" . $_SESSION['id'] . "/" . date("y");
        $data = $root->con->query("select barang.harga_jual, barang.nama_barang,tempo.id_subtransaksi,tempo.id_barang,tempo.jumlah_beli,tempo.total_harga from tempo inner join barang on barang.id_barang=tempo.id_barang where trx='$trx'");

        $no = 1;
        while ($f = $data->fetch_assoc()) {
        ?><tr>
                <td><?= $no++ ?></td>
                <td><?= $f['id_barang'] ?></td>
                <td><?= $f['nama_barang'] ?></td>
                <td><?= $f['jumlah_beli'] ?></td>
                <td>Rp. <?= number_format($f['harga_jual']) ?></td>
                <?php
                $dataDiskon = $root->con->query("SELECT * FROM tb_diskon WHERE id_barang = '$f[id_barang]'");
                if ($dataDiskon->num_rows > 0) {
                    $fetch = $dataDiskon->fetch_assoc();
                ?>
                    <td class="text-center"><?= $fetch['jumlah_diskon'] ?>%
                        <span>
                            <button data-toggle="modal" data-target="#editDiskon" class="btn bluetbl m-r-10" onclick="editDiskon(<?= $fetch['id_barang'] ?>, <?= $fetch['jumlah_diskon'] ?>)"><span class="btn-edit-tooltip">Edit Diskon</span><i class="fas fa-pencil-alt"></i></button>
                        </span>
                    </td>
                <?php   } else { ?>
                    <td class="text-center">
                        <?php
                        $dataPotongan = $root->con->query("SELECT * FROM tb_potongan WHERE id_barang = '$f[id_barang]'");
                        if ($dataPotongan->num_rows > 0) {
                            $fetch = $dataPotongan->fetch_assoc();
                        ?>
                            Rp. <?= number_format($fetch['jumlah_potongan']) ?>
                            <span>
                                <button data-toggle="modal" data-target="#editPotongan" class="btn bluetbl m-r-10" onclick="editPotongan(<?= $fetch['id_barang'] ?>, <?= $fetch['jumlah_potongan'] ?>)"><span class="btn-edit-tooltip">Edit Potongan</span><i class="fas fa-pencil-alt"></i></button>
                            </span>

                        <?php } else { ?>
                            <button type="button" class="" onClick="potongan(<?= $f['id_barang'] ?>)" data-toggle="modal" data-target="#exampleModal">
                                Berikan Potongan
                            </button>
                        <?php } ?>
                    </td>
                <?php } ?>


                <td>Rp. <?= number_format($f['total_harga']) ?></td>
                <td><a class="btn redtbl hapus" data-trx="<?= $f['trx'] ?>" data-sub="<?= $f['id_subtransaksi'] ?>" data-barang="<?= $f['id_barang'] ?>" data-jumlah="<?= $f['jumlah_beli'] ?>"><span class="btn-hapus-tooltip">Cancel</span><i class="fas fa-times"></i></a></td>
            </tr>
        <?php
        }
        ?>
    </tbody>

    <tr id="total">

    </tr>

</table>


<br>
<div id="form-transaksi">

</div>


<script type="text/javascript">
    function getTotalGrand() {
        $.ajax({
            url: "getGrand.php",
            method: "GET",
            success: function(res) {
                $("#total_bayar").val(res)
                // $("#totalbayar").val(res)
            }
        })
    }
    // $("prosestran").click((e) => {
    //     e.preventDefault();
    //     var namaPembeli = document.getElementById("nama_pembeli").value
    //     var email = document.getElementById("email_pembeli").value

    //     $.ajax({
    //         url: "submit_transaksi.php",
    //         method: "POST",
    //         data: {
    //             total_bayar: total,
    //             nama_pembeli: namaPembeli,
    //             email: email

    //         },
    //         success: function(data) {
    //             alert(data);
    //         },
    //         error: function(data) {
    //             alert(data);
    //         }
    //     })

    // })
    $(document).ready(function() {
        // var total = document.getElementById("grand").value
        // alert(total)
        $("#total").load("grand_total.php")
        $("#form-transaksi").load("form-trans.php")
        var grand = $("#grand").val();
        $("#total_bayar").val(grand)
        $("#totalbayar").val(grand)
        $(".hapus").click(function() {
            var idTempo = $(this).data("sub");
            var idBarang = $(this).data("barang");
            var jumlahBarang = $(this).data("jumlah");
            var grand = $("#grand").val();
            console.log(grand);
            var tr = $(this).closest('tr')
            $.ajax({
                url: "handler.php?action=hapus_tempo&id_tempo=" + idTempo + "&id_barang=" + idBarang + "&jumbel=" + jumlahBarang,
                method: "POST",
                success: function(data, res) {
                    setTimeout(function() {
                        tr.fadeOut(1000, function() {
                            tr.remove();
                        });
                        $("#total").load("grand_total.php")

                        getTotalGrand()
                    });

                }
            })
        })
    })
</script>