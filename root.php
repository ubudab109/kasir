<?php
// coded by https://www.athoul.site
error_reporting(1);
class penjualan
{

	public $con;
	function __construct()
	{
		$this->con = new mysqli("localhost", "root", "", "tekno");
	}
	function __destruct()
	{
		$this->con->close();
	}
	function alert($text)
	{
?><script type="text/javascript">
			alert("<?= $text ?>");
		</script>
	<?php
	}
	// coded by https://www.athoul.site
	function redirect($url)
	{
	?>
		<script type="text/javascript">
			window.location.href = "<?= $url ?>";
		</script>
	<?php
	}
	function go_back()
	{
	?>
		<script type="text/javascript">
			window.history.back();
		</script>
		<?php
	}
	function base_url()
	{
		return sprintf(
			"%s://%s%s",
			isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
			$_SERVER['SERVER_NAME'],
			$_SERVER['REQUEST_URI']
		);
	}
	function login($username, $password, $loginas)
	{
		if (trim($username) == "") {
			$error[] = "Username";
		}
		if (trim($password) == "") {
			$error[] = "Password";
		}
		if (isset($error)) {
			echo "<div class='red'><i class='fa fa-warning'></i> Maaf sepertinya " . implode(' dan ', $error) . " anda kosong.</div>";
		} else {
			$password = sha1($password);
			$query = $this->con->query("select * from user where username='$username' and password='$password' and status='$loginas'");
			// coded by https://www.athoul.site
			if ($query->num_rows > 0) {
				echo "<div class='green'><i class='fa fa-check'></i> Login Berhasil, silahkan tunggu beberapa saat.</div>";
				$data = $query->fetch_assoc();
				session_start();
				$_SESSION['username'] = $data['username'];
				$_SESSION['status'] = $data['status'];
				$_SESSION['id'] = $data['id'];
				if ($data['status'] == '1') {
					$this->redirect("home.php");
				} else {
					$this->redirect("transaksi.php");
				}
			} else {
				echo "<div class='red'><i class='fa fa-warning'></i> Maaf sepertinya username atau password anda salah.</div>";
			}
		}
	}
	// fungsi untuk nambah diskon
	function tambah_diskon($idBarang, $jumlahDiskon)
	{
		$queryPotongan = $this->con->query("SELECT * FROM tb_potongan WHERE id_barang='$idBarang'");
		$dataPotongan = $queryPotongan->fetch_assoc();
		// $barang_id = $dataPotongan['']
		if ($queryPotongan->num_rows > 0) {
			$this->alert("Harga Barang Sudah Dipotong'$dataPotongan[jumlah_potongan]' Silahkan Hapus Terlebih Dahulu");
			$this->redirect("barang.php");
		} else {
			$query = $this->con->query("INSERT INTO tb_diskon SET jumlah_diskon='$jumlahDiskon', id_barang='$idBarang'");

			if ($query === TRUE) {
				$this->alert("Berhasil Menambahkan Diskon");
				$this->redirect("barang.php");
			} else {
				$this->alert("Barang Sudah Diberikan Diskon Sebelumnya. Silahkan Ubah Atau Hapus Terlebih Dahulu");
				$this->redirect("barang.php");
			}
		}
	}
	// fungsi untuk nambah potongan admin
	function tambah_potongan($idBarang, $jumlahDiskon)
	{
		$queryDiskon = $this->con->query("SELECT * FROM tb_diskon WHERE id_barang='$idBarang'");
		$dataDiskon = $queryDiskon->fetch_assoc();
		$queryBarang = $this->con->query("SELECT * FROM barang WHERE id_barang='$idBarang'");
		$dataBarang = $queryBarang->fetch_assoc();
		if ($queryDiskon->num_rows > 0) {
			$this->alert("Harga Barang Sudah Ada Diskon '$dataDiskon[jumlah_diskon]'% Silahkan Hapus Terlebih Dahulu");
			$this->redirect("barang.php");
		} else {
			$selectTempo = $this->con->query("SELECT * FROM tempo WHERE id_barang='$idBarang'");
			if ($selectTempo->num_rows > 0) {
				$ubah = $selectTempo->fetch_assoc();
				$jumbel = $ubah['jumlah_beli'];
				// $totalDiskon = $jumlahDiskon / 100;
				$total_diskon = ($jumbel * $dataBarang['harga_jual']) - $jumlahDiskon;
				$query = $this->con->query("UPDATE tempo SET total_harga='$total_diskon',potongan='$jumlahDiskon', jenis_potongan='potongan' WHERE id_barang='$idBarang'");
			} else {
				$query = $this->con->query("INSERT INTO tb_potongan SET jumlah_potongan='$jumlahDiskon', id_barang='$idBarang'");
			}
			if ($query === TRUE) {
				$this->alert("Berhasil Menambahkan Potongan");
				$this->redirect("barang.php");
			} else {
				$this->alert("Data Sudah Ada");
				$this->redirect("barang.php");
			}
		}
	}
	// fungsi untuk nambah potongan kasir
	function tambah_potongan_kasir($idBarang, $jumlahPotongan)
	{
		$query = $this->con->query("INSERT INTO tb_potongan SET jumlah_potongan='$jumlahPotongan', id_barang='$idBarang'");
		$quDiskon = $this->con->query("SELECT * FROM tb_potongan WHERE id_barang='$idBarang'");
		$q1 = $this->con->query("select * from barang where id_barang='$idBarang'");
		$data = $q1->fetch_assoc();
		if ($query === TRUE) {
			$q = $this->con->query("select * from tempo where id_barang='$idBarang'");
			if ($q->num_rows > 0) {
				$ubah = $q->fetch_assoc();
				$jumbel = $ubah['jumlah_beli'];
				if ($quDiskon->num_rows > 0) {
					$fetch = $quDiskon->fetch_assoc();
					$jumlahDiskon = $fetch['jumlah_potongan'];
					// $totalDiskon = $jumlahDiskon / 100;
					$total_diskon = ($jumbel * $data['harga_jual']) - $jumlahDiskon;
					// $total_harga = ($jumbel * $data['harga_jual']) - $total_diskon;
					$this->con->query("UPDATE tempo SET total_harga=$total_diskon, potongan='$jumlahDiskon',jenis_potongan='potongan' WHERE id_barang='$idBarang'");
				} else {
					$this->alert("Terjadi Kesalahan");
				}
			}
			$hasil = 0;
		} else {
			$hasil = 1;
		}
		return $hasil;
	}

