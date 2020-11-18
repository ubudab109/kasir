<?php
include "root.php";
session_start();
$trx = date("d") . "/AF/" . $_SESSION['id'] . "/" . date("y");
$getsum = $root->con->query("select sum(total_harga) as grand_total from tempo where trx='$trx'");
$fetch = $getsum->fetch_assoc();
// $getsum1 = json_encode($fetch);
// var_dump($trx);
if ($fetch["grand_total"] > 0) {
    $hasil = $fetch['grand_total'];
    echo $hasil;
} else {
    $hasil = null;
    echo $hasil;
}


// echo $getsum1;
