<?php
session_start();
require 'admin/config/koneksi.php';
if (!isset($_GET['key'])) {
    echo "<script>
        document.location.href=window.history.go(-1)
        </script>";
}
if (isset($_GET['key'])) {
    if ($_GET['key'] == "") {
        echo "<script>
        document.location.href=window.history.go(-1)
        </script>";
    }
}

if (isset($_POST['add'])) {
    if (addcart($_POST) > 0) {
        echo "<script>
		document.location.href=window.history.go(-1)
		</script>";
    }
}

$visit = mysqli_query($koneksi, "UPDATE barang SET dilihat = dilihat + 1 WHERE id_barang = $_GET[key] ");
$barang = mysqli_query($koneksi, "SELECT * FROM barang INNER JOIN jenis ON barang.jenis_id=jenis.id_jenis WHERE id_barang=$_GET[key]");
$data = mysqli_fetch_assoc($barang);
$harga = number_format($data['harga'], 0, ",", ".");
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
    <div class="section">
        <div class="container">
            <div class="row col-spacing-40">
                <div class="col-12 col-xl-8">
                    <h5 class="mb-2"> Details Produk</h5>
                    <hr>
                    <div class="form-row">
                        <div class="col-lg-2">
                            <label>Nama Produk</label>
                        </div>
                        <div class="col-lg-1">:</div>
                        <div class="col-9">
                            <label><?php echo $data['judul'] ?></label>
                        </div>
                        <div class="col-lg-2">
                            <label>Kategori Produk</label>
                        </div>
                        <div class="col-lg-1">:</div>
                        <div class="col-9">
                            <label><?php echo $data['nama_jenis'] ?></label>
                        </div>
                        <div class="col-lg-2">
                            <label>Detail Produk</label>
                        </div>
                        <div class="col-lg-1">:</div>
                        <div class="col-9">
                            <label><?php echo $data['detail_barang'] ?></label>
                        </div>
                        <div class="col-lg-2">
                            <label>Stok Produk</label>
                        </div>
                        <div class="col-lg-1">:</div>
                        <div class="col-9">
                            <label><?php echo $data['stok_barang'] ?></label>
                        </div>
                        <div class="col-lg-2">
                            <label>Harga Produk</label>
                        </div>
                        <div class="col-lg-1">:</div>
                        <div class="col-9">
                            <label><i class="item_price">Rp. <?php echo $harga; ?></i></label>
                        </div>
                        <div class="col-lg-2">
                            <label>Berat Produk</label>
                        </div>
                        <div class="col-lg-1">:</div>
                        <div class="col-9">
                            <label><?php echo $data['berat_barang']; ?>Kg</label>
                        </div>
                        <div class="groups-btn remove">
                            <input id="product-add-to-cart" class="btn btn-produk-add-to-cart bold_hidden" data-btn-addtocart="" type="submit" name="add" value="Tambah Ke Keranjang" data-form-id="#add-to-cart-form" style="display: none;" data-event-queue-button="0">
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4">
                    <img src="admin/gambar/<?php echo $data['gambar'] ?>">
                </div>
            </div><!-- end row -->
            <div class="add-to-cart">
                <?php if (!isset($_SESSION['id_user'])) : ?>
                    <hr>
                    <button name="add-to-card"type="submit"><a href="login.php"><i class="fa fa-shopping-cart" ></i> Tambah Troli</p></a></button>
                <?php endif ?>
                <?php if (isset($_SESSION['id_user'])) : ?>
                    <?php if ($_SESSION['role'] == "Pembeli") : ?>
                        <form method="post">
                            <input type="hidden" value="<?php echo $data['id_barang'] ?>" name="id_produk">
                            <input type="hidden" value="1" name="qty">
                            <input type="hidden" value="<?php echo $_SESSION['id_user'] ?>" name="id_pembeli">
                            <input type="hidden" value="<?php echo $data['harga'] ?>" name="harga_c">
                            <input type="hidden" value="Keranjang" name="status_c">
                            <input type="hidden" value="Belum Ter Konfirmasi" name="status_b">
                            <hr>
                            <button name="add" type="submit"><p style="text-align: center;"><i class="fa fa-shopping-cart"> Tambah Troli</p></i></button>
                        </form>
                    <?php endif ?>
                <?php endif ?>
                <!--  <a href="{{ route('cart') }}"><i class="fa fa-shopping-cart"></i> Add to Cart</a> -->
            </div>
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