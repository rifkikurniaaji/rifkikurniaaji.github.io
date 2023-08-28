<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("location:login.php");
}
require 'admin/config/koneksi.php';

$transaksi = mysqli_query($koneksi, "SELECT * FROM cart INNER JOIN barang ON cart.id_produk=barang.id_barang JOIN pembayaran ON cart.pembayaran_id=pembayaran.id_pembayaran JOIN jenis ON barang.jenis_id=jenis.id_jenis JOIN user ON cart.id_pembeli=user.id_user JOIN transaksi ON transaksi.id_pembeli=user.id_user JOIN layanan ON layanan.produk_id=barang.id_barang WHERE transaksi.id_pembeli='$_SESSION[id_user]' AND cart.status_c='Ter Checkout'");
$data = mysqli_query($koneksi, "SELECT * FROM cart WHERE id_pembeli='$_SESSION[id_user]' AND status_c='Ter Checkout' AND status_b='Belum Ter Konfirmasi' AND upload_bukti=''");
$sql = mysqli_fetch_assoc($data);
$pembayaran = mysqli_query($koneksi, "SELECT * FROM pembayaran");
if (isset($_POST['addb'])) {
    if (bukti($_POST) > 0) {
        echo "<script>
        alert('Berhasil Upload, Tunggu Konfirmasi dari Admin')
        document.location.href='detail_checkout.php'
        </script>";
    }
}

$totalBayar = 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Anugrahcase</title>
    <!-- Favicon -->
    <link href="assets/images/favicon.png" rel="shortcut icon">
    <!-- CSS -->
    <link href="assets/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="assets/plugins/owl-carousel/owl.carousel.min.css" rel="stylesheet">
    <link href="assets/plugins/owl-carousel/owl.theme.default.min.css" rel="stylesheet">
    <link href="assets/plugins/magnific-popup/magnific-popup.min.css" rel="stylesheet">
    <link href="assets/plugins/sal/sal.min.css" rel="stylesheet">
    <link href="assets/css/theme.css" rel="stylesheet">
    <!-- Fonts/Icons -->
    <link href="assets/plugins/font-awesome/css/all.css" rel="stylesheet">
    <link href="assets/plugins/themify/themify-icons.min.css" rel="stylesheet">
    <link href="assets/plugins/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
</head>

