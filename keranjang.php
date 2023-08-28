<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("location:login.php");
}
require 'admin/config/koneksi.php';

$cart = mysqli_query($koneksi, "SELECT * FROM cart INNER JOIN barang ON cart.id_produk=barang.id_barang JOIN user ON cart.id_pembeli=user.id_user WHERE cart.status_c='Keranjang' AND cart.id_pembeli='$_SESSION[id_user]'");
$data = mysqli_fetch_assoc($cart);

if (isset($_POST['edit'])) {
    $sql = mysqli_query($koneksi, "UPDATE cart SET qty='$_POST[qty]' WHERE id_cart='$_POST[id_cart]'");
    if ($sql) {
        echo "<script>
        document.location.href='keranjang.php'
        </script>";
    }
}
if (isset($_GET['hapus'])) {
    $sql = mysqli_query($koneksi, "DELETE FROM cart WHERE id_cart='$_GET[hapus]'");
    if ($sql) {
        echo "<script>
        document.location.href='keranjang.php'
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
</head>

<body data-preloader="1">

    <?php include 'assets/header.php'; ?>
    <!-- Products section -->
    <div class="section-lg">
        <div class="container">
            <div class="row col-spacing-40">
                <div class="col-12 col-xl-8">
                    <h5>Keranjang Belanja</h5>
                    <table class="table cart-table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Produk</th>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Berat</th>
                                <th scope="col">Jumlah Barang</th>
                                <th scope="col">Total</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $nul = 0; ?>
                            <?php $brt = 0; ?>
                            <?php $no=1; ?>
                            <?php foreach ($cart as $crt) : ?>
                                <?php $harga = number_format($crt['harga_c'], 0, ",", ".");
                                $total = number_format($crt['harga_c'] * $crt['qty'], 0, ",", ".");
                                $subtotal = number_format($nul += $crt['harga_c'] * $crt['qty'], 0, ",", ".");
                                $ber=$crt['berat_barang']*$crt['qty'];
                                $totoalberat=$brt+=$ber;
                                ?>
                                <tr>
                                    <th><?php echo $no ?>.</th>
                                    <td class="product-thumbnail">
                                        <a href="#"><img src="admin/gambar/<?php echo $crt['gambar'] ?>" width="270" alt=""></a>
                                    </td>
                                    <td><?php echo $crt['judul'] ?></td>
                                    <td>Rp. <?php echo $harga ?></td>
                                    <td><?php echo $crt['berat_barang']; ?>Kg</td>
                                    <td>
                                        <form class="product-quantity" method="post">
                                            <input type="hidden" value="<?php echo $crt['id_cart'] ?>" name="id_cart">
                                            <!--     <input value="<?php echo $crt['id_produk'] ?>" hidden="" name="id_produk"> -->
                                            <div class="qnt">
                                                <input type="number" id="quantity" name="qty" min="1" value="<?php echo $crt['qty'] ?>">
                                            </div><br>
                                            <button class="btn btn-sm btn-dark" name="edit" style="margin-left: 15%;border-radius: 25%;margin-top: 2%;">Update</button>
                                        </form>
                                    </td>
                                    <td>Rp. <?php echo $total ?></td>
                                    <th scope="row"><a href="?hapus=<?php echo $crt['id_cart'] ?>" class="btn btn-sm btn-danger rounded-pill" onclick="return confirm('Hapus Data?')">
                                        <i class="fa fa-trash"></i>
                                    </a></th>
                                </tr>
                                <?php $no++ ?>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="bg-grey padding-20 padding-md-30 padding-lg-40">
                        <h5 class="font-weight-medium">Total Produk</h5>
                        <table class="table cart-totals">
                            <tbody>
                                <tr>
                                    <th scope="row">Item Produk</th>
                                    <td><?php echo mysqli_num_rows($cart) ?> x</td>
                                </tr>

                                <tr>
                                    <th scope="row">Total Berat </th>
                                    <td>
                                        <?php if (isset($data)) : ?>
                                            Rp. <?php echo $totoalberat ?> Kg
                                        <?php endif ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">Total</th>
                                    <td>
                                        <?php if (isset($data)) : ?>
                                            Rp. <?php echo $subtotal ?>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php if (isset($data)) : ?>
                            <a class="button button-md button-dark button-fullwidth margin-top-20" href="proses_checkout.php">Checkout</a>
                        <?php endif ?>
                    </div>
                </div>
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

    <!-- * JAVASCRIPTS * -->
    <script src="assets/plugins/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=IntersectionObserver"></script>
    <script src="assets/plugins/plugins.js"></script>
    <script src="assets/js/functions.js"></script>
</body>

</html>