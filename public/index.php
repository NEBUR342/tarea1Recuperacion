<?php
session_start();
/*
  He puesto como index la pagina que usamos como portal, para 2 cosas:
    1.- Por si ya tenemos iniciada la sesion.
    2.- Para que la pagina principal no sea el login.
  Personalmente es algo que me gusta más. Además así me veía forzado a hacer la siguiente validación
*/
// En caso de que no estés logueado te envío al login.
if(!isset($_SESSION['usuario'])){
    header("Location:login.php");
    die;
}
use Src\Usuario;
require_once "./../vendor/autoload.php";
$usuarios=Usuario::read();
if(isset($_POST['id'])){
    // al pulsar el boton de modificar, hago el update en el que se ha marcado.
    foreach($usuarios as $usuario){
        if($usuario->id==$_POST['id'] && $usuario->email!=$_SESSION['usuario']) Usuario::update($usuario->email,$usuario->perfil);
    }
    header("Location:{$_SERVER['PHP_SELF']}");
    die();
}else{
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
    <!-- CDN SeetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Portal</title>
</head>
<body style="background-color:bisque">
    <div class="d-flex flex-row-reverse my-2 mx-4">
        <div>
            <a href="cerrar.php" class="btn btn-danger ">
                <i class="fa-solid fa-right-from-bracket"></i> SALIR
            </a>
        </div>
        <div>
            <input type="text" readonly value="<?php echo $_SESSION['usuario'] ?>" class="bg-info form-control" />
        </div>
    </div>
    <div class="container">
        <h5 class=" text-center my-2">LISTADO DE USUARIOS REGISTRADOS</h5>
        <table class="table table-dark">
            <thead>
                <tr class="text-center">
                    <th scope="col">ID</th>
                    <th scope="col">EMAIL</th>
                    <th scope="col">CIUDAD</th>
                    <th scope="col">PERFIL</th>
                    <?php if(Usuario::permisosUsuario($_SESSION['usuario'])){ ?>
                    <th scope="col">ACCIONES</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($usuarios as $usuario){
                        $cad=($usuario->email==$_SESSION['usuario']) ? " *" : "";
                        $cad1=($_SESSION['usuario']==$usuario->email) ? "disabled" : "";
                        echo <<<TXT
                            <tr class="text-center">
                            <th scope="row">{$usuario->id}</th>
                            <td>{$usuario->email}$cad </td>
                            <td>{$usuario->ciudad}</td>
                        TXT;
                        if($usuario->perfil=="Usuario") {
                            echo <<<TXT
                                <td class='text-info'>{$usuario->perfil}</td>
                            TXT;
                        } else if($usuario->perfil=="Administrador") {
                            echo <<<TXT
                                <td class='text-warning'>{$usuario->perfil}</td>
                            TXT;
                        }
                        if(Usuario::permisosUsuario($_SESSION['usuario'])){
                            echo <<<TXT
                            <td>
                                <form method='POST' action='index.php'>
                                    <input type="hidden" value="{$usuario->id}" name='id'/>
                                    <button type='submit' name='u' class='btn btn-info' $cad1>
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                </form>
                            </td>
                            TXT;
                        }
                        echo <<<TXT
                            </tr>
                        TXT;
                    }
                ?>
            </tbody>
        </table>
    </div>
    <?php
    if(isset($_SESSION['error'])){
        echo "<p class='text-danger text-lg p-4 rounded bg-info'>{$_SESSION['error']}</p>";
        unset($_SESSION['error']);
    }
    ?>
</body>
<?php } ?>