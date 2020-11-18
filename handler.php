<?php
include "root.php";

if (isset($_GET['action'])) {
	$action = $_GET['action'];
	if ($action == "login") {
		$root->login($_POST['username'], $_POST['pass'], $_POST['loginas']);
	}
	if ($action == "logout") {
		session_start();
		session_destroy();
		$root->redirect("index.php");
	}
	if ($action == "tambah_barang") {
		$rand = rand();
		$ekstensi = array('png', 'jpg', 'jpeg', 'gif');
		$filename = $_FILES['foto']['name'];
		$ukuran = $_FILES['foto']['size'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$file = $rand . '-' . $filename;
		if (!in_array($ext, $ekstensi)) {
			$this->alert("Format harus JPEG, JPG atau PNG");
			$this->go_back();
		} else {
			if ($ukuran < 2044070) {
				move_uploaded_file($_FILES['foto']['tmp_name'], 'images/produk/' . $file);
				$root->tambah_barang($_POST['nama_barang'], $_POST['stok'], $_POST['harga_beli'], $_POST['harga_jual'], $_POST['kategori'], $file);
			} else {
				$this->alert("Maksimal Foto : 2 MegaByte");
				$this->go_back();
			}
		}
	}
	if ($action == "tambah_kategori") {
		$root->tambah_kategori($_POST['nama_kategori']);
	}
	if ($action == "hapus_kategori") {
		$root->hapus_kategori($_GET['id_kategori']);
	}
	if ($action == "edit_kategori") {
		$root->aksi_edit_kategori($_POST['id_kategori'], $_POST['nama_kategori']);
	}
	if ($action == "hapus_barang") {
		$getFoto = $root->con->query("SELECT * FROM barang WHERE id_barang = '$_GET[id_barang]'");
		// $foto = $getFoto->fetch_assoc();
		while ($data = $getFoto->fetch_assoc()) {
			$foto_produk = 'images/produk/' . $data['foto_produk'];
			unlink($foto_produk);
		}

		$root->hapus_barang($_GET['id_barang']);
	}
	if ($action == "edit_barang") {
		$root->aksi_edit_barang($_POST['id_barang'], $_POST['nama_barang'], $_POST['stok'], $_POST['harga_beli'], $_POST['harga_jual'], $_POST['kategori']);
	}
	if ($action == "tambah_kasir") {
		$root->tambah_kasir($_POST['nama_kasir'], $_POST['password']);
	}
	if ($action == "hapus_user") {
		$root->hapus_user($_GET['id_user']);
	}
	if ($action == "edit_kasir") {
		$root->aksi_edit_kasir($_POST['nama_kasir'], $_POST['password'], $_POST['id']);
	}
	if ($action == "edit_admin") {
		$root->aksi_edit_admin($_POST['username'], $_POST['password']);
	}
	if ($action == "reset_admin") {
		$pass = sha1("admin");
		$q = $root->con->query("update user set username='admin',password='$pass',date_created=date_created where id='1'");
		if ($q === TRUE) {
			$root->alert("admin berhasil direset, username & password = 'admin'");
			session_start();
			session_destroy();
			$root->redirect("index.php");
		}
	}
	if ($action == "tambah_tempo") {
		$root->tambah_tempo($_POST['id_barang'], $_POST['jumlah'], $_POST['trx']);
	}
	if ($action == "hapus_tempo") {
		$root->hapus_tempo($_GET['id_tempo'], $_GET['id_barang'], $_GET['jumbel']);
	}
	if ($action == "selesai_transaksi") {
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
		// var_dump($query);
		$root->alert("Transaksi berhasil");
		$root->redirect("transaksi.php");
	}
	if ($action == "delete_transaksi") {
		$q1 = $root->con->query("delete from transaksi where id_transaksi='$_GET[id]'");
		$q2 = $root->con->query("delete from sub_transaksi where id_transaksi='$_GET[id]'");
		if ($q1 === TRUE && $q2 === TRUE) {
			$root->alert("Transaksi No $_GET[id] Berhasil Dihapus");
			$root->redirect("laporan.php");
		}
	}
	if ($action == "tambah_diskon") {
		$root->tambah_diskon($_POST['id_barang'], $_POST['jumlah_diskon']);
	}
	if ($action == "edit_diskon") {
		if (empty($_POST['jumlah_diskon'])) {
			$query = $root->con->query("DELETE FROM tb_diskon WHERE id_barang='$_POST[id_barang]'");
		} else {
			$query = $root->con->query("UPDATE tb_diskon SET jumlah_diskon='$_POST[jumlah_diskon]' WHERE id_barang='$_POST[id_barang]'");
		}

		if ($query === TRUE) {
			$root->alert("Diskon Berhasil Diubah");
			$root->redirect("barang.php");
		} else {
			$root->alert("Terjadi Kesalahan");
			$root->go_back();
		}
	}
} else {
	echo "no direct script are allowed";
}
