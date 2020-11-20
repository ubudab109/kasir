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
			$root->alert("Harap Isi Foto Dengan Format harus JPEG, JPG atau PNG");
			$root->go_back();
		} else {
			if ($ukuran < 2044070) {
				move_uploaded_file($_FILES['foto']['tmp_name'], 'images/produk/' . $file);
				$root->tambah_barang($_POST['nama_barang'], $_POST['stok'], $_POST['harga_beli'], $_POST['harga_jual'], $_POST['kategori'], $file);
			} else {
				$root->alert("Maksimal Foto : 2 MegaByte");
				$root->go_back();
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
		$rand = rand();
		$ekstensi = array('png', 'jpg', 'jpeg', 'gif');
		$filename = $_FILES['foto']['name'];
		$ukuran = $_FILES['foto']['size'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$file = $rand . '-' . $filename;
		if (is_uploaded_file($_FILES['foto']['tmp_name'])) {
			if (!in_array($ext, $ekstensi)) {
				$this->alert("Format harus JPEG, JPG atau PNG");
				$this->go_back();
			} else {
				if ($ukuran < 2044070) {
					move_uploaded_file($_FILES['foto']['tmp_name'], 'images/produk/' . $file);
					$root->aksi_edit_barang($_POST['id_barang'], $_POST['nama_barang'], $_POST['stok'], $_POST['harga_beli'], $_POST['harga_jual'], $_POST['kategori'], $file);
				} else {
					$this->alert("Maksimal Foto : 2 MegaByte");
					$this->go_back();
				}
			}
		} else {
			$root->aksi_edit_barang($_POST['id_barang'], $_POST['nama_barang'], $_POST['stok'], $_POST['harga_beli'], $_POST['harga_jual'], $_POST['kategori'], $_POST['foto_lama']);
		}
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

		if (!empty($_POST["email"]) || !empty($_POST['nama_pembeli'])) {
			$query = $root->con->query("INSERT INTO transaksi SET kode_kasir='$_SESSION[id]',total_bayar='$_POST[total_bayar]',no_invoice='$trx',nama_pembeli='$_POST[nama_pembeli]',email='$_POST[email]'");
		} else {
			$query = $root->con->query("INSERT INTO transaksi SET kode_kasir='$_SESSION[id]',total_bayar='$_POST[total_bayar]',no_invoice='$trx',nama_pembeli='-', email='-'");
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
			$barang = $root->con->query("SELECT * FROM barang WHERE id_barang='$_POST[id_barang]'");
			$barangGet = $barang->fetch_assoc();
			$hargaBarang = $barangGet['harga_jual'];
			$queryTempo = $root->con->query("UPDATE tempo SET total_harga=jumlah_beli*'$hargaBarang' WHERE id_barang='$_POST[id_barang]'");
		} else {
			$query = $root->con->query("UPDATE tb_diskon SET jumlah_diskon='$_POST[jumlah_diskon]' WHERE id_barang='$_POST[id_barang]'");
			$barang = $root->con->query("SELECT * FROM barang WHERE id_barang='$_POST[id_barang]'");
			$barangGet = $barang->fetch_assoc();
			$hargaBarang = $barangGet['harga_jual'];

			// masih kurang disini
			$quTemp = $root->con->query("SELECT * FROM tempo WHERE id_barang='$_POST[id_barang]'");
			$getTemp = $quTemp->fetch_assoc();
			$getJumlah = $getTemp['jumlah_beli'];
			$persen = $_POST['jumlah_diskon'] / 100;
			$subTotal = ($getJumlah * $hargaBarang) * $persen;
			$grandTotal = ($getJumlah * $hargaBarang) - $subTotal;

			$queryTempo = $root->con->query("UPDATE tempo SET total_harga='$grandTotal' WHERE id_barang='$_POST[id_barang]'");
		}


		if ($query === TRUE && $queryTempo === TRUE) {
			$root->alert("Diskon Berhasil Diubah");
			$root->redirect("barang.php");
		} else {
			$root->alert("Terjadi Kesalahan");
			$root->go_back();
		}
	}
	if ($action == "edit_diskon_kasir") {
		if (empty($_POST['jumlah_diskon'])) {
			$query = $root->con->query("DELETE FROM tb_diskon WHERE id_barang='$_POST[id_barang]'");
			$barang = $root->con->query("SELECT * FROM barang WHERE id_barang='$_POST[id_barang]'");
			$barangGet = $barang->fetch_assoc();
			$hargaBarang = $barangGet['harga_jual'];
			$queryTempo = $root->con->query("UPDATE tempo SET total_harga=jumlah_beli*'$hargaBarang', potongan=NULL, jenis_potongan=NULL WHERE id_barang='$_POST[id_barang]'");
		} else {
			$query = $root->con->query("UPDATE tb_diskon SET jumlah_diskon='$_POST[jumlah_diskon]' WHERE id_barang='$_POST[id_barang]'");
			$barang = $root->con->query("SELECT * FROM barang WHERE id_barang='$_POST[id_barang]'");
			$barangGet = $barang->fetch_assoc();
			$hargaBarang = $barangGet['harga_jual'];

			$quTemp = $root->con->query("SELECT * FROM tempo WHERE id_barang='$_POST[id_barang]'");
			$getTemp = $quTemp->fetch_assoc();
			$getJumlah = $getTemp['jumlah_beli'];
			$persen = $_POST['jumlah_diskon'] / 100;
			$subTotal = ($getJumlah * $hargaBarang) * $persen;
			$grandTotal = ($getJumlah * $hargaBarang) - $subTotal;

			$queryTempo = $root->con->query("UPDATE tempo SET total_harga='$grandTotal',potongan='$_POST[jumlah_diskon]' WHERE id_barang='$_POST[id_barang]'");
		}


		if ($query === TRUE && $queryTempo === TRUE) {
			$hasil = 0;
		} else {
			$hasil = 1;
		}

		echo $hasil;
	}
	if ($action == "tambah_potongan") {
		$root->tambah_potongan($_POST['id_barang'], $_POST['jumlah_potongan']);
	}
	if ($action == "edit_potongan") {
		if (empty($_POST['jumlah_potongan'])) {
			$query = $root->con->query("DELETE FROM tb_potongan WHERE id_barang='$_POST[id_barang]'");
			$barang = $root->con->query("SELECT * FROM barang WHERE id_barang='$_POST[id_barang]'");
			$barangGet = $barang->fetch_assoc();
			$hargaBarang = $barangGet['harga_jual'];
			$queryTempo = $root->con->query("UPDATE tempo SET total_harga=jumlah_beli*'$hargaBarang' WHERE id_barang='$_POST[id_barang]'");
		} else {
			$query = $root->con->query("UPDATE tb_potongan SET jumlah_potongan='$_POST[jumlah_potongan]' WHERE id_barang='$_POST[id_barang]'");
			$barang = $root->con->query("SELECT * FROM barang WHERE id_barang='$_POST[id_barang]'");
			$barangGet = $barang->fetch_assoc();
			$hargaBarang = $barangGet['harga_jual'];

			// masih kurang disini
			$quTemp = $root->con->query("SELECT * FROM tempo WHERE id_barang='$_POST[id_barang]'");
			$getTemp = $quTemp->fetch_assoc();
			$getJumlah = $getTemp['jumlah_beli'];
			$subTotal = ($getJumlah * $hargaBarang) - $_POST['jumlah_potongan'];

			$queryTempo = $root->con->query("UPDATE tempo SET total_harga='$subTotal' WHERE id_barang='$_POST[id_barang]'");
		}

		if ($query === TRUE && $queryTempo === TRUE) {
			$root->alert("Potongan Berhasil Diubah");
			$root->redirect("barang.php");
		} else {
			$root->alert("Terjadi Kesalahan");
			$root->go_back();
		}
	}
	if ($action == "edit_potongan_kasir") {
		if (empty($_POST['jumlah_potongan'])) {
			$query = $root->con->query("DELETE FROM tb_potongan WHERE id_barang='$_POST[id_barang]'");
			$barang = $root->con->query("SELECT * FROM barang WHERE id_barang='$_POST[id_barang]'");
			$barangGet = $barang->fetch_assoc();
			$hargaBarang = $barangGet['harga_jual'];
			$queryTempo = $root->con->query("UPDATE tempo SET total_harga=jumlah_beli*'$hargaBarang', potongan=NULL, jenis_potongan=NULL WHERE id_barang='$_POST[id_barang]'");
		} else {
			$query = $root->con->query("UPDATE tb_potongan SET jumlah_potongan='$_POST[jumlah_potongan]' WHERE id_barang='$_POST[id_barang]'");
			$barang = $root->con->query("SELECT * FROM barang WHERE id_barang='$_POST[id_barang]'");
			$barangGet = $barang->fetch_assoc();
			$hargaBarang = $barangGet['harga_jual'];

			// masih kurang disini
			$quTemp = $root->con->query("SELECT * FROM tempo WHERE id_barang='$_POST[id_barang]'");
			$getTemp = $quTemp->fetch_assoc();
			$getJumlah = $getTemp['jumlah_beli'];
			$subTotal = ($getJumlah * $hargaBarang) - $_POST['jumlah_potongan'];

			$queryTempo = $root->con->query("UPDATE tempo SET total_harga='$subTotal' WHERE id_barang='$_POST[id_barang]'");
		}

		if ($query === TRUE && $queryTempo === TRUE) {
			$hasil = 0;
		} else {
			$hasil = 1;
		}

		echo $hasil;
	}
	if ($action == "tambah_diskon_kasir") {
		$root->tambah_diskon_kasir($_POST['id_barang'], $_POST['jumlah_diskon']);
	}
	if ($action == "tambah_potongan_kasir") {
		$root->tambah_potongan_kasir($_POST['id_barang'], $_POST['jumlah_potongan']);
	}
	// validasi jumlah diskon
	if ($action == "validasi_diskon") {
		$jumlahDiskon = $_POST['jumlah_diskon'];
		if ($jumlahDiskon > 100) {
			$hasil = 0;
		} else {
			$hasil = 1;
		}

		echo $hasil;
	}
} else {
	echo "no direct script are allowed";
}
