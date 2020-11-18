<?php 
    include "root.php";
    session_start();
    $trx = date("d") . "/AF/" . $_SESSION['id'] . "/" . date("y/h/i/s");

    if (!empty($_POST["email"])) {
        $query = $root->con->query("INSERT INTO transaksi SET kode_kasir='$_SESSION[id]',total_bayar='$_POST[total_bayar]',no_invoice='$trx',nama_pembeli='$_POST[nama_pembeli]',email='$_POST[email]'");
    } else {
        $query = $root->con->query("INSERT INTO transaksi SET kode_kasir='$_SESSION[id]',total_bayar='$_POST[total_bayar]',no_invoice='$trx',nama_pembeli='$_POST[nama_pembeli]'");
    }

    $trx2 = date("d") . "/AF/" . $_SESSION['id'] . "/" . date("y");
    $get1 = $root->con->query("select * from transaksi where no_invoice='$trx'");
    $datatrx = $get1->fetch_assoc();
    $id_transaksi2 = $datatrx['id_transaksi'];

    $query2 = $root->con->query("select * from tempo where trx='$trx2'");
    while ($f = $query2->fetch_assoc()) {
        $root->con->query("insert into sub_transaksi set id_barang='$f[id_barang]',id_transaksi='$id_transaksi2',jumlah_beli='$f[jumlah_beli]',total_harga='$f[total_harga]',no_invoice='$trx'");
    }
    $root->con->query("delete from tempo where trx='$trx2'");
