<?php
include 'root.php';
session_start();

$idBarang = $_GET['id_barang'];
$jumlahPotongan = $_POST['jumlah_potongan'];

$queryBarang = $root->con->query("SELECT * FROM barang WHERE id_barang='$idBarang'");
$dataBarang = $queryBarang->fetch_assoc();
$hargaBarang = $dataBarang['harga_jual'];

// $queryPotongan = $root->con->query("SELECT * FROM tb_potongan WHERE id_barang='$idBarang'")
if ($jumlahPotongan > $hargaBarang) {
    $hasil = 0;
} else {
    $hasil = 1;
}

echo $hasil;
