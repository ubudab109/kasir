<script type="text/javascript">
	document.title = "Transaksi Baru";
	document.getElementById('transaksi').classList.add('active');
</script>
<script type="text/javascript">
	$(document).ready(function() {
		if ($.trim($('#contenth').text()) == "") {
			$('#prosestran').attr("disabled", "disabled");
			$('#prosestran').attr("title", "tambahkan barang terlebih dahulu");
			$('#prosestran').css("background", "#ccc");
			$('#prosestran').css("cursor", "not-allowed");
		}
	})

	// tambah script untuk autocomplete search dropdown produk
</script>


<div class="content">
	<div class="padding">
		<div class="bgwhite">
			<div class="padding">
				<h3 class="jdl">Entry Transaksi Baru</h3>



				<table class="table">
					<thead class="thead-inverse">
						<tr>
							<th>Pilih Barang</th>
							<!-- <th>Stok Barang</th>
							<th>Harga</th> -->
							<th>Jumlah Beli</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
						<form class="form-inline" id="subt" method="post" style="padding-top: 30px;">

							<tr class="no-border">
								<td>
									<div class="input-group">
										<input type="hidden" class="form-control mb-2 mr-sm-2" id="stock" readonly disabled>
										<input type="hidden" class="form-control mb-2 mr-sm-2" id="harga_jual" readonly disabled>
										<select style="width: 372px;cursor: pointer;" required="required" class="chosen" id="id_barang" name="id_barang">
											<?php

											$data = $root->con->query("select * from barang");
											while ($f = $data->fetch_assoc()) {
												echo "<option value='$f[id_barang]'>$f[nama_barang] (stock : $f[stok] | Harga : " . number_format($f['harga_jual']) . ")</option>";
											}
											?>
										</select>
										<p class="text-danger" id="err_nama"></p>
									</div>
								</td>
								<td>
									<div class="input-group">
										<input required="required" class="form-control mb-2 mr-sm-2" id="jumlah_beli" type="number" name="jumlah">
										<p class="text-danger" id="err_jumlah"></p>
										<input type="hidden" class="form-control mb-2 mr-sm-2" id="trx" name="trx" value="<?php echo date("d") . "/AF/" . $_SESSION['id'] . "/" . date("y") ?>">
									</div>
								</td>
								<td>
									<button class="btnblue" type="submit" id="simpan"><i class="fa fa-save"></i> Simpan</button>
								</td>
							</tr>
						</form>

					</tbody>
				</table>



			</div>
		</div>
		<br>
		<div class="bgwhite">
			<div class="padding">
				<h3 class="jdl">Data transaksi</h3>
				<div id="dataBarang">

				</div>

			</div>
		</div>


	</div>
</div>
<script>
	$(document).ready(() => {
		$('#dataBarang').load("data_sub_barang.php");

		// function getTotalAjax() {

		// }
		// getTotalAjax()
		$('#simpan').click((e) => {
			e.preventDefault()
			// var data = $("subt").serialize();
			var idBarang = document.getElementById('id_barang').value;
			var jumlahBeli = document.getElementById('jumlah_beli').value;
			var trx = document.getElementById('trx').value;


			if (idBarang == "") {
				document.getElementById('err_nama').innerHTML = "Nama Barang Harus Diisi";
			} else {
				document.getElementById('err_nama').innerHTML = "";
			}

			if (jumlahBeli == "") {
				document.getElementById('err_jumlah').innerHTML = "Jumlah Beli Harus Diisi";
			} else {
				document.getElementById('err_jumlah').innerHTML = ""
			}

			if (idBarang != "" && jumlahBeli != "") {
				$.ajax({
					type: 'POST',
					url: "handler.php?action=tambah_tempo",
					data: {
						id_barang: idBarang,
						jumlah: jumlahBeli,
						trx: trx,
					},
					success: function() {
						$('#dataBarang').load("data_sub_barang.php");
						$.ajax({
							url: "getGrand.php",
							method: "GET",
						}).then(function(e) {
							$("#total_bayar").val(e)
						})
						document.getElementById('subt').reset()
					},
					error: function() {
						alert("Terjadi Kesalahan");
					}
				})
			}
		})
	})
</script>

<script>
	$('.chosen').chosen({
		width: '80%',
		height: '40%',
		allow_single_deselect: true
	});
</script>

<?php
include "foot.php";
?>