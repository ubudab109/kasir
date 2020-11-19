<?php include "head.php" ?>
<?php
if (isset($_GET['action']) && $_GET['action'] == "tambah_barang") {
	include "tambah_barang.php";
} else if (isset($_GET['action']) && $_GET['action'] == "edit_barang") {
	include "edit_barang.php";
} else if (isset($_GET['action']) && $_GET['action'] == "tambah_diskon") {
	include "tambah_diskon.php";
} else if (isset($_GET['action']) && $_GET['action'] == "tambah_potongan") {
	include "tambah_potongan.php";
} else if (isset($_GET['action']) && $_GET['action'] == "edit_diskon") {
	include "edit_diskon.php";
} else if (isset($_GET['action']) && $_GET['action'] == "edit_potongan") {
	include "edit_potongan.php";
} else {
?>
	<script type="text/javascript">
		document.title = "Barang";
		document.getElementById('barang').classList.add('active');
	</script>
	<script type="text/javascript" src="assets/jquery.tablesorter.min.js"></script>
	<script type="text/javascript">
		$(function() {
			$.tablesorter.addWidget({
				id: "indexFirstColumn",
				format: function(table) {
					$(table).find("tr td:first-child").each(function(index) {
						$(this).text(index + 1);
					})
				}
			});
			$("table").tablesorter({
				widgets: ['indexFirstColumn'],
				headers: {
					0: {
						sorter: false
					},
					3: {
						sorter: false
					},
					4: {
						sorter: false
					},
					5: {
						sorter: false
					},
					6: {
						sorter: false
					},
					7: {
						sorter: false
					},
				}
			});
		});
	</script>

	<div class="content">
		<div class="padding">
			<div class="bgwhite">
				<div class="padding">
					<div class="contenttop">
						<div class="left">
							<a href="?action=tambah_barang" class="btnblue"><i class="fa fa-plus"></i> Tambah Barang</a>
							<a href="?action=tambah_diskon" class="btnblue"><i class="fas fa-percentage"></i> Tambah Diskon</a>
							<a href="?action=tambah_potongan" class="btnblue"><i class="fas fa-percentage"></i> Tambah Potongan</a>
							<a href="cetak_barang.php" class="btnblue" target="_blank"><i class="fa fa-print"></i> Cetak</a>
						</div>
						<div class="right">
							<script type="text/javascript">
								function gotocat(val) {
									var value = val.options[val.selectedIndex].value;
									window.location.href = "barang.php?id_cat=" + value + "";
								}
							</script>
							<select class="leftin1" onchange="gotocat(this)">
								<option value="">Filter kategori</option>
								<?php
								$data = $root->con->query("select * from kategori");
								while ($f = $data->fetch_assoc()) {
								?>
									<option <?php if (isset($_GET['id_cat'])) {
												if ($_GET['id_cat'] == $f['id_kategori']) {
													echo "selected";
												}
											} ?> value="<?= $f['id_kategori'] ?>"><?= $f['nama_kategori'] ?></option>
								<?php
								}
								?>
							</select>
							<form class="leftin">
								<input type="text" name="q" id="search" placeholder="Cari Barang...">
								<!-- <button><i class="fa fa-search"></i></button> -->
							</form>
						</div>
						<div class="both"></div>
					</div>
					<span class="label">Jumlah Barang : <?= $root->show_jumlah_barang() ?></span>
					<table class="datatable" id="datatable">
						<thead>
							<tr>
								<th width="10px">#</th>
								<th style="cursor: pointer;">Nama Barang <i class="fa fa-sort"></i></th>
								<th style="cursor: pointer;" width="100px">Kategori <i class="fa fa-sort"></i></th>
								<th>Stok</th>
								<th width="120px">Harga Beli</th>
								<th width="120px">Harga Jual</th>
								<th width="120px">Diskon</th>
								<th width="150px">Tanggal Ditambahkan</th>
								<th width="60px">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if (isset($_GET['id_cat']) && $_GET['id_cat']) {
								$root->tampil_barang_filter($_GET['id_cat']);
							} else {
								$keyword = isset($_GET['q']) ? $_GET['q'] : "null";
								$root->tampil_barang($keyword);
							}
							?>
						</tbody>

					</table>
				</div>
			</div>
		</div>
	</div>
	<script>
		$("#search").keyup(function() {
			// Declare variables
			var input, filter, table, tr, td, i, txtValue;
			input = document.getElementById("search");
			filter = input.value.toUpperCase();
			table = document.getElementById("datatable");
			tr = table.getElementsByTagName("tr");

			// Loop through all table rows, and hide those who don't match the search query
			for (i = 0; i < tr.length; i++) {
				td = tr[i].getElementsByTagName("td")[1];
				if (td) {
					txtValue = td.textContent || td.innerText;
					if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
					} else {
						tr[i].style.display = "none";
					}
				}
			}
		})
		// function search() {

		// }
	</script>

<?php
}
include "foot.php" ?>