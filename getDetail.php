<?php
include "root.php";
$idBarang = $_POST['id_barang'];
$query = $root->con->query("SELECT * FROM barang WHERE id_barang='$idBarang'");
$result = mysqli_fetch_assoc($query);
$json = json_encode($result);

echo $json;
