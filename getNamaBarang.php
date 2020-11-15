<?php
include "root.php";

$query = $root->getNamaBarang();
// $result = mysqli_fetch_array($query);
// $json = json_encode($result);

echo $query;
