<?php
class SolicitudController
{
    private $solicitudModel;
    private $animalModel;

    public function __construct($conn)
    {
        $this->solicitudModel = new Solicitud($conn);
        $this->animalModel = new Animal($conn);
    }

    public function save(): void
    {
        require_login();

        $animalId = (int)($_POST['animal_id'] ?? 0);
        $animal = $this->animalModel->getById($animalId);

        if (!$animal || (int)$animal['publicado'] !== 1 || $animal['estado'] !== 'Disponible' || (int)$animal['activo'] !== 1) {
            flash_set('error', 'Este animal ya no está disponible.');
            redirect_to('animales');
        }

        if ($this->solicitudModel->existsPending($animalId, current_user()['id'])) {
            flash_set('error', 'Ya tienes una solicitud pendiente para este animal.');
            redirect_to('adopcion', ['id' => $animalId]);
        }

        if (!isset($_POST['acepta_compromiso'])) {
            flash_set('error', 'Debes aceptar el compromiso de adopción.');
            redirect_to('adopcion', ['id' => $animalId]);
        }

        $ok = $this->solicitudModel->create([
            'animal_id' => $animalId,
            'usuario_id' => current_user()['id'],
            'motivo' => trim($_POST['motivo'] ?? ''),
            'experiencia' => trim($_POST['experiencia'] ?? ''),
            'tipo_vivienda' => trim($_POST['tipo_vivienda'] ?? ''),
            'miembros_hogar' => (int)($_POST['miembros_hogar'] ?? 0),
            'acepta_compromiso' => 1
        ]);

        if ($ok) {
            flash_set('success', 'Solicitud registrada correctamente.');
        } else {
            flash_set('error', 'No se pudo registrar la solicitud.');
        }

        redirect_to('detalle', ['id' => $animalId]);
    }

    public function adminIndex(): void
    {
        require_admin();
        $title = 'Solicitudes de adopción';
        $flash = flash_get();
        $solicitudes = $this->solicitudModel->getAll();
        include __DIR__ . '/../views/admin_solicitudes.php';
    }

    public function approve(): void
    {
        require_admin();
        $id = (int)($_GET['id'] ?? 0);
        $respuesta = trim($_POST['respuesta'] ?? 'Solicitud aprobada.');

        if ($id > 0 && $this->solicitudModel->approve($id, current_user()['id'], $respuesta)) {
            flash_set('success', 'Solicitud aprobada correctamente.');
        } else {
            flash_set('error', 'No se pudo aprobar la solicitud.');
        }

        redirect_to('admin_solicitudes');
    }

    public function reject(): void
    {
        require_admin();
        $id = (int)($_GET['id'] ?? 0);
        $respuesta = trim($_POST['respuesta'] ?? 'Solicitud rechazada.');

        if ($id > 0 && $this->solicitudModel->reject($id, current_user()['id'], $respuesta)) {
            flash_set('success', 'Solicitud rechazada correctamente.');
        } else {
            flash_set('error', 'No se pudo rechazar la solicitud.');
        }

        redirect_to('admin_solicitudes');
    }
}