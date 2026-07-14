<?php
class UsuarioController
{
    private $usuarioModel;
    private $solicitudModel;

    public function __construct($conn)
    {
        $this->usuarioModel = new Usuario($conn);
        $this->solicitudModel = new Solicitud($conn);
    }

    public function perfil(): void
    {
        require_login();

        $title = 'Mi perfil';
        $flash = flash_get();
        $usuario = $this->usuarioModel->getById(current_user()['id']);
        $misSolicitudes = $this->solicitudModel->getMine(current_user()['id']);
        include __DIR__ . '/../views/perfil.php';
    }

    public function updateProfile(): void
    {
        require_login();

        $id = current_user()['id'];

        $nombres   = trim($_POST['nombres'] ?? '');
        $apellidos = trim($_POST['apellidos'] ?? '');
        $dni       = trim($_POST['dni'] ?? '');
        $telefono  = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $email     = trim($_POST['email'] ?? '');

        if ($nombres === '' || $apellidos === '' || $dni === '' || $email === '') {
            flash_set('error', 'Completa los campos obligatorios.');
            redirect_to('perfil');
        }

        if ($this->usuarioModel->existsEmail($email, $id) || $this->usuarioModel->existsDni($dni, $id)) {
            flash_set('error', 'Email o DNI ya está registrado en otro usuario.');
            redirect_to('perfil');
        }

        $ok = $this->usuarioModel->updateProfile($id, [
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'dni' => $dni,
            'telefono' => $telefono,
            'direccion' => $direccion,
            'email' => $email
        ]);

        if ($ok) {
            $_SESSION['user']['nombres'] = $nombres;
            $_SESSION['user']['apellidos'] = $apellidos;
            $_SESSION['user']['email'] = $email;
            flash_set('success', 'Perfil actualizado correctamente.');
        } else {
            flash_set('error', 'No se pudo actualizar el perfil.');
        }

        redirect_to('perfil');
    }

    public function adminIndex(): void
    {
        require_admin();
        $title = 'Gestión de usuarios';
        $flash = flash_get();
        $usuarios = $this->usuarioModel->getAll();
        $roles = $this->usuarioModel->roles();
        include __DIR__ . '/../views/admin_usuarios.php';
    }

    public function adminSave(): void
    {
        require_admin();

        $id = (int)($_POST['id'] ?? 0);
        $rol_id = (int)($_POST['rol_id'] ?? 2);
        $estado = trim($_POST['estado'] ?? 'Activo');

        if ($id > 0 && $this->usuarioModel->updateRole($id, $rol_id, $estado)) {
            flash_set('success', 'Usuario actualizado correctamente.');
        } else {
            flash_set('error', 'No se pudo actualizar el usuario.');
        }

        redirect_to('admin_usuarios');
    }

    public function delete(): void
    {
        require_admin();

        $id = (int)($_GET['id'] ?? 0);

        if ($id === current_user()['id']) {
            flash_set('error', 'No puedes desactivar tu propia cuenta.');
            redirect_to('admin_usuarios');
        }

        if ($id > 0 && $this->usuarioModel->deactivate($id)) {
            flash_set('success', 'Usuario desactivado correctamente.');
        } else {
            flash_set('error', 'No se pudo desactivar el usuario.');
        }

        redirect_to('admin_usuarios');
    }
}