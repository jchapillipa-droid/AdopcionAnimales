<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/config/helpers.php';

require_once __DIR__ . '/models/Usuario.php';
require_once __DIR__ . '/models/Animal.php';
require_once __DIR__ . '/models/Solicitud.php';

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/AnimalController.php';
require_once __DIR__ . '/controllers/SolicitudController.php';
require_once __DIR__ . '/controllers/UsuarioController.php';

$authController = new AuthController($conn);
$animalController = new AnimalController($conn);
$solicitudController = new SolicitudController($conn);
$usuarioController = new UsuarioController($conn);

$action = $_GET['action'] ?? $_GET['accion'] ?? 'home';

switch ($action) {
    case 'home':
        $animalController->home();
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            $authController->loginForm();
        }
        break;

    case 'registro':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        } else {
            $authController->registerForm();
        }
        break;

    case 'logout':
        $authController->logout();
        break;

    case 'animales':
        $animalController->index();
        break;

    case 'detalle':
        $animalController->detalle();
        break;

    case 'adopcion':
        $animalController->adopcion();
        break;

    case 'solicitar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $solicitudController->save();
        } else {
            redirect_to('animales');
        }
        break;

    case 'perfil':
        $usuarioController->perfil();
        break;

    case 'actualizar_perfil':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioController->updateProfile();
        } else {
            redirect_to('perfil');
        }
        break;

    case 'admin':
        require_admin();
        $title = 'Panel administrador';
        $flash = flash_get();
        include __DIR__ . '/views/admin.php';
        break;

    case 'admin_animales':
        $animalController->adminIndex();
        break;

    case 'admin_nuevo_animal':
        $animalController->form();
        break;

    case 'admin_editar_animal':
        $animalController->form();
        break;

    case 'guardar_animal':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $animalController->save();
        } else {
            redirect_to('admin_animales');
        }
        break;

    case 'eliminar_animal':
        $animalController->delete();
        break;

    case 'publicar_animal':
        $animalController->togglePublish();
        break;

    case 'admin_solicitudes':
        $solicitudController->adminIndex();
        break;

    case 'aprobar_solicitud':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $solicitudController->approve();
        } else {
            redirect_to('admin_solicitudes');
        }
        break;

    case 'rechazar_solicitud':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $solicitudController->reject();
        } else {
            redirect_to('admin_solicitudes');
        }
        break;

    case 'admin_usuarios':
        $usuarioController->adminIndex();
        break;

    case 'guardar_usuario':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioController->adminSave();
        } else {
            redirect_to('admin_usuarios');
        }
        break;

    case 'desactivar_usuario':
        $usuarioController->delete();
        break;

    default:
        $animalController->home();
        break;
}