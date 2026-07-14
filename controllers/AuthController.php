<?php
class AuthController
{
    private $usuarioModel;

    public function __construct($conn)
    {
        $this->usuarioModel = new Usuario($conn);
    }

    public function loginForm(): void
    {
        $title = 'Iniciar sesión';
        $flash = flash_get();
        include __DIR__ . '/../views/login.php';
    }

    public function login(): void
    {
        $usuario = trim($_POST['usuario'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($usuario === '' || $password === '') {
            flash_set('error', 'Completa usuario y contraseña.');
            redirect_to('login');
        }

        $user = $this->usuarioModel->getByUsuarioOrEmail($usuario);

        if (!$user || !password_verify($password, $user['password'])) {
            flash_set('error', 'Credenciales incorrectas.');
            redirect_to('login');
        }

        if (($user['estado'] ?? '') !== 'Activo' || (int)($user['activo'] ?? 0) !== 1) {
            flash_set('error', 'Tu cuenta está inactiva.');
            redirect_to('login');
        }

        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id' => $user['id'],
            'nombres' => $user['nombres'],
            'apellidos' => $user['apellidos'],
            'usuario' => $user['usuario'],
            'email' => $user['email'],
            'rol_nombre' => $user['rol_nombre']
        ];
        $_SESSION['last_activity'] = time();

        flash_set('success', 'Bienvenido, ' . $user['nombres'] . '.');
        redirect_to('home');
    }

    public function registerForm(): void
    {
        $title = 'Registro';
        $flash = flash_get();
        include __DIR__ . '/../views/registro.php';
    }

    public function register(): void
    {
        $nombres   = trim($_POST['nombres'] ?? '');
        $apellidos = trim($_POST['apellidos'] ?? '');
        $dni       = trim($_POST['dni'] ?? '');
        $telefono  = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $usuario   = trim($_POST['usuario'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = $_POST['password'] ?? '';
        $confirmar = $_POST['confirmar'] ?? '';

        if ($nombres === '' || $apellidos === '' || $dni === '' || $usuario === '' || $email === '' || $password === '') {
            flash_set('error', 'Completa los campos obligatorios.');
            redirect_to('registro');
        }

        if ($password !== $confirmar) {
            flash_set('error', 'Las contraseñas no coinciden.');
            redirect_to('registro');
        }

        if ($this->usuarioModel->existsUsuario($usuario) || $this->usuarioModel->existsEmail($email) || $this->usuarioModel->existsDni($dni)) {
            flash_set('error', 'Ya existe un usuario con esos datos.');
            redirect_to('registro');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $ok = $this->usuarioModel->create([
            'rol_id' => 2,
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'dni' => $dni,
            'telefono' => $telefono,
            'direccion' => $direccion,
            'usuario' => $usuario,
            'email' => $email,
            'password' => $hash
        ]);

        if ($ok) {
            flash_set('success', 'Cuenta creada correctamente. Ya puedes iniciar sesión.');
            redirect_to('login');
        }

        flash_set('error', 'No se pudo registrar el usuario.');
        redirect_to('registro');
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        session_start();
        flash_set('success', 'Sesión cerrada correctamente.');
        redirect_to('home');
    }
}