	// fungsi untuk nambah diskon kasir
	function tambah_diskon_kasir($idBarang, $jumlahDiskon)
	{
		$query = $this->con->query("INSERT INTO tb_diskon SET jumlah_diskon='$jumlahDiskon', id_barang='$idBarang'");
		$quDiskon = $this->con->query("SELECT * FROM tb_diskon WHERE id_barang='$idBarang'");
		$q1 = $this->con->query("select * from barang where id_barang='$idBarang'");
		$data = $q1->fetch_assoc();

		if ($query === TRUE) {
			$q = $this->con->query("select * from tempo where id_barang='$idBarang'");
			if ($q->num_rows > 0) {
				$ubah = $q->fetch_assoc();
				$jumbel = $ubah['jumlah_beli'];
				if ($quDiskon->num_rows > 0) {
					$fetch = $quDiskon->fetch_assoc();
					$jumlahDiskon = $fetch['jumlah_diskon'];
					$totalDiskon = $jumlahDiskon / 100;
					$total_diskon = ($jumbel * $data['harga_jual']) * $totalDiskon;
					$total_harga = ($jumbel * $data['harga_jual']) - $total_diskon;
					$this->con->query("UPDATE tempo SET total_harga=$total_harga, potongan='$jumlahDiskon',jenis_potongan='diskon' WHERE id_barang='$idBarang'");
				} else {
					$this->alert("Terjadi Kesalahan");
				}
				return 0;
			} else {
				return 1;
				// $this->alert("Terjadi Kesalahan");
			}
			// return $hasil;
		}
	}
	function tambah_barang($nama_barang, $stok, $harga_beli, $harga_jual, $id_kategori, $file)
	{
		$date = date("Y-m-d H:i:s");
		$query = $this->con->query("select * from barang where nama_barang='$nama_barang'");
		if ($query->num_rows > 0) {
			$this->alert("Data barang sudah ada");
			$this->go_back();
		} else {
			$query2 = $this->con->query("insert into barang set nama_barang='$nama_barang',id_kategori='$id_kategori',stok='$stok',harga_beli='$harga_beli',harga_jual='$harga_jual', foto_produk='$file', date_added='$date'");
			if ($query2 === TRUE) {
				$this->alert("Barang Berhasil Ditambahkan");
				$this->redirect("barang.php");
			} else {
				$this->alert("Barang Gagal Ditambahkan");
				$this->redirect("barang.php");
			}
		}
	}
	function tambah_kasir($nama_kasir, $password)
	{
		$nama_kasir = str_replace(" ", "", $nama_kasir);
		$query = $this->con->query("select * from  user where username='$nama_kasir' and status='2'");
		if ($query->num_rows > 0) {
			$this->alert("Username  untuk kasir  sudah ada.");
			$this->go_back();
		} else {
			$password = sha1($password);
			$query2 = $this->con->query("insert into user set username='$nama_kasir',password='$password',status='2'");
			if ($query2 ===  TRUE) {
				$this->alert("Data kasir berhasil dismpan");
				$this->redirect("users.php");
			} else {
				$this->alert("Kasir Gagal Ditambahkan");
				$this->redirect("users.php");
			}
		}
	}
	// coded by https://www.athoul.site
	function tambah_kategori($nama_kategori)
	{
		$query = $this->con->query("select * from kategori where nama_kategori='$nama_kategori'");
		if ($query->num_rows > 0) {
			$this->alert("Kategori Sudah Ada");
			$this->redirect("kategori.php");
		} else {
			if (!empty($nama_kategori)) {
				$query2 = $this->con->query("insert into kategori set nama_kategori='$nama_kategori'");
				if ($query2 === TRUE) {
					$this->alert("kategori Berhasil Ditambahkan");
					$this->redirect("kategori.php");
				} else {
					$this->alert("kategori Gagal Ditambahkan");
					$this->redirect("kategori.php");
				}
			} else {
				$this->alert("Nama Kategori Harus Diisi");
				$this->redirect("kategori.php");
			}
		}
	}
	function tampil_barang($keyword)
	{
		if ($keyword == "null") {
			$query = $this->con->query("select barang.id_barang,barang.nama_barang,barang.stok,barang.harga_beli,barang.harga_jual,barang.date_added,kategori.nama_kategori from barang inner join kategori on kategori.id_kategori=barang.id_kategori");
		} else {
			$query = $this->con->query("select barang.id_barang,barang.nama_barang,barang.stok,barang.harga_beli,barang.harga_jual,barang.date_added,kategori.nama_kategori from barang inner join kategori on kategori.id_kategori=barang.id_kategori where nama_barang like '%$keyword%'");
		}
		if ($query->num_rows > 0) {
			$no = 1;
			while ($data = $query->fetch_assoc()) {
		?>
				<tr>
					<td><?= $no ?></td>
					<td><?= $data['nama_barang'] ?></td>
					<td><?= $data['nama_kategori'] ?></td>
					<td><?= $data['stok'] ?></td>
					<td>Rp. <?= number_format($data['harga_beli']) ?></td>
					<td>Rp. <?= number_format($data['harga_jual']) ?></td>
					<?php
					$dataDiskon = $this->con->query("SELECT * FROM tb_diskon WHERE id_barang = '$data[id_barang]'");
					if ($dataDiskon->num_rows > 0) {
						$fetch = $dataDiskon->fetch_assoc();
					?>
						<td class="text-center"><?= $fetch['jumlah_diskon'] ?>%<span>
								<a href="?action=edit_diskon&id_barang=<?= $fetch['id_barang'] ?>" class="btn bluetbl m-r-10"><span class="btn-edit-tooltip">Edit Diskon</span><i class="fas fa-pencil-alt"></i></a>
							</span></td>
					<?php   } else { ?>
						<td class="text-center">
							<?php
							$dataPotongan = $this->con->query("SELECT * FROM tb_potongan WHERE id_barang = '$data[id_barang]'");
							if ($dataPotongan->num_rows > 0) {
								$fetch = $dataPotongan->fetch_assoc();
							?>
								Rp. <?= number_format($fetch['jumlah_potongan']) ?><span>
									<a href="?action=edit_potongan&id_barang=<?= $fetch['id_barang'] ?>" class="btn bluetbl m-r-10"><span class="btn-edit-tooltip">Edit Potongan</span><i class="fas fa-pencil-alt"></i></a>
								</span>

							<?php } else { ?>
								<?php
								$quTempo = $this->con->query("SELECT * FROM tempo WHERE id_barang='$data[id_barang]'");
								if ($quTempo->num_rows > 0) {
									$tempo = $quTempo->fetch_assoc();
									if ($tempo['jenis_potongan'] == 'diskon') { ?>
										<?= $tempo['jumlah_potongan'] ?> %
									<?php } else if ($tempo['jenis_potongan'] == 'potongan') { ?>
										Rp. <?= number_format($tempo['jumlah_potongan']) ?>
									<?php } else {
										echo "Tidak ada Diskon ataupun Potongan";
									} ?>

								<?php	} else {
									echo "Tidak ada Diskon ataupun Potongan";
								} ?>

							<?php } ?>

						</td>
					<?php } ?>
					<td><?= date("d-m-Y", strtotime($data['date_added'])) ?></td>
					<td>
						<a href="?action=edit_barang&id_barang=<?= $data['id_barang'] ?>" class="btn bluetbl m-r-10"><span class="btn-edit-tooltip">Edit</span><i class="fas fa-pencil-alt"></i></a>
						<a href="handler.php?action=hapus_barang&id_barang=<?= $data['id_barang'] ?>" class="btn redtbl" onclick="return confirm('yakin ingin menghapus <?= $data['nama_barang'] . " (id : " . $data['id_barang'] ?>) ?')"><span class="btn-hapus-tooltip">Hapus</span><i class="fa fa-trash"></i></a>

					</td>
				</tr>
			<?php
				$no++;
			}
		} else {
			echo "<td></td><td colspan='5'>Maaf, barang yang anda cari tidak ada!</td>";
		}
	}

	// get nama barang untuk autocomplete
	function getNamaBarang()
	{
		$query = $this->con->query("SELECT * FROM barang");
		// $fetch = $query->fetch_row();
		if ($query->num_rows > 0) {

			$tmp = array();
			while ($row = mysqli_fetch_assoc($query)) {
				array_push($tmp, array(
					'label' => $row['nama_barang'],
					'value' => $row['id_barang'],
					'stok' => $row['stok'],
					'harga' => $row['harga_jual'],
				));
			}
			$result = [
				"res" => 'oke',
				"num" => count($tmp),
				"data" => $tmp
			];
		} else {
			$result = [
				"res" => 'null',
				"data" => null,
			];
		}


		return json_encode($result);
	}
	function tampil_barang_filter($id_cat)
	{
		$query = $this->con->query("select barang.id_barang,barang.nama_barang,barang.stok,barang.harga_beli,barang.harga_jual,barang.date_added,kategori.nama_kategori from barang inner join kategori on kategori.id_kategori=barang.id_kategori where kategori.id_kategori='$id_cat'");
		if ($query->num_rows > 0) {

			$no = 1;
			while ($data = $query->fetch_assoc()) {
			?>
				<tr>
					<td><?= $no ?></td>
					<td><?= $data['nama_barang'] ?></td>
					<td><?= $data['nama_kategori'] ?></td>
					<td><?= $data['stok'] ?></td>
					<td>Rp. <?= number_format($data['harga_beli']) ?></td>
					<td>Rp. <?= number_format($data['harga_jual']) ?></td>
					<?php
					$dataDiskon = $this->con->query("SELECT * FROM tb_diskon WHERE id_barang = '$data[id_barang]'");
					if ($dataDiskon->num_rows > 0) {
						$fetch = $dataDiskon->fetch_assoc();
					?>
						<td><?= $fetch['jumlah_diskon'] ?>% <span>
								<a href="?action=edit_diskon&id_barang=<?= $fetch['id_barang'] ?>" class="btn bluetbl m-r-10"><span class="btn-edit-tooltip">Edit Diskon</span><i class="fas fa-pencil-alt"></i></a>
							</span></td>
					<?php   } else { ?>
						<td>Tidak Ada Diskon</td>
					<?php } ?>
					<td><?= date("d-m-Y", strtotime($data['date_added'])) ?></td>
					<td>
						<a href="?action=edit_barang&id_barang=<?= $data['id_barang'] ?>" class="btn bluetbl m-r-10"><span class="btn-edit-tooltip">Edit</span><i class="fa fa-pencil"></i></a>
						<a href="handler.php?action=hapus_barang&id_barang=<?= $data['id_barang'] ?>" class="btn redtbl" onclick="return confirm('yakin ingin menghapus <?= $data['nama_barang'] . " (id : " . $data['id_barang'] ?>) ?')"><span class="btn-hapus-tooltip">Hapus</span><i class="fa fa-trash"></i></a>
					</td>
				</tr>
			<?php
				$no++;
			}
		} else {
			echo "<td></td><td colspan='5'>Barang dengan kategori tersebut masih kosong</td>";
		}
	}
	// coded by https://www.athoul.site
	function tampil_kategori()
	{
		$query = $this->con->query("select * from kategori order by id_kategori desc");
		$no = 1;
		while ($data = $query->fetch_assoc()) {
			?>
			<tr>
				<td><?= $no ?></td>
				<td><?= $data['nama_kategori'] ?></td>
				<td>
					<a href="?action=edit_kategori&id_kategori=<?= $data['id_kategori'] ?>" class="btn bluetbl m-r-10"><span class="btn-edit-tooltip">Edit</span><i class="fas fa-pencil-alt"></i></a>
					<a href="handler.php?action=hapus_kategori&id_kategori=<?= $data['id_kategori'] ?>" class="btn redtbl" onclick="return confirm('yakin ingin menghapus kategori : <?= $data['nama_kategori'] ?> ?')"><span class="btn-hapus-tooltip">Hapus</span><i class="fa fa-trash"></i></a>
				</td>
			</tr>
		<?php

			$no++;
		}
	}
	function tampil_kategori2()
	{
		$query = $this->con->query("select * from kategori order by id_kategori desc");
		while ($data = $query->fetch_assoc()) {
		?>
			<option value="<?= $data['id_kategori'] ?>"><?= $data['nama_kategori'] ?></option>
		<?php
		}
	}
	function tampil_kategori3($id_barang)
	{
		$q = $this->con->query("select * from barang where id_barang='$id_barang'");
		$q2 = $q->fetch_assoc();
		$id_cat = $q2['id_kategori'];
		$query = $this->con->query("select * from kategori order by id_kategori desc");
		while ($data = $query->fetch_assoc()) {
		?>
			<option <?php if ($data['id_kategori'] == $id_cat) {
						echo "selected";
					} ?> value="<?= $data['id_kategori'] ?>"><?= $data['nama_kategori'] ?></option>
		<?php
		}
	}
	function tampil_kasir()
	{
		$query = $this->con->query("select * from user where status='2'");
		$no = 1;
		while ($data = $query->fetch_assoc()) {
		?>
			<tr>
				<td><?= $no ?></td>
				<td><?= $data['username'] ?></td>
				<td>Kasir</td>
				<td><?= date("d-m-Y", strtotime($data['date_created'])) ?></td>
				<td>
					<a href="?action=edit_kasir&id_kasir=<?= $data['id'] ?>" class="btn bluetbl m-r-10"><span class="btn-edit-tooltip">Edit</span><i class="fas fa-pencil-alt"></i></a>
					<a href="handler.php?action=hapus_user&id_user=<?= $data['id'] ?>" class="btn redtbl" onclick="return confirm('yakin ingin menghapus user : <?= $data['username'] ?> ?')"><span class="btn-hapus-tooltip">Hapus</span><i class="fa fa-trash"></i></a>
				</td>
			</tr>
		<?php
			$no++;
		}
	}
	// tampil laporan transaksi kasir
	function tampil_laporan_kasir($id)
	{
		$query = $this->con->query("SELECT transaksi.id_transaksi,transaksi.kode_kasir, transaksi.tgl_transaksi,transaksi.no_invoice,transaksi.total_bayar,transaksi.nama_pembeli FROM transaksi WHERE transaksi.kode_kasir='$id' ORDER BY transaksi.id_transaksi DESC");
		// var_dump($id);
		$no = 1;
		while ($f = $query->fetch_assoc()) {
		?>
			<tr>
				<td><?= $no++ ?></td>
				<td><?= $f['no_invoice'] ?></td>
				<td><?= $_SESSION['username'] ?></td>
				<td><?= $f['nama_pembeli'] ?></td>
				<td><?= date("d-m-Y", strtotime($f['tgl_transaksi'])) ?></td>
				<td>Rp. <?= number_format($f['total_bayar']) ?></td>
				<td>
					<a href="?action=detail_transaksi&id_transaksi=<?= $f['id_transaksi'] ?>" class="btn bluetbl m-r-10"><span class="btn-edit-tooltip">Lihat</span><i class="fa fa-eye"></i></a>
					<a onclick="return confirm('yakin ingin menghapus <?= $f['no_invoice'] . " (id : " . $f['id_transaksi'] ?>) ?')" href="handler.php?action=delete_transaksi&id=<?= $f['id_transaksi'] ?>" class="btn redtbl"><span class="btn-hapus-tooltip">Hapus</span><i class="fa fa-trash"></i></a>
				</td>
			</tr>
		<?php
		}
	}
	// filter laporan transaksi kasir
	function filter_tampil_laporan_kasir($tanggal, $aksi, $id)
	{
		if ($aksi == 1) {
			$split1 = explode('-', $tanggal);
			$tanggal = $split1[2] . "-" . $split1[1] . "-" . $split1[0];
			$query = $this->con->query("select transaksi.id_transaksi,transaksi.tgl_transaksi,transaksi.no_invoice,transaksi.total_bayar,transaksi.nama_pembeli,user.username from transaksi inner join user on transaksi.kode_kasir=user.id where transaksi.tgl_transaksi like '%$tanggal%' AND kode_kasir='$id' order by transaksi.id_transaksi desc");
		} else {
			$split1 = explode('-', $tanggal);
			$tanggal = $split1[1] . "-" . $split1[0];
			$query = $this->con->query("select transaksi.id_transaksi,transaksi.tgl_transaksi,transaksi.no_invoice,transaksi.total_bayar,transaksi.nama_pembeli,user.username from transaksi inner join user on transaksi.kode_kasir=user.id where transaksi.tgl_transaksi like '%$tanggal%' AND kode_kasir='$id' order by transaksi.id_transaksi desc");
		}

		$no = 1;
		while ($f = $query->fetch_assoc()) {
		?>
			<tr>
				<td><?= $no++ ?></td>
				<td><?= $f['no_invoice'] ?></td>
				<td><?= $f['username'] ?></td>
				<td><?= $f['nama_pembeli'] ?></td>
				<td><?= date("d-m-Y", strtotime($f['tgl_transaksi'])) ?></td>
				<td>Rp. <?= number_format($f['total_bayar']) ?></td>
				<td>
					<a href="?action=detail_transaksi&id_transaksi=<?= $f['id_transaksi'] ?>" class="btn bluetbl m-r-10"><span class="btn-edit-tooltip">Lihat</span><i class="fa fa-eye"></i></a>
					<a onclick="return confirm('yakin ingin menghapus <?= $f['no_invoice'] . " (id : " . $f['id_transaksi'] ?>) ?')" href="handler.php?action=delete_transaksi&id=<?= $f['id_transaksi'] ?>" class="btn redtbl"><span class="btn-hapus-tooltip">Hapus</span><i class="fa fa-trash"></i></a>
				</td>
			</tr>
		<?php
		}
	}
	function tampil_laporan()
	{
		$query = $this->con->query("select transaksi.id_transaksi,transaksi.tgl_transaksi,transaksi.no_invoice,transaksi.total_bayar,transaksi.nama_pembeli,user.username from transaksi inner join user on transaksi.kode_kasir=user.id order by transaksi.id_transaksi desc");
		$no = 1;
		while ($f = $query->fetch_assoc()) {
		?>
			<tr>
				<td><?= $no++ ?></td>
				<td><?= $f['no_invoice'] ?></td>
				<td><?= $f['username'] ?></td>
				<td><?= $f['nama_pembeli'] ?></td>
				<td><?= date("d-m-Y", strtotime($f['tgl_transaksi'])) ?></td>
				<td>Rp. <?= number_format($f['total_bayar']) ?></td>
				<td>
					<a href="?action=detail_transaksi&id_transaksi=<?= $f['id_transaksi'] ?>" class="btn bluetbl m-r-10"><span class="btn-edit-tooltip">Lihat</span><i class="fa fa-eye"></i></a>
					<a onclick="return confirm('yakin ingin menghapus <?= $f['no_invoice'] . " (id : " . $f['id_transaksi'] ?>) ?')" href="handler.php?action=delete_transaksi&id=<?= $f['id_transaksi'] ?>" class="btn redtbl"><span class="btn-hapus-tooltip">Hapus</span><i class="fa fa-trash"></i></a>
				</td>
			</tr>
		<?php
		}
	}
	function filter_tampil_laporan($tanggal, $aksi)
	{
		if ($aksi == 1) {
			$split1 = explode('-', $tanggal);
			$tanggal = $split1[2] . "-" . $split1[1] . "-" . $split1[0];
			$query = $this->con->query("select transaksi.id_transaksi,transaksi.tgl_transaksi,transaksi.no_invoice,transaksi.total_bayar,transaksi.nama_pembeli,user.username from transaksi inner join user on transaksi.kode_kasir=user.id where transaksi.tgl_transaksi like '%$tanggal%' order by transaksi.id_transaksi desc");
		} else {
			$split1 = explode('-', $tanggal);
			$tanggal = $split1[1] . "-" . $split1[0];
			$query = $this->con->query("select transaksi.id_transaksi,transaksi.tgl_transaksi,transaksi.no_invoice,transaksi.total_bayar,transaksi.nama_pembeli,user.username from transaksi inner join user on transaksi.kode_kasir=user.id where transaksi.tgl_transaksi like '%$tanggal%' order by transaksi.id_transaksi desc");
		}

		$no = 1;
		while ($f = $query->fetch_assoc()) {
		?>
			<tr>
				<td><?= $no++ ?></td>
				<td><?= $f['no_invoice'] ?></td>
				<td><?= $f['username'] ?></td>
				<td><?= $f['nama_pembeli'] ?></td>
				<td><?= date("d-m-Y", strtotime($f['tgl_transaksi'])) ?></td>
				<td>Rp. <?= number_format($f['total_bayar']) ?></td>
				<td>
					<a href="?action=detail_transaksi&id_transaksi=<?= $f['id_transaksi'] ?>" class="btn bluetbl m-r-10"><span class="btn-edit-tooltip">Lihat</span><i class="fa fa-eye"></i></a>
					<a onclick="return confirm('yakin ingin menghapus <?= $f['no_invoice'] . " (id : " . $f['id_transaksi'] ?>) ?')" href="handler.php?action=delete_transaksi&id=<?= $f['id_transaksi'] ?>" class="btn redtbl"><span class="btn-hapus-tooltip">Hapus</span><i class="fa fa-trash"></i></a>
				</td>
			</tr>
<?php
		}
	}
	function show_jumlah_cat()
	{
		$query = $this->con->query("select * from kategori");
		echo $query->num_rows;
	}
	function show_jumlah_barang()
	{
		$query = $this->con->query("select * from barang");
		echo $query->num_rows;
	}
	function show_jumlah_kasir()
	{
		$query = $this->con->query("select * from user where status='2'");
		echo $query->num_rows;
	}
	function show_jumlah_trans()
	{
		$query = $this->con->query("select * from transaksi where kode_kasir='$_SESSION[id]'");
		echo $query->num_rows;
	}
	function show_jumlah_trans2()
	{
		$query = $this->con->query("select * from transaksi");
		echo $query->num_rows;
	}
	function hapus_kategori($id_kategori)
	{
		$query = $this->con->query("delete from kategori where id_kategori='$id_kategori'");
		if ($query === TRUE) {
			$this->alert("Kategori id $id_kategori telah dihapus");
			$this->redirect("kategori.php");
		}
	}
	function hapus_barang($id_barang)
	{
		$query = $this->con->query("delete from barang where id_barang='$id_barang'");
		if ($query === TRUE) {
			$this->alert("barang id $id_barang telah dihapus");
			$this->redirect("barang.php");
		} else {
			$this->alert("terjadi kesalahan");
			$this->go_back();
		}
	}
	function hapus_user($id_user)
	{
		$query = $this->con->query("delete from user where id='$id_user'");
		if ($query === TRUE) {
			$this->alert("Kasir id : $id_user berhasil dihapus");
			$this->redirect("users.php");
		}
	}
	function edit_kategori($id_kategori)
	{
		$query = $this->con->query("select * from kategori where id_kategori='$id_kategori'");
		$data = $query->fetch_assoc();
		return $data;
	}
	function edit_barang($id_barang)
	{
		$query = $this->con->query("select * from barang where id_barang='$id_barang'");
		$data = $query->fetch_assoc();
		return $data;
	}
	function edit_kasir($id_kasir)
	{
		$query = $this->con->query("select * from user where id='$id_kasir'");
		$data = $query->fetch_assoc();
		return $data;
	}
	function edit_diskon($id_barang)
	{
		$query = $this->con->query("SELECT * FROM tb_diskon WHERE id_barang='$id_barang'");
		$data = $query->fetch_assoc();
		return $data;
	}
	function edit_potongan($id_barang)
	{
		$query = $this->con->query("SELECT * FROM tb_potongan WHERE id_barang='$id_barang'");
		$data = $query->fetch_assoc();
		return $data;
	}
	function edit_admin()
	{
		$query = $this->con->query("select * from user where id='1'");
		$data = $query->fetch_assoc();
		return $data;
	}
	function aksi_edit_kategori($id_kategori, $nama_kategori)
	{
		$query = $this->con->query("update kategori set nama_kategori='$nama_kategori' where id_kategori='$id_kategori'");
		if ($query === TRUE) {
			$this->alert("Kategori berhasil di update");
			$this->redirect("kategori.php");
		} else {
			$this->alert("Kategori gagal di update");
			$this->redirect("kategori.php");
		}
	}
	function aksi_edit_barang($id_barang, $nama_barang, $stok, $harga_beli, $harga_jual, $id_kategori, $file)
	{
		$query = $this->con->query("update barang set nama_barang='$nama_barang',stok='$stok',harga_beli='$harga_beli',harga_jual='$harga_jual',id_kategori='$id_kategori',date_added=date_added ,foto_produk='$file' where id_barang='$id_barang'");
		if ($query === TRUE) {
			$this->alert("Barang berhasil di update");
			$this->redirect("barang.php");
		} else {
			$this->alert("Barang gagal di update (Nama Barang Sudah Ada)");
			$this->redirect("barang.php");
		}
	}
	function aksi_edit_kasir($username, $password, $id)
	{
		if (empty($password)) {
			$query = $this->con->query("update user set username='$username',date_created=date_created where id='$id'");
		} else {
			$password = sha1($password);
			$query = $this->con->query("update user set username='$username',password='$password',date_created=date_created where id='$id'");
		}

		if ($query === TRUE) {
			$this->alert("Kasir berhasil di update");
			$this->redirect("users.php");
		} else {
			$this->alert("User gagal di update");
			$this->redirect("user.php");
		}
	}
	function aksi_edit_admin($username, $password)
	{
		if (empty($password)) {
			$query = $this->con->query("update user set username='$username',date_created=date_created where id='1'");
		} else {
			$password = sha1($password);
			$query = $this->con->query("update user set username='$username',password='$password',date_created=date_created where id='1'");
		}

		if ($query === TRUE) {
			$this->alert("admin berhasil di update, silahkan login kembali");
			session_start();
			session_destroy();
			$this->redirect("index.php");
		} else {
			$this->alert("admin gagal di update");
			$this->redirect("user.php");
		}
	}
	function tambah_tempo($id_barang, $jumlah, $trx)
	{
		$q1 = $this->con->query("select * from barang where id_barang='$id_barang'");
		$data = $q1->fetch_assoc();
		$quDiskon = $this->con->query("SELECT * FROM tb_diskon WHERE id_barang='$id_barang'");
		$quPotongan = $this->con->query("SELECT * FROM tb_potongan WHERE id_barang='$id_barang'");

		if ($data['stok'] < $jumlah) {
			$hasil = $this->alert("stock tidak mencukupi");

			// $this->redirect("transaksi.php?action=transaksi_baru");
		} else {
			$q = $this->con->query("select * from tempo where id_barang='$id_barang'");
			if ($q->num_rows > 0) {
				$ubah = $q->fetch_assoc();
				$jumbel = $ubah['jumlah_beli'] + $jumlah;
				if ($quDiskon->num_rows > 0) {
					$fetch = $quDiskon->fetch_assoc();
					$jumlahDiskon = $fetch['jumlah_diskon'];
					$totalDiskon = $jumlahDiskon / 100;
					$total_diskon = ($jumbel * $data['harga_jual']) * $totalDiskon;
					$total_harga = ($jumbel * $data['harga_jual']) - $total_diskon;
					$dbquery = $this->con->query("update tempo set jumlah_beli='$jumbel',total_harga='$total_harga', potongan='$jumlahDiskon',jenis_potongan='diskon' where id_barang='$id_barang'");
				} else {
					if ($quPotongan->num_rows > 0) {
						$fetch = $quPotongan->fetch_assoc();
						$jumlahPotongan = $fetch['jumlah_potongan'];
						$total_harga = ($jumbel * $data['harga_jual']) - $jumlahPotongan;
						$dbquery = $this->con->query("update tempo set jumlah_beli='$jumbel',total_harga='$total_harga', potongan='$jumlahPotongan',jenis_potongan='potongan' where id_barang='$id_barang'");
					} else {
						$total_harga = $jumlah * $data['harga_jual'];
						$dbquery = $this->con->query("update tempo set jumlah_beli='$jumbel',total_harga='$total_harga' where id_barang='$id_barang'");
					}
					// $total_harga = $jumbel * $data['harga_jual'];
				}
				// $dbquery = $this->con->query("update tempo set jumlah_beli='$jumbel',total_harga='$total_harga' where id_barang='$id_barang'");
				if ($dbquery === TRUE) {
					$this->con->query("update barang set stok=stok-$jumlah where id_barang='$id_barang'");
				} else {
					$this->alert("Terjadi Kesalahan Silahkan Ulangi");
				}
			} else {
				if ($quDiskon->num_rows > 0) {
					$fetch = $quDiskon->fetch_assoc();
					$jumlahDiskon = $fetch['jumlah_diskon'];
					$totalDiskon = $jumlahDiskon / 100;
					$total_diskon = ($jumlah * $data['harga_jual']) * $totalDiskon;
					$total_harga = ($jumlah * $data['harga_jual']) - $total_diskon;
					$query1 = $this->con->query("insert into tempo set id_barang='$id_barang',jumlah_beli='$jumlah',potongan='$jumlahDiskon',total_harga='$total_harga',jenis_potongan='diskon', trx='$trx'");
				} else {
					if ($quPotongan->num_rows > 0) {
						$fetch = $quPotongan->fetch_assoc();
						$jumlahPotongan = $fetch['jumlah_potongan'];
						$total_harga = ($jumlah * $data['harga_jual']) - $jumlahPotongan;
						$query1 = $this->con->query("insert into tempo set id_barang='$id_barang',jumlah_beli='$jumlah',potongan='$jumlahPotongan',total_harga='$total_harga',jenis_potongan='potongan', trx='$trx'");
					} else {
						$total_harga = $jumlah * $data['harga_jual'];
						$query1 = $this->con->query("insert into tempo set id_barang='$id_barang',jumlah_beli='$jumlah',total_harga='$total_harga',trx='$trx'");
					}
				}
				// $total_harga = $jumlah * $data['harga_jual'];
				if ($query1 === TRUE) {
					$this->con->query("update barang set stok=stok-$jumlah where id_barang='$id_barang'");
					$hasil = 0;
				} else {
					$hasil = 1;
				}
			}
		}
		return $hasil;
	}
	function hapus_tempo($id_tempo, $id_barang, $jumbel)
	{
		$query = $this->con->query("delete from tempo where id_subtransaksi='$id_tempo'");
		if ($query === TRUE) {
			$this->con->query("update barang set stok=stok+$jumbel where id_barang='$id_barang'");
			return 0;
		} else {
			return 1;
		}
	}
	// function get_grand($trx)
	// {

	// 	return $getsum1;
	// }
}
// coded by https://www.athoul.site
$root = new penjualan();
?>