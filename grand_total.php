<?php
include "root.php";
session_start();
$trx = date("d") . "/AF/" . $_SESSION['id'] . "/" . date("y");
$getsum = $root->con->query("select sum(total_harga) as grand_total from tempo where trx='$trx'");
$getsum1 = $getsum->fetch_assoc();
if ($getsum1['grand_total'] > 0) { ?>
    <td colspan="3"></td>
    <td>Grand Total :</td>
    <input type="hidden" id="grand" value="<?= $getsum1['grand_total'] ?>">
    <td> Rp. <?= number_format($getsum1['grand_total']) ?></td>
    <td></td>
<?php } else { ?>
    <td colspan="6">Data masih kosong</td>
<?php } ?>