<?php
session_start();
require 'admin/config/koneksi.php';
$where = " WHERE 1=1 ";
$orderby = "";
if (isset($_GET['jenis'])) {
    $where .= " AND id_jenis = '" . $_GET['jenis'] . "' ";
}

if (isset($_GET['populer'])) {
    $orderby .= " ORDER BY dilihat DESC";
}


$barang = mysqli_query($koneksi, "SELECT * FROM barang INNER JOIN jenis ON barang.jenis_id=jenis.id_jenis $where $orderby");
$barangterbaru = mysqli_query($koneksi, "SELECT * FROM barang INNER JOIN jenis ON barang.jenis_id=jenis.id_jenis ORDER BY id_barang DESC");
$rekomendasi = mysqli_query($koneksi, "SELECT * FROM barang INNER JOIN jenis ON barang.jenis_id=jenis.id_jenis WHERE rekomendasi = 1 ORDER BY id_barang DESC");
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <!-- Fonts/Icons -->
    <link href="assets/plugins/font-awesome/css/all.css" rel="stylesheet">
    <link href="assets/plugins/themify/themify-icons.min.css" rel="stylesheet">
    <link href="assets/plugins/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        body{
            background: #000 ;
            background-attachment: fixed;
            background-blend-mode: hard-light;
            font-family: sans-serif;
        }
        .menu {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .menu li a {
            padding: 10px 20px;
            border: 1px solid #ddd;
            display: inline-block;
        }

        .bg-cyan {
            background-color: darkcyan;
            color: #fff;
        }

        .items {
            width: 90%;
            margin: 0px auto;
            margin-top: 100px
        }

        .slick-slide {
            margin: 10px
        }

        .slick-slide img {
            width: 100%;
            border: 0px solid #fff
        }
        .button{
            background: #ddd;
        }
    </style>
</head>

<body data-preloader="1">

    <?php include 'assets/header.php'; ?>
    <div class=" mt-3">
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" style="height:300px">
            <div class="carousel-inner" style="height:550px">
                <div class="carousel-item active">
                    <img src="assets/images/cassio.jpg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="assets/images/butter-banner-min.jpg" class="d-block w-100" alt="...">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <div class="section" style="background-color: black" ><BR><BR><BR><BR><BR><BR><BR>
        <div class="container" data-aos="fade-down"><hr>
            <h4 class="text-center" style="color: cornsilk;">Koleksi Produk</h4>
            <hr style="height:3px;background-color:white;width:100px;margin:0 auto 30px auto">
            <ul class="menu my-3">
                <li><a href="?populer" <?= (isset($_GET['populer'])) ? 'class="bg-cyan"' : ''; ?>>Populer</a></li>
                <?php
                $jenis = mysqli_query($koneksi, "SELECT * FROM jenis");
                while ($j = mysqli_fetch_array($jenis)) {
                ?>
                    <li><a href="?jenis=<?= $j['id_jenis'] ?>" <?= (isset($_GET['jenis']) && $_GET['jenis'] == $j['id_jenis']) ? 'class="bg-cyan"' : ''; ?>><?= $j['nama_jenis']; ?></a></li>
                <?php } ?>
            </ul>

            <div class="row col-spacing-30">
                <!-- Product box 1 -->
                <?php foreach ($barang as $bk) : ?>
                    <?php $harga = number_format($bk['harga'], 0, ",", "."); ?>
                    <div class="col-12 col-sm-6 col-lg-3" style="width: 18rem;">
                        <div class="product-box" style="background: black;">
                            <div class="product-img">
                                <!-- Product IMG -->
                                <a class="product-img-link" href="detail.php?key=<?php echo $bk['id_barang'] ?>">
                                    <img src="admin/gambar/<?php echo $bk['gambar'] ?>" alt="" width="120px">
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
                                        <a href="login.php"><i class="fa fa-shopping-cart"></i> Tambah Troli</a>
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
                                                <button name="add-to-card" type="submit" style="padding: 10px;"><i class="fa fa-shopping-cart"></i> Tambah Troli</button>
                                            </form>
                                        <?php endif ?>
                                    <?php endif ?>
                                    <!--  <a href="{{ route('cart') }}"><i class="fa fa-shopping-cart"></i> Add to Cart</a> -->
                                </div>

                            </div>
                            <div class="product-title">
                                <!-- Product Title -->
                                <h6 class="font-weight-medium"><a href="#"> <?php echo $bk['judul'] ?></a></h6>
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
                                    <button name="add-to-cart" type="submit"><a href="login.php"><i class="fa fa-shopping-cart"></i> Tambah Troli</a></button>
                                    &nbsp
                                    &nbsp
                                    &nbsp
                                    &nbsp
                                    <button name="add-to-cart" type="submit"><a href="detail.php?key=<?php echo $bk['id_barang'] ?>"><i class="fa fa-list" style="float: ;right"></i> Detail</a></button>
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
                <!-- Product box 2 -->
                <!-- Product box 3 -->
                <!-- Product box 4 -->
                <!-- Product box 5 -->
                <!-- Product box 6 -->
            </div><!-- end row -->
        </div>
    </div>



    
    <!-- Products section -->
    <div class="section" style="background: blue;">
        <div class="container">
            <h4 class="text-center" style="color: cornsilk;">Produk Baru</h4>
            <hr style="height:3px;background-color:white;width:100px;margin:0 auto 30px auto">
            <!-- Products -->
            <div class="row col-spacing-30 mb-5">
                <!-- Product box 1 -->
                <?php foreach ($barangterbaru as $bk) : ?>
                    <?php $harga = number_format($bk['harga'], 0, ",", "."); ?>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="product-box" style="background-color:#fff;">
                            <div class="product-img">
                                <!-- Product IMG -->
                                <a class="product-img-link" href="detail.php?key=<?php echo $bk['id_barang'] ?>">
                                    <img src="admin/gambar/<?php echo $bk['gambar'] ?>" alt="" width="120px">
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
                                        <a href="login.php"><i class="fa fa-shopping-cart"></i> Tambah Troli</a>
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
                            <div class="product-title p-2">
                                <!-- Product Title -->
                                <h6 class="font-weight-medium"><a href="#"><?php echo $bk['judul'] ?></a></h6>
                                <h6 class="font-weight-medium"><a href="#">Brand :<?php echo $bk['nama_jenis'] ?></a></h6>
                                <!-- Product Price -->
                                <div class="price">
                                    <!-- <del>$98</del> -->
                                    <p class="card-text fas fa-tag"></p>
                                    <span>Rp. <?php echo $harga; ?></span>
                                    <span class="text pull-right" style="float: right;">Stok : <?php echo $bk['stok_barang'] ?> pcs</span>
                                </div>
                                <hr>
                                <?php if (!isset($_SESSION['id_user'])) : ?>
                                    <button name="add-to-cart" type="submit"><a href="login.php"><i class="fa fa-shopping-cart"></i> Tambah Troli</a></button>
                                    &nbsp
                                    &nbsp
                                    <button name="add-to-cart" type="submit"><a href="detail.php?key=<?php echo $bk['id_barang'] ?>"><i class="fa fa-list"></i> Detail</a></button>
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
                        <li class="admin-item"><a class="admin-link" href="#">«</a></li>
                        <li class="admin-item active"><a class="admin-link" href="#">1</a></li>
                        <li class="admin-item"><a class="admin-link" href="#">2</a></li>
                        <li class="admin-item"><a class="admin-link" href="#">3</a></li>
                        <li class="admin-item"><a class="admin-link" href="#">»</a></li>
                    </ul>
                </nav>
            </div> -->
        </div><!-- end container -->
    </div>
    <!-- end Products section -->

    <div class="section" style="background-color: #f5f5f5;">
        <div class="container">

            <h4 class="text-center">Rekomendasi Produk</h4>
            <hr style="height:3px;background-color:#333;width:100px;margin:0 auto 30px auto">
            <!-- Products -->
            <div class="row col-spacing-30">

                <div class="items">
                    <?php foreach ($rekomendasi as $bk) : ?>
                        <?php $harga = number_format($bk['harga'], 0, ",", "."); ?>
                        <div>

                            <div class="product-box">
                                <div class="card h-10"></div>
                                <div class="product-img">
                                    <!-- Product IMG -->
                                    <a class="product-img-link" href="detail.php?key=<?php echo $bk['id_barang'] ?>">
                                        <img src="admin/gambar/<?php echo $bk['gambar'] ?>" alt="" width="120px">
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
                                            <a href="admin/login.php"><p style="text-align: center;"><i class="fa fa-shopping-cart"></i> Tambah Troli</p></a>
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
                                    <h6 class="font-weight-medium"><a href="#">Brand :<?php echo $bk['nama_jenis'] ?></a></h6>
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
                                    &nbsp
                                    &nbsp
                                    &nbsp
                                    &nbsp
                                    &nbsp
                                    &nbsp
                                    &nbsp
                                    <button name="add-to-cart"type="submit"><a href="detail.php?key=<?php echo $bk['id_barang'] ?>"><i class="fa fa-list"></i> Detail</a></button>
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



                <!-- Product box 1 -->


                <!-- Product box 2 -->
                <!-- Product box 3 -->
                <!-- Product box 4 -->
                <!-- Product box 5 -->
                <!-- Product box 6 -->
            </div><!-- end row -->

        </div>
    </div>
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
    <script src="assets/js/Main.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.items').slick({
                infinite: true,
                slidesToShow: 3,
                slidesToScroll: 3,
                autoplay: true,
                autoplaySpeed: 2000,
            });
        });
    </script>
</body>

</html>