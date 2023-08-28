<?php
session_start();
require 'admin/config/koneksi.php';

$where = " WHERE 1=1 ";
if (isset($_GET['jenis'])) {
    $where .= " AND id_jenis = '" . $_GET['jenis'] . "' ";
}

if (isset($_GET['key'])) {
    $where .= " AND judul LIKE '%" . $_GET['key'] . "%' ";
}
$barang = mysqli_query($koneksi, "SELECT * FROM barang INNER JOIN jenis ON barang.jenis_id=jenis.id_jenis $where ");
if (isset($_POST['add'])) {
    if (addcart($_POST) > 0) {
        echo "<script>
        document.location.href=window.history.go(-1)
        </script>";
    }
}
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
    <style>
        .jumbotron {
            background-repeat: no-repeat;
            background-size: 100%;
            height: 40vh;
        }

        .jumb1 {
            background-image: url('assets/images/photo2.png');
        }

        .jumb2 {
            background-image: url('assets/images/photo1.png');
        }

        .jumb3 {
            background-image: url('assets/images/photo4.jpg');
        }
    </style>
</head>

<body data-preloader="1">

    <?php include 'assets/header.php'; ?>

    <!-- Products section -->
    <div class="section">
        <div class="container">

            <!-- Products -->
            <div class="row col-spacing-30">
                <div class="col-sm-3 border py-3">
                    <h5 class="text-center">Semua Kategori</h5>

                    <div class="list-group">
                        <?php
                        $jenis = mysqli_query($koneksi, "SELECT * FROM jenis");
                        while ($j = mysqli_fetch_array($jenis)) {
                        ?>
                            <a href="product.php?jenis=<?= $j['id_jenis'] ?>" class="list-group-item list-group-item-action"><?= $j['nama_jenis']; ?></a>
                        <?php } ?>
                    </div>
                </div>
                <div class="row col-spacing-30 mb-5">
                    <!-- <div class="col-sm-9"> -->
                    <!-- Product box 1 -->
                    <?php foreach ($barang as $bk) : ?>
                        <?php $harga = number_format($bk['harga'], 0, ",", "."); ?>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="product-box">
                                <div class="product-img">
                                    <!-- Product IMG -->
                                    <a class="product-img-link" href="detail.php?key=<?php echo $bk['id_barang'] ?>">
                                        <img src="admin/gambar/<?php echo $bk['gambar'] ?>" alt="" width="100px">
                                    </a>
                                    <!-- Badge (left) -->
                                    <div class="product-badge-left">
                                        <!-- you can add: 'red/green' -->
                                        <span>New</span>
                                    </div>
                                    <!-- Badge (right) -->
                                    <div class="product-badge-right red">
                                        <!-- you can add: 'red/green' -->
                                        <span>-50%</span>
                                    </div>
                                    <!-- Add to Cart -->
                                    <div class="add-to-cart">
                                        <?php if (!isset($_SESSION['id_user'])) : ?>
                                            <!-- <hr> -->
                                            <a href="login.php">
                                                <p style="text-align: center;"><i class="fa fa-shopping-cart"></i> Tambah Troli</p>
                                            </a>
                                        <?php endif ?>
                                        <?php if (isset($_SESSION['id_user'])) : ?>
                                            <?php if ($_SESSION['role'] == "Pembeli") : ?>
                                                <form method="post">
                                                    <input type="hidden" value="<?php echo $bk['id_barang'] ?>" name="id_produk">
                                                    <input type="hidden" value="1" name="qty">
                                                    <input type="hidden" value="<?php echo $_SESSION['id_user'] ?>" name="id_pembeli">
                                                    <input type="hidden" value="<?php echo $bk['harga'] ?>" name="harga_c">
                                                    <input type="hidden" value="Keranjang" name="status_c">
                                                    <input type="hidden" value="Belum Ter Konfirmasi" name="status_b">
                                                    <button name="add" type="submit"><i class="fa fa-shopping-cart"></i> Tambah Troli</button>
                                                </form>
                                            <?php endif ?>
                                        <?php endif ?>
                                        <!--  <a href="{{ route('cart') }}"><i class="fa fa-shopping-cart"></i> Add to Cart</a> -->
                                    </div>

                                </div>
                                <div class="product-title">
                                    <!-- Product Title -->
                                    <h6 class="font-weight-medium"><a href="#"><?php echo $bk['judul'] ?></a></h6>
                                    <h6 class="font-weight-medium"><a href="#">Brand : <?php echo $bk['nama_jenis'] ?></a></h6>
                                    <!-- Product Price -->
                                    <div class="price">
                                        <!-- <del>$98</del> -->
                                        <p class="card-text fas fa-tag"></p>
                                        <span>Rp. <?php echo $harga; ?></span>
                                        <span class="text pull-right" style="float: right;">Stok : <?php echo $bk['stok_barang'] ?> pcs</span>
                                    </div>
                                    <hr>
                                    <?php if (!isset($_SESSION['id_user'])) : ?>
                                        <button name="add-to-card"type="submit"><a href="login.php"><i class="fa fa-shopping-cart"></i> Tambah Troli</a></button>
                                        &nbsp
                                        &nbsp
                                        &nbsp
                                        &nbsp
                                        &nbsp
                                        <button name="add-to-card"type="submit"><a href="detail.php?key=<?php echo $bk['id_barang'] ?>"><i class="fa fa-list"></i> Detail</a></button>
                                    <?php endif ?>
                                    <?php if (isset($_SESSION['id_user'])) : ?>
                                        <?php if ($_SESSION['role'] == "Pembeli") : ?>
                                            <form method="post">
                                                <input type="hidden" value="<?php echo $bk['id_barang'] ?>" name="id_produk">
                                                <input type="hidden" value="1" name="qty">
                                                <input type="hidden" value="<?php echo $_SESSION['id_user'] ?>" name="id_pembeli">
                                                <input type="hidden" value="<?php echo $bk['harga'] ?>" name="harga_c">
                                                <input type="hidden" value="Keranjang" name="status_c">
                                                <input type="hidden" value="Belum Ter Konfirmasi" name="status_b">
                                                <button name="add" type="submit"><i class="fa fa-shopping-cart"></i> Tambah Troli</button>
                                                <button name="add" type="submit"><a href="detail.php?key=<?php echo $bk['id_barang'] ?>"><i class="fa fa-list" style="padding: 1px;"></i> Detail</a></button>
                                            </form>
                                        <?php endif ?>
                                    <?php endif ?>

                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>

                <!-- Product box 2 -->
                <!-- Product box 3 -->
                <!-- Product box 4 -->
                <!-- Product box 5 -->
                <!-- Product box 6 -->
            </div><!-- end row -->
            <!-- Pagination -->
            <!-- <div class="text-center margin-top-50">
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item"><a class="page-link" href="#">«</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">»</a></li>
                    </ul>
                </nav>
            </div> -->
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