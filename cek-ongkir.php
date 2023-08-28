<?php
session_start();
require 'admin/config/koneksi.php';

$kota_asal = $_POST['kota_asal'];
$kota_tujuan = $_POST['kota_tujuan'];
$kurir = $_POST['kurir'];
$berat = $_POST['berat']*1000;

$curl = curl_init();
curl_setopt_array($curl, array(
	CURLOPT_URL => "http://api.rajaongkir.com/starter/cost",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => "origin=".$kota_asal."&destination=".$kota_tujuan."&weight=".$berat."&courier=".$kurir."",
	CURLOPT_HTTPHEADER => array(
		"content-type: application/x-www-form-urlencoded",
		"key: 48ed5aaa5d69b92caf31c45f26c9fe7c"
	),
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
$data= json_decode($response, true);
$kurir=$data['rajaongkir']['results'][0]['name'];
$kotaasal=$data['rajaongkir']['origin_details']['city_name'];
$provinsiasal=$data['rajaongkir']['origin_details']['province'];
$kotatujuan=$data['rajaongkir']['destination_details']['city_name'];
$provinsitujuan=$data['rajaongkir']['destination_details']['province'];
$berat=$data['rajaongkir']['query']['weight']/1000;

$cart = mysqli_query($koneksi, "SELECT * FROM cart INNER JOIN barang ON cart.id_produk=barang.id_barang JOIN user ON cart.id_pembeli=user.id_user WHERE cart.status_c='Keranjang' AND cart.id_pembeli='$_SESSION[id_user]'");

?>
<div class="col-sm-12 border py-12">
	<div class="col-12 col-xl-10">
		<div class="col-12 col-xl-12">
			<div class="bg-grey padding-20 padding-md-30 padding-lg-40 " >
				<h5>Ongkir</h5>
				<form method="post" action="confirm.php" enctype="multipart/form-data">
					<input type="hidden" name="no_order" value="<?php echo $_POST['no_order'] ?>">
					<input type="hidden" name="id_pembayaran" value="<?php echo $_POST['id_pembayaran'] ?>">
					<!-- <input type="hidden" name="id_kirim" value="<?php echo $_POST['id_kirim'] ?>"> -->
					<input type="hidden" name="keterangan" value="<?php echo $_POST['keterangan'] ?>">
					<div class="panel-body">		
						<table width="100%">
							<tr>
								<td width="15%"><b>Kurir</b> </td>
								<td>&nbsp;<b><?=$kurir?></b></td>
							</tr>
							<tr>
								<td>Dari</td>
								<td>: <?=$kotaasal.", ".$provinsiasal?> <input type="hidden" value="<?php echo $kotaasal ?>, <?php echo $provinsiasal ?>" name="asal"></td>
							</tr>
							<tr>
								<td>Tujuan</td>
								<td>: <?=$kotatujuan.", ".$provinsitujuan?><input type="hidden" value="<?php echo $kotatujuan ?>, <?php echo $provinsitujuan ?>" name="tujuan"></td>
							</tr>
							<tr>
								<td>Berat (Kg)</td>
								<td>: <?=$berat?></td>
							</tr>
						</table><br>
						<table class="table table-striped table-bordered ">
							<thead>
								<tr>
									<th>List. </th>
									<th>Nama Layanan</th>
									<th>Tarif</th>
									<th>ETD(Estimates Days)</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($data['rajaongkir']['results'][0]['costs'] as $value): ?>
									<tr>
										<td><input type="radio" name="layanan" value="<?php echo $value['service'] ?>"></td></td>
										<td><?php echo $value['service'] ?>
										<?php foreach ($value['cost'] as $tarif): ?>
											<td align="right">Rp <?php echo number_format($tarif['value'],2,',','.'); ?>
											<input type="hidden" value="<?php echo $tarif['value'] ?>" name="tarif">
										</td>
										<td><?php echo $tarif['etd'] ?><input type="hidden" value="<?php echo $tarif['etd'] ?>" name="etd"></td>
									<?php endforeach ?>
								</tr>
							<?php endforeach ?>

						</tbody>

					</table>
				</div>
				<div class="row">
					<?php foreach ($cart as $crt): ?>
						<input type="checkbox" checked="" value="pilih" name="pilih[]" hidden="">
						<input type="hidden" value="<?php echo $crt['id_produk'] ?>" name="produk_id[]">
						<input type="hidden" value="<?php echo $crt['id_pembeli'] ?>" name="user_id[]">
					<?php endforeach ?>
					<!-- <div class="col-lg-2"></div> -->
					<div class="col-lg-12">
						<h5>Form Pelanggan</h5>
						<div class="col-lg-12">
							<input type="hidden" value="<?php echo $_SESSION['id_user'] ?>" name="id_pembeli">
							<label class="required">Name</label>
							<input type="text" class="form-control" name="nama_p" required>
						</div>
						<div class="col-lg-12">
							<label class="required">Telepon</label>
							<input type="tel" class="form-control" name="telepon_p" required>
						</div>
						<div class="col-lg-12">
							<label> Alamat Lengkap </label>
							<textarea class="form-control" rows="4" name="alamat_p"></textarea>
						</div>
						<div class="col-lg-12">
							<label class="required">Kode Pos</label>
							<input type="text" class="form-control" name="kode_pos" required>
						</div>
						<div class="col-lg-12">
							<label class="required">Email address</label>
							<input type="email" class="form-control" name="email_p" required>
						</div>
						<div class="col-lg-3 mt-4 mb-5">
							<button class="btn btn-lg form-control btn-success" name="submit" type="submit">Confirm</button>
						</div>
					</div>
				</form>
			</div>
		</div>

