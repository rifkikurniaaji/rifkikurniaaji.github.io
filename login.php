<?php
session_start();
require 'admin/config/koneksi.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = mysqli_query($koneksi, "SELECT * FROM user WHERE nama='$email' AND password='$password'");
    $num = mysqli_num_rows($sql);

    if ($num) {
        $data = mysqli_fetch_assoc($sql);
        if ($data['role'] == "Pembeli") {
            $_SESSION['id_user'] = $data['id_user'];
            $_SESSION['role'] = $data['role'];
            $_SESSION['email'] = $email;
            header("location:index.php");
        } else {
            echo "<script>alert('Gagal, tidak ada akses')</script>";
        }
    } else {
        $salah = true;
    }
}

if (isset($_POST['add'])) {
    if (register($_POST) > 0) {
        $berhasil = true;
    } else {
        echo "<script>
        alert('Gagal Register')
        document.location.href='login.php'
        </script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Anugrahcase</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin/assets/css/bootstrap.css">
    <link rel="stylesheet" href="admin/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="admin/assets/css/app.css">
    <link rel="stylesheet" href="admin/assets/css/pages/auth.css">
</head>

<body>
    <div id="auth">

        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <h1 class="auth-title">Log in.</h1>
                    <p class="auth-subtitle mb-3">Log in with your data and registration.</p>
                    <?php if (isset($berhasil)) : ?>
                        <p class="auth-subtitle text-success">Register Berhasil Silahkan Login</p>
                    <?php endif ?>

                    <form method="post">
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" required="" name="email" placeholder="Nama">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" class="form-control form-control-xl" required="" name="password" placeholder="Password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <div class="form-check form-check-lg d-flex align-items-end">
                            <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                Keep me logged in
                            </label>
                        </div>
                        <button name="login" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
                    </form>
                    <div class="text-center mt-5 text-lg fs-4">
                        <p class="text-gray-600">Tidak Punya Akun? <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#inlineForm">Sign
                                up</a>.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right" style="background-image: url('assets/images/photo2.png');">

                </div>
            </div>
        </div>

    </div>

    <div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Register Form </h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <label>Nama : </label>
                        <div class="form-group">
                            <input type="text" name="email" placeholder="Nama" class="form-control">
                        </div>
                        <label>Password : </label>
                        <div class="form-group">
                            <input type="password" name="password" placeholder="Password" class="form-control">
                        </div>
                        <label>Jenis Kelamin : </label>
                        <div class="form-group">
                            <select class="form-control" name="jenis_kelamin">
                                <option value="Laki-Laki">Laki-Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" class="btn btn-primary ml-1" name="add">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Accept</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
<script src="admin/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="admin/assets/js/bootstrap.bundle.min.js"></script>

<script src="admin/assets/js/main.js"></script>
<script src="admin/assets/js/extensions/sweetalert2.js"></script>
<script src="admin/assets/vendors/sweetalert2/sweetalert2.all.min.js"></script>
<?php if (isset($salah)) : ?>
    <script type="text/javascript">
        Swal.fire({
            icon: "error",
            title: "Nama dan Password Salah!"
        });
    </script>
<?php endif ?>

</html>