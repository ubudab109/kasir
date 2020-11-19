<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
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
											<option value="" selected disabled>Pilih Barang </option>
											<?php

											$data = $root->con->query("select * from barang");
											while ($f = $data->fetch_assoc()) {
												echo "<option value='$f[id_barang]' data-img='images/produk/$f[foto_produk]'>$f[nama_barang] (stock : $f[stok] | Harga : " . number_format($f['harga_jual']) . ")</option>";
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
				<div class="gambar">
					<img src="images/produk/" id="gambar" alt="" style="display: block; margin-left: auto; margin-right: auto; width: 20%;">
				</div>



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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
				<button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<select name="" id="jenis" class="form-control">
					<option value="" selected disabled>Pilih Jenis Potongan : </option>
					<option value="Potongan">Potongan</option>
					<option value="Diskon">Diskon</option>
				</select>
				<br>
				<form action="#" id="diskon">
					<div class="form-group">
						<label for="jumlah_diskon">Jumlah Diskon Dalam % :</label>
						<div class="input-group mb-2">
							<input type="number" id="jumlah_diskon" min="0" max="100" step="0.01" name="jumlah_diskon" class="form-control">
							<div class="input-group-prepend">
								<div class="input-group-text">%</div>
							</div>
						</div>
						<p class="text-danger" id="err_jumlah_diskon"></p>
						<input type="hidden" id="barang_id" name="id_barang" class="form-control">
					</div>
					<br>
					<button type="submit" id="simpan_diskon" class="btn btn-primary">Simpan</button>
				</form>
				<form action="#" id="potongan">
					<div class="form-group">
						<label for="jumlah_potongan">Jumlah Potongan Dalam Rupiah :</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<div class="input-group-text">Rp. </div>
							</div>
							<input type="number" id="jumlah_potongan" name="jumlah_potongan" class="form-control">
						</div>
						<p class="text-danger" id="err_jumlah_potongan"></p>
						<input type="hidden" id="barang_id_potongan" name="id_barang" class="form-control">
					</div>
					<br>
					<button type="submit" id="simpan_potongan" class="btn btn-primary">Simpan</button>


				</form>
			</div>
			<div class="modal-footer">
				<!-- <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
				<!-- <button type="button" class="btn btn-primary">Save changes</button> -->
			</div>
		</div>
	</div>
</div>

<!-- modal edit diskon -->
<div class="modal fade" id="editDiskon" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
				<button type="button" class="close" id="close_diskon" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<br>
				<form action="#" id="edit_diskon">
					<div class="form-group">
						<label for="jumlah_diskon">Jumlah Diskon Dalam % :</label>
						<div class="input-group mb-2">
							<input type="number" id="edit_jumlah_diskon" min="0" max="100" step="0.01" name="jumlah_diskon" class="form-control">
							<div class="input-group-prepend">
								<div class="input-group-text">%</div>
							</div>
						</div>
						<p class="text-danger" id="err_edit_diskon"></p>
						<input type="hidden" id="edit_barang_id" name="id_barang" class="form-control">
					</div>
					<br>
					<label>* Jika ingin mengubah jenis potongan kosongkan input jumlah diskon lalu input kembali jenis potongan</label><br>
					<label>* Jika ingin merubah jumlah diskon input kembali jumlah potongan diskon</label><br><br>
					<button type="submit" id="ubah_diskon" class="btn btn-primary">Ubah</button>

				</form>

			</div>
			<div class="modal-footer">
				<!-- <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
				<!-- <button type="button" class="btn btn-primary">Save changes</button> -->
			</div>
		</div>
	</div>
</div>

<!-- modal edit potongan -->
<div class="modal fade" id="editPotongan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
				<button type="button" class="close" id="close_potongan" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<br>
				<form action="#" id="edit_potongan">
					<div class="form-group">
						<label for="jumlah_diskon">Jumlah Potongan Dalam Rupiah :</label>
						<div class="input-group mb-2">
							<div class="input-group-prepend">
								<div class="input-group-text">Rp. </div>
							</div>
							<input type="number" id="edit_jumlah_potongan" name="jumlah_diskon" class="form-control">
							<p class="text-danger" id="err_edit_potongan"></p>

						</div>

						<input type="hidden" id="edit_id_barang" name="id_barang" class="form-control">
					</div>
					<br>
					<label>* Jika ingin mengubah jenis potongan kosongkan input jumlah potongan lalu input kembali jenis potongan</label><br>
					<label>* Jika ingin merubah jumlah potongan input kembali jumlah potongan dalam rupiah</label><br><br>
					<button type="submit" id="ubah_potongan" class="btn btn-primary">Ubah</button>

				</form>

			</div>
			<div class="modal-footer">
				<!-- <button type="button" id="close" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
				<!-- <button type="button" class="btn btn-primary">Save changes</button> -->
			</div>
		</div>
	</div>
</div>

<script>
	function potongan(id) {
		document.getElementById('barang_id').value = id;
		document.getElementById('barang_id_potongan').value = id;
	}

	function editDiskon(id, jumlahDiskon) {
		document.getElementById("edit_jumlah_diskon").value = jumlahDiskon;
		document.getElementById("edit_barang_id").value = id;
	}

	function editPotongan(id, jumlahPotongan) {
		document.getElementById("edit_jumlah_potongan").value = jumlahPotongan;
		document.getElementById("edit_id_barang").value = id;
	}
	$(document).ready(function() {
		$("#diskon").hide();
		$("#potongan").hide();


		$("#jenis").change(function(e) {
			e.preventDefault();
			var value = $("#jenis").find(":selected").val();

			if (value == "Potongan") {
				$("#potongan").show();
				$("#diskon").hide();
			} else if (value == "Diskon") {
				$("#diskon").show();
				$("#potongan").hide();
			}
		})
	})
</script>
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
						$(".chosen").val('').trigger("chosen:updated");
					},
					error: function() {
						alert("Terjadi Kesalahan");
					}
				})
			}
		})



		// untuk diskon
		$("#simpan_diskon").click((e) => {
			e.preventDefault();
			var jumlahDiskon = document.getElementById("jumlah_diskon").value;
			var idBarang = document.getElementById("barang_id").value;
			if (jumlahDiskon == "") {
				alert("Jumlah Diskon Harus Diisi");
			} else {
				$.ajax({
					type: 'POST',
					url: "handler.php?action=tambah_diskon_kasir",
					data: {
						id_barang: idBarang,
						jumlah_diskon: jumlahDiskon,
					},
					success: function(res) {
						if (res == 0) {
							$('#dataBarang').load("data_sub_barang.php");
							$.ajax({
								url: "getGrand.php",
								method: "GET",
							}).then(function(e) {
								$("#total_bayar").val(e)
							})
							$("#close").trigger("click");
						} else {
							alert("Terjadi Kesalahan Silahkan Ulangi");
						}
					}
				})
			}

		})
		// validasi jumlah diskon
		$("#jumlah_diskon").keyup((e) => {
			e.preventDefault();
			var jumlahDiskon = $("#jumlah_diskon").val();
			$.ajax({
				url: "handler.php?action=validasi_diskon",
				type: "POST",
				data: {
					jumlah_diskon: jumlahDiskon
				},
				success: function(res) {
					if (res == 0) {
						document.getElementById("err_jumlah_diskon").innerHTML = "Diskon Tidak Boleh Melebihi 100%";
						$("#simpan_diskon").prop('disabled', true);
					} else {
						$("#simpan_diskon").prop('disabled', false);
						document.getElementById("err_jumlah_diskon").innerHTML = "";
					}
				}
			})
		})

		// untuk potongan
		$("#simpan_potongan").click(function(e) {
			e.preventDefault();
			var jumlahPotongan = document.getElementById('jumlah_potongan').value;
			var idBarang = document.getElementById('barang_id_potongan').value;
			if (jumlahPotongan == "") {
				alert("Jumlah Potongan Harus Diisi");
			} else {
				$.ajax({
					type: 'POST',
					url: "handler.php?action=tambah_potongan_kasir",
					data: {
						id_barang: idBarang,
						jumlah_potongan: jumlahPotongan,
					},
					success: function(res) {
						if (res == 0) {
							$('#dataBarang').load("data_sub_barang.php");
							$.ajax({
								url: "getGrand.php",
								method: "GET",
							}).then(function(e) {
								$("#total_bayar").val(e)
							})
							$("#close").trigger("click");
						} else {
							alert("Terjadi Kesalahan Silahkan Ulangi Kembali");
						}
					}
				})

			}
		})

		// validasi jumlah potongan
		$("#jumlah_potongan").keyup((e) => {
			var jumlahPotongan = $("#jumlah_potongan").val();
			var idBarang = document.getElementById('barang_id_potongan').value;

			$.ajax({
				url: 'potongan_validate.php?id_barang=' + idBarang,
				method: 'POST',
				data: {
					jumlah_potongan: jumlahPotongan
				},
				success: function(res) {
					if (res == 0) {
						document.getElementById("err_jumlah_potongan").innerHTML = "Potongan Tidak Boleh Melebihi Harga Barang";
						$("#simpan_potongan").prop('disabled', true);
					} else {
						$("#simpan_potongan").prop('disabled', false);
						document.getElementById("err_jumlah_potongan").innerHTML = "";
					}
				}
			})

		})

		// edit potongan
		$("#ubah_potongan").click((e) => {
			e.preventDefault();

			var jumlahPotongan = document.getElementById("edit_jumlah_potongan").value;
			var idBarang = document.getElementById("edit_id_barang").value;

			$.ajax({
				url: "handler.php?action=edit_potongan_kasir",
				type: "POST",
				data: {
					id_barang: idBarang,
					jumlah_potongan: jumlahPotongan
				},
				success: function(res) {
					if (res == 0) {
						$('#dataBarang').load("data_sub_barang.php");
						$.ajax({
							url: "getGrand.php",
							method: "GET",
						}).then(function(e) {
							$("#total_bayar").val(e)
						})
						$("#close_potongan").trigger("click");
					} else {
						alert("Terjadi Kesalahan Silahkan Ulangi Kembali");
					}
				}

			})
		})

		$("#edit_jumlah_potongan").keyup((e) => {
			var jumlahPotongan = $("#edit_jumlah_potongan").val();
			var idBarang = document.getElementById('edit_id_barang').value;

			$.ajax({
				url: 'potongan_validate.php?id_barang=' + idBarang,
				method: 'POST',
				data: {
					jumlah_potongan: jumlahPotongan
				},
				success: function(res) {
					if (res == 0) {
						document.getElementById("err_edit_potongan").innerHTML = "Potongan Tidak Boleh Melebihi Harga Barang";
						$("#ubah_potongan").prop('disabled', true);
					} else {
						$("#ubah_potongan").prop('disabled', false);
						document.getElementById("err_edit_potongan").innerHTML = "";
					}
				}
			})

		})

		// edit diskom
		$("#ubah_diskon").click((e) => {
			e.preventDefault();
			var jumlahPotongan = document.getElementById("edit_jumlah_diskon").value;
			var idBarang = document.getElementById("edit_barang_id").value;
			$.ajax({
				url: "handler.php?action=edit_diskon_kasir",
				type: "POST",
				data: {
					id_barang: idBarang,
					jumlah_diskon: jumlahPotongan
				},
				success: function(res) {
					if (res == 0) {
						$('#dataBarang').load("data_sub_barang.php");
						$.ajax({
							url: "getGrand.php",
							method: "GET",
						}).then(function(e) {
							$("#total_bayar").val(e)
						})
						$("#close_diskon").trigger("click");
					} else {
						alert("Terjadi Kesalahan Silahkan Ulangi Kembali");
					}
				}
			})
		})

		// validasi jumlah diskon
		$("#edit_jumlah_diskon").keyup((e) => {
			e.preventDefault();
			var jumlahDiskon = $("#edit_jumlah_diskon").val();

			$.ajax({
				url: "handler.php?action=validasi_diskon",
				type: "POST",
				data: {
					jumlah_diskon: jumlahDiskon
				},
				success: function(res) {
					if (res == 0) {
						document.getElementById("err_edit_diskon").innerHTML = "Diskon Tidak Boleh Melebihi 100%";
						$("#ubah_diskon").prop('disabled', true);
					} else {
						$("#ubah_diskon").prop('disabled', false);
						document.getElementById("err_edit_diskon").innerHTML = "";
					}
				}

			})
		})


	})
</script>

<script>
	$('.chosen').chosen({
		width: '80%',
		height: '40%',
		allow_single_deselect: true
	});

	$(document).ready(function() {
		$('#id_barang').change((e) => {
			e.preventDefault();
			var selected = $(this).find('option:selected');
			var extra = selected.data('img');
			console.log(extra);
			$('#gambar').attr('src', extra);
		})
	})
</script>

<?php
include "foot.php";
?>