<?php  
session_start();
if (!isset($_SESSION['id_user'])) {
    header("location:page/login.php");
}
require 'page/config/koneksi.php';

$cart=mysqli_query($koneksi,"SELECT * FROM user WHERE id_user='$_SESSION[id_user]'");
$data=mysqli_fetch_assoc($cart);
if (isset($_POST['edit'])) {
    $password=md5($_POST['password']);
    if ($_POST['password']!=="") {
        mysqli_query($koneksi,"UPDATE user SET nama='$_POST[nama]', email='$_POST[email]',password='$password',jenis_kelamin='$_POST[jenis_kelamin]' WHERE id_user='$_SESSION[id_user]'");
        echo "<script>
        document.location.href='profil.php';
        </script>";
    }elseif ($_POST['password']=="") {
        mysqli_query($koneksi,"UPDATE user SET nama='$_POST[nama]', email='$_POST[email]',jenis_kelamin='$_POST[jenis_kelamin]' WHERE id_user='$_SESSION[id_user]'");
        echo "<script>
        document.location.href='profil.php';
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
<div class="section">
    <div class="container">
        <div class="row col-spacing-40">
            <div class="col-12 col-xl-8">
                <h5 class="font-weight-medium margin-bottom-30">Edit Profil</h5>
                <form method="post">
                    <div class="form-row">
                        <div class="col-6">
                            <label class="required">Name</label>
                            <input type="text" name="nama" value="<?php echo $data['nama'] ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="required">Email</label>
                            <input type="email" value="<?php echo $data['email'] ?>" name="email" required>
                        </div>
                        <div class="col-6">
                            <label class="required">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control">
                                <option value="Laki-Laki">Laki-Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label> Password </label>
                            <input type="text" name="password">
                        </div>
                        <div class="col-6">
                            <input type="submit" name="edit" value="Edit Data">
                        </div>
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

		<!-- ***** JAVASCRIPTS ***** -->
		<script src="assets/plugins/jquery.min.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
		<script src="https://polyfill.io/v3/polyfill.min.js?features=IntersectionObserver"></script>
		<script src="assets/plugins/plugins.js"></script>
		<script src="assets/js/functions.js"></script>
	</body>
</html>