<body data-preloader="1">

    <?php include 'assets/header.php'; ?>
    <!-- Products section -->
    <div class="section-lg">
        <div class="container">
            <h5>Pesanan Saya</h5>
            <div class="row col-spacing-40">
                <!-- <div class="alert alert-succes alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                </div> -->
                <?php if (isset($sql)) : ?>
                    <div class="col-9 col-lg-9">
                        <table class="table cart-table">
                            <thead>
                                <tr>
                                    <th scope="col">Produk</th>
                                    <!--   <th scope="col"></th> -->
                                    <th scope="col">Nama Product</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Jumlah Barang</th>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col">Pembayaran</th>
                                    <th scope="col">Status Barang</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Pengiriman</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transaksi as $tr) : ?>
                                    <?php $total = number_format($tr['harga_c'] * $tr['qty'], 0, ",", ".");
                                    $harga = number_format($tr['harga_c'], 0, ",", ".");
                                    $totalBayar += $tr['harga_c'] * $tr['qty'];
                                    ?>
                                    <tr>
                                        <td class="product-thumbnail">
                                            <a href="#"><img src="admin/gambar/<?php echo $tr['gambar'] ?>" width="270" alt=""></a>
                                        </td>
                                        <td><?php echo $tr['judul'] ?> - <?php echo $tr['nama_jenis'] ?></td>
                                        <td>Rp. <?php echo $harga ?></td>
                                        <td>
                                            <?php echo $tr['qty'] ?>
                                        </td>
                                        <td>Rp. <?php echo $total ?></td>
                                        <td><?php echo $tr['kode'] ?> - <?php echo $tr['keterangan'] ?></td>
                                        <td>
                                            <?php if ($tr['status_b']=="Belum Ter Konfirmasi"): ?>
                                                <span class="badge bg-danger"><?php echo $tr['status_b'] ?></span>
                                            <?php endif ?>
                                            <?php if ($tr['status_b']=="Ter Konfirmasi" OR $tr['status_b']=="Barang Di Siapkan"): ?>
                                                <span class="badge bg-warning"><?php echo $tr['status_b'] ?></span>
                                            <?php endif ?>
                                            <?php if ($tr['status_b']=="Barang dalam Perjalanan" OR $tr['status_b']=="Barang sudah di Terima"): ?>
                                                <span class="badge bg-success"><?php echo $tr['status_b'] ?></span>
                                            <?php endif ?>
                                        </td>
                                        <td><?php echo $tr['tanggal_tf'] ?></td>
                                        <td><?php echo $tr['nama_layanan'] ?><br><?php echo $tr['estimas'] ?> Hari<br>
                                            Rp <?php echo number_format($tr['tarif'],0,",","."); ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-3 mt-2">
                        <label>Upload Bukti Pembayaran</label>
                        <form method="post" enctype="multipart/form-data" class="mb-5">
                            <input type="text" value="<?php echo $_SESSION['id_user'] ?>" name="id_pembeli" hidden>
                            <input type="file" required="" class="form-control" name="gambar">
                            <button name="addb" class="btn btn-sm btn-success">Upload bukti pembayaran</button>
                        </form>



                    </div>
                <?php endif ?>
                <?php if (!isset($sql)) : ?>
                    <div class="col-12 col-xl-12">
                        <table class="table cart-table">
                            <thead>
                                <tr>
                                    <th scope="col">Gambar</th>
                                    <!--   <th scope="col"></th> -->
                                    <th scope="col">Product</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Jumlah Barang</th>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col">Pembayaran</th>
                                    <th scope="col">Status Barang</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Pengiriman</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transaksi as $tr) : ?>
                                    <?php $total = number_format($tr['harga_c'] * $tr['qty'], 0, ",", ".");
                                    $harga = number_format($tr['harga_c'], 0, ",", ".");
                                    ?>
                                    <tr>
                                        <td class="product-thumbnail">
                                            <a href="#"><img src="admin/gambar/<?php echo $tr['gambar'] ?>" width="270" alt=""></a>
                                        </td>
                                        <td><?php echo $tr['judul'] ?> - <?php echo $tr['nama_jenis'] ?></td>
                                        <td>Rp. <?php echo $harga ?></td>
                                        <td>
                                            <?php echo $tr['qty'] ?>
                                        </td>
                                        <td>Rp. <?php echo $total ?></td>
                                        <td><?php echo $tr['kode'] ?> - <?php echo $tr['keterangan'] ?></td>
                                        <td>
                                            <?php if ($tr['status_b']=="Belum Ter Konfirmasi"): ?>
                                                <span class="badge bg-danger"><?php echo $tr['status_b'] ?></span>
                                            <?php endif ?>
                                            <?php if ($tr['status_b']=="Ter Konfirmasi" OR $tr['status_b']=="Barang Di Siapkan"): ?>
                                                <span class="badge bg-warning"><?php echo $tr['status_b'] ?></span>
                                            <?php endif ?>
                                            <?php if ($tr['status_b']=="Barang dalam Perjalanan" OR $tr['status_b']=="Barang sudah di Terima"): ?>
                                                <span class="badge bg-success"><?php echo $tr['status_b'] ?></span>
                                            <?php endif ?>
                                        </td>
                                        <td><?php echo $tr['tanggal_tf'] ?></td>
                                        <td><?php echo $tr['nama_layanan'] ?><br><?php echo $tr['estimas'] ?> Hari<br>
                                            Rp <?php echo number_format($tr['tarif'],0,",","."); ?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                <?php endif ?>

            </div><!-- end row -->

        </div><!-- end container -->
    </div>

    <!-- end Products section -->
    <?php include 'assets/footer.php'; ?>

    <!-- Scroll to top button -->
    <div class="scrolltotop">
        <a class="button-circle button-circle-sm button-circle-dark" href="#"><i class="ti-arrow-up"></i></a>
    </div>
    <!-- end Scroll to top button -->

    <!-- ***** JAVASCRIPTS ***** -->
    <script src="assets/plugins/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=IntersectionObserver"></script>
    <script src="assets/plugins/plugins.js"></script>
    <script src="assets/js/functions.js"></script>
</body>

</html>