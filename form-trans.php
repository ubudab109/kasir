<?php
include "root.php";
session_start();
$trx = date("d") . "/AF/" . $_SESSION['id'] . "/" . date("y");
$getsum = $root->con->query("select sum(total_harga) as grand_total from tempo where trx='$trx'");
$getsum1 = $getsum->fetch_assoc();
?>
<form class="form-input" action="handler.php?action=selesai_transaksi" method="post">
    <label>Nama Pembeli :</label>
    <input type="text" id="nama_pembeli" name="nama_pembeli">
    <input type="hidden" id="total_bayar" name="total_bayar" value="<?= $getsum1['grand_total'] ?>">
    <label>Nomor HP :</label>
    <input type="number" name="email" id="email_pembeli">
    <button class="btnblue" id="prosestran" type="submit"><i class="fa fa-save"></i> Proses Transaksi</button>
</form>