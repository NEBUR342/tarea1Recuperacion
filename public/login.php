<?php
session_start();
require_once './../vendor/autoload.php';
use Src\Usuario;
if (isset($_POST['login'])) {
    $errores = false;
    $email = trim($_POST['email']);
    $pass = trim($_POST['pass']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores = true;
        $_SESSION['err_email'] = "Debes introducir un email valido";
    }
    if (strlen($pass) == 0) {
        $errores = true;
        $_SESSION['err_pass'] = "El campo password no debe estar vacio";
    }
    if ($errores) {
        header("Location:{$_SERVER['PHP_SELF']}");
        die();
    }
    // compruebo que existe el usuario
    if (Usuario::comprobarCuenta($email, $pass)) {
        $_SESSION['usuario']=$email;
        header("Location:index.php");
        die();
    } else {
        $_SESSION['err_val'] = "Usuario o contraseña incorrectos";
        header("Location:{$_SERVER['PHP_SELF']}");
        die();
    }
} else {
?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- CDN Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- CDN FONTAWESOME -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <title>Login</title>
    </head>
    <body style="background-color:bisque">
        <section class="vh-100 gradient-custom">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-dark text-white" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">
                                <div class="mb-md-5 mt-md-4 pb-5">
                                    <form name="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                        <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                                        <p class="text-white-50 mb-5">Please enter your login and password!</p>
                                        <div class="form-outline form-white mb-4">
                                            <?php
                                            if (isset($_SESSION['err_email'])) {
                                                echo "<small class='mb-2 text-danger text-sm italic'>{$_SESSION['err_email']}</small>";
                                                unset($_SESSION['err_email']);
                                            }
                                            ?>
                                            <input type="text" id="typeEmailX" class="form-control form-control-lg" name="email" />
                                            <label class="form-label" for="typeEmailX">Email</label>
                                        </div>
                                        <div class="form-outline form-white mb-4">
                                            <?php
                                            if (isset($_SESSION['err_pass'])) {
                                                echo "<small class='mb-2 text-danger text-sm italic'>{$_SESSION['err_pass']}</small>";
                                                unset($_SESSION['err_pass']);
                                            }
                                            ?>
                                            <input type="password" id="typePasswordX" class="form-control form-control-lg" name="pass" />
                                            <label class="form-label" for="typePasswordX">Password</label>
                                        </div>
                                        <button class="btn btn-outline-light btn-lg px-5" type="submit" name="login">Login</button>
                                    </form>
                                    <?php
                                    if (isset($_SESSION['err_val'])) {
                                        echo "<small class='mb-2 text-danger text-sm italic'>{$_SESSION['err_val']}</small>";
                                        unset($_SESSION['err_val']);
                                    }
                                    ?>
                                </div>
                                <div>
                                    <p class="mb-0">¿No tienes cuenta? <a href="register.php" class="text-white-50 fw-bold">Regístrate</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
    </html>
<?php } ?>
