<?php
error_reporting(0);
session_start();
if (!isset($_SESSION['id_user'])) {
  header("location:login.php");
}
require 'admin/config/koneksi.php';
$query = mysqli_query($koneksi, "SELECT max(no_order) as kodeTerbesar FROM cart");
$data = mysqli_fetch_array($query);
$kodeBarang = $data['kodeTerbesar'];

    // mengambil angka dari kode barang terbesar, menggunakan fungsi substr
    // dan diubah ke integer dengan (int)
$urutan = (int) substr($kodeBarang, 4, 4);

    // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
$urutan++;

    // membentuk kode barang baru
    // perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
    // misalnya perintah sprintf("%03s", 15); maka akan menghasilkan '015'
    // angka yang diambil tadi digabungkan dengan kode huruf yang kita inginkan, misalnya BRG 
$huruf = "NOB";
$kodeBarang = $huruf . sprintf("%04s", $urutan);

$cart = mysqli_query($koneksi, "SELECT * FROM cart INNER JOIN barang ON cart.id_produk=barang.id_barang JOIN user ON cart.id_pembeli=user.id_user WHERE cart.status_c='Keranjang' AND cart.id_pembeli='$_SESSION[id_user]'");
$data = mysqli_fetch_assoc($cart);

$pembayaran = mysqli_query($koneksi, "SELECT * FROM pembayaran");
$kirim = mysqli_query($koneksi, "SELECT * FROM pengiriman");
if (isset($_POST['transaksi'])) {
  if (transaksi($_POST) > 0) {
    if (statuscheckout($_POST) > 0) {
      echo "<script>
      document.location.href='detail_checkout.php'
      </script>";
    }
  }
}

?>
<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <!-- <link rel="stylesheet" href="asset/bootstrap-3.3.7/dist/css/bootstrap.min.css"> -->
  <link rel="stylesheet" href="asset/select2-4.0.6-rc.1/dist/css/select2.min.css">
  <script src="asset/jquery/jquery-3.3.1.min.js"></script>
  <script src="asset/bootstrap-3.3.7/dist/js/bootstrap.min.js"></script>
  <script src="asset/select2-4.0.6-rc.1/dist/js/select2.min.js"></script>
  <script src="asset/select2-4.0.6-rc.1/dist/js/i18n/id.js"></script>
  <script src="asset/js/app.js"></script>
  <link href="assets/plugins/font-awesome/css/all.css" rel="stylesheet">
  <link href="assets/plugins/themify/themify-icons.min.css" rel="stylesheet">
  <link href="assets/plugins/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
  <title>Anugrahcase</title>
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="row col-spacing-30">

      <div class="col-sm-5 border py-6">
        <div class="col-12 col-xl-10">
          <div class="col-12 col-xl-12">
            <div class="bg-grey padding-20 padding-md-30 padding-lg-40 " >
              <h5 class="font-weight-medium">Proses transaksi</h5>
              <form method="post" id="ongkir">
                <table class="table cart-totals">
                  <tbody>
                    <tr>
                      <th scope="row">Product per Item x <?php echo mysqli_num_rows($cart) ?></th>
                      <!-- <td>$20.00</td> -->
                    </tr>
                    <tr>
                      <span hidden="">
                        <?php $nul = 0; ?>
                        <?php $brt = 0; ?>
                        <?php foreach ($cart as $crt) : ?>
                          <?php
                          $subtotal = $nul += $crt['harga_c'] * $crt['qty'] + $crt['harga_ongkir'];
                          $ber=$crt['berat_barang']*$crt['qty'];
                          $totoalberat=$brt+=$ber;
                          ?>
                          <input type="text" value="<?php echo $kodeBarang ?>" name="no_order">

                        <?php endforeach ?>
                      </span>
                      <td>
                        <div class="">
                          Cart Subtotal <span id="cartTotal" class="float-right"> Rp <?= number_format($subtotal,0,",",".") ?></span>

                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2"><strong>Pembayaran</strong></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <select name="package" class="form-control" id="package">
                          <option>-- Pilih --</option>
                          <?php foreach ($pembayaran as $p) : ?>
                            <option data-id_pembayaran="<?php echo $p['id_pembayaran'] ?>" data-keterangan="<?php echo $p['keterangan'] ?>"><?php echo $p['keterangan'] ?></option>
                          <?php endforeach ?>
                        </select>
                        <label for="id_pembayaran" hidden="">Id</label>
                        <input type="text" hidden="" name="id_pembayaran" readonly />
                        <label for="keterangan" hidden="">Keterangan</label>
                        <input type="text" name="keterangan" hidden="" readonly />
                        <input type="hidden" value="<?php echo $_SESSION['id_user'] ?>" name="id_user">
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <label class="control-label">Kota Asal:</label>
                        <select class="form-control" id="kota_asal" name="kota_asal" required="">
                        </select>

                        <label class="control-label">Kota Tujuan</label>
                        <select class="form-control" id="kota_tujuan" name="kota_tujuan" required="">
                          <option></option>
                        </select>

                        <label class="control-label col-sm-3">Kurir</label>
                        <select class="form-control" id="kurir" name="kurir" required="">
                          <option value="jne">JNE</option>
                          <option value="tiki">TIKI</option>
                          <option value="pos">POS INDONESIA</option>
                        </select>

                        <label class="control-label col-sm-3">Berat (Kg)</label>
                        <input type="text" class="form-control" value="<?php echo $totoalberat ?>" readonly id="berat" name="berat" required="">
                      </td>
                    </tr>
                  </tbody>
                </table>
                <!-- <button name="transaksi" class="btn button-md button-dark button-fullwidth margin-top-10" type="submit">Confirm order</button> -->
                <button type="submit" class="btn btn-primary btn-block">Cek</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6" id="response_ongkir">
      </div>
    </div>
    <!-- <div class="row">
      <div class="col-lg-12" id="response_ongkir">
      </div>
    </div> -->
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
  <script type="text/javascript">
    $('#package').on('change', function(){
  // ambil data dari elemen option yang dipilih
  const id_pembayaran = $('#package option:selected').data('id_pembayaran');
  const keterangan = $('#package option:selected').data('keterangan');
  
  // kalkulasi total harga
  const totalDiscount = (keterangan)
  const total = id_pembayaran - totalDiscount;
  
  // tampilkan data ke element
  $('[name=id_pembayaran]').val(id_pembayaran);
  $('[name=keterangan]').val(totalDiscount);
  
  // $('#total').text(`Rp ${total}`);
});
</script>
</body>
</html>