<?php  
require 'admin/config/koneksi.php';
if (isset($_POST['submit'])) {
	if (transaksi($_POST) > 0) {
		if (statuscheckout($_POST) > 0) {
			foreach ($_POST['produk_id'] as $key => $value) {
				$layanan=$_POST['layanan'];
				$tarif=$_POST['tarif'];
				$etd=$_POST['etd'];
				$produk_id=$_POST['produk_id'][$key];
				$user_id=$_POST['user_id'][$key];
				$query = mysqli_query($koneksi, "INSERT INTO layanan VALUES ('','$layanan','$tarif','$etd','$produk_id','$user_id')");
				if ($query) {
					echo "<script>
					document.location.href='detail_checkout.php'
					</script>";
				}
			}
		}
	}
}
?>