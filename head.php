<?php
include "root.php";
session_start();
if (!isset($_SESSION['username'])) {
	$root->redirect("index.php");
}
?>
<!DOCTYPE html>
<html>

<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="assets/index.css">
	<link rel="stylesheet" type="text/css" href="assets/awesome/css/font-awesome.min.css">
	<!-- <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css"> -->
	<link rel="stylesheet" href="assets/choosen/chosen.min.css">
	<link rel="stylesheet" href="assets/jquery-ui/jquery-ui.css">
	<link rel="stylesheet" href="assets/font-awesom.min.css" />
	<link rel="stylesheet" href="assets/all.min.css" />
	<script type="text/javascript" src="assets/jquery-ui/external/jquery/jquery.js"></script>
	<script type="text/javascript" src="assets/font-awesome.min.js"></script>
	<script type="text/javascript" src="assets/choosen/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="assets/choosen/chosen.proto.min.js"></script>
	<script type="text/javascript" src="assets/jquery-ui/jquery-ui.js"></script>
</head>

<body>

	<div class="sidebar">
		<h3><i class="fa fa fa-shopping-cart"></i> Traditional Food</h3>
		<ul><?php
			if ($_SESSION['status'] == 1) {
			?>
				<li class="admin-info">
					<img src="assets/img/cat.jpg">
					<span><?php echo $_SESSION['username']; ?></span>
				</li>
				<li><a id="dash" href="home.php"><i class="fa fa-home"></i> Dashboard</a></li>
				<li><a id="barang" href="barang.php"><i class="fa fa-bars"></i> Barang</a></li>
				<li><a id="kategori" href="kategori.php"><i class="fa fa-tags"></i> Kategori Barang</a></li>
				<li><a id="users" href="users.php"><i class="fa fa-users"></i> Kasir</a></li>
				<li><a id="laporan" href="laporan.php"><i class="fa fa-book"></i> Laporan</a></li>
				<?php
				if ($_SESSION['status'] == 1) {
				?>
					<li><a href="setting_akun.php"><i class="fa fa-cog"></i> Pengaturan Akun</a></li>
				<?php } ?>
				<li><a href="handler.php?action=logout"><i class="fa fa-sign-out"></i> Logout</a></li>
			<?php
			} else {
			?>
				<li><a id="transaksi" href="transaksi.php"><i class="fa fa-money"></i> Transaksi</a></li>
				<li><a id="laporan-transaksi" href="#"><i class="fa fa-money"></i> Laporan Transaksi</a></li>
				<?php
				if ($_SESSION['status'] == 1) {
				?>
					<li><a href="setting_akun.php"><i class="fa fa-cog"></i> Pengaturan Akun</a></li>
				<?php } ?>
				<li><a href="handler.php?action=logout"><i class="fa fa-sign-out"></i> Logout</a></li>
			<?php
			}
			?>
		</ul>
	</div>
	<div class="nav">
		<ul>
			<li><a href=""><i class="fa fa-user"></i> <?= $_SESSION['username'] ?></a>
				<ul>

				</ul>
			</li>
		</ul>
	</div>