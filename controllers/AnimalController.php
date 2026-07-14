<?php
class AnimalController
{
    private $animalModel;

    public function __construct($conn)
    {
        $this->animalModel = new Animal($conn);
    }

    public function home(): void
    {
        $title = 'Inicio';
        $flash = flash_get();
        $featured = $this->animalModel->featuredPublic(6);
        $especies = $this->animalModel->especies();
        include __DIR__ . '/../views/home.php';
    }

    public function index(): void
    {
        $title = 'Animales disponibles';
        $flash = flash_get();
        $especies = $this->animalModel->especies();

        $animales = $this->animalModel->getAllPublic([
            'buscar' => $_GET['buscar'] ?? '',
            'especie_id' => $_GET['especie_id'] ?? '',
            'sexo' => $_GET['sexo'] ?? '',
            'tamano' => $_GET['tamano'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'edad_min' => $_GET['edad_min'] ?? '',
            'edad_max' => $_GET['edad_max'] ?? ''
        ]);

        include __DIR__ . '/../views/animales.php';
    }

    public function detalle(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $animal = $this->animalModel->getById($id);

        if (!$animal) {
            flash_set('error', 'Animal no encontrado.');
            redirect_to('animales');
        }

        $title = 'Detalle del animal';
        $flash = flash_get();
        include __DIR__ . '/../views/detalle.php';
    }

    public function adopcion(): void
    {
        require_login();

        $id = (int)($_GET['id'] ?? 0);
        $animal = $this->animalModel->getById($id);

        if (!$animal) {
            flash_set('error', 'Animal no encontrado.');
            redirect_to('animales');
        }

        $title = 'Solicitud de adopción';
        $flash = flash_get();
        include __DIR__ . '/../views/adopcion.php';
    }

    public function adminIndex(): void
    {
        require_admin();
        $title = 'Administrar animales';
        $flash = flash_get();
        $animales = $this->animalModel->getAllAdmin();
        include __DIR__ . '/../views/admin_animales.php';
    }

    public function form(): void
    {
        require_admin();

        $id = (int)($_GET['id'] ?? 0);
        $animal = null;

        if ($id > 0) {
            $animal = $this->animalModel->getById($id);
            if (!$animal) {
                flash_set('error', 'Animal no encontrado.');
                redirect_to('admin_animales');
            }
        }

        $title = $animal ? 'Editar animal' : 'Nuevo animal';
        $flash = flash_get();
        $especies = $this->animalModel->especies();
        $razas = $this->animalModel->razas();
        include __DIR__ . '/../views/animal_form.php';
    }

    public function save(): void
{
    require_admin();

    $id = (int)($_POST['id'] ?? 0);

    $nombre = trim($_POST['nombre'] ?? '');
    $especie = (int)($_POST['especie_id'] ?? 0);
    $raza = !empty($_POST['raza_id']) ? (int)$_POST['raza_id'] : null;
    $sexo = trim($_POST['sexo'] ?? '');
    $edad = (int)($_POST['edad_meses'] ?? 0);
    $tamano = trim($_POST['tamano'] ?? '');
    $color = trim($_POST['color'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $historia = trim($_POST['historia_rescate'] ?? '');
    $fechaRescate = !empty($_POST['fecha_rescate']) ? $_POST['fecha_rescate'] : null;
    $salud = trim($_POST['estado_salud'] ?? '');
    $ester = isset($_POST['esterilizado']) ? 1 : 0;
    $vacun = isset($_POST['vacunado']) ? 1 : 0;
    $public = isset($_POST['publicado']) ? 1 : 0;
    $estado = trim($_POST['estado'] ?? 'Disponible');

    if ($nombre === '' || $especie <= 0 || $sexo === '' || $tamano === '' || $descripcion === '') {
        flash_set('error', 'Completa los campos obligatorios.');
        redirect_to($id > 0 ? 'admin_editar_animal' : 'admin_nuevo_animal', $id > 0 ? ['id' => $id] : []);
    }

    $imagen = null;

    $imagenUrl = trim($_POST['imagen_url'] ?? '');
    if ($imagenUrl !== '') {
        $imagen = $imagenUrl;
    }

    if (!empty($_FILES['imagen_file']['name'])) {
        $ext = strtolower(pathinfo($_FILES['imagen_file']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];

        if (in_array($ext, $permitidas, true)) {
            if (!is_dir(__DIR__ . '/../uploads')) {
                @mkdir(__DIR__ . '/../uploads', 0777, true);
            }

            $nuevoNombre = uniqid('animal_', true) . '.' . $ext;
            $ruta = __DIR__ . '/../uploads/' . $nuevoNombre;

            if (move_uploaded_file($_FILES['imagen_file']['tmp_name'], $ruta)) {
                $imagen = $nuevoNombre;
            }
        }
    }

    if ($id > 0 && $imagen === null) {
        $actual = $this->animalModel->getById($id);
        if ($actual && !empty($actual['imagen'])) {
            $imagen = $actual['imagen'];
        }
    }

    $data = [
        'nombre' => $nombre,
        'especie_id' => $especie,
        'raza_id' => $raza,
        'sexo' => $sexo,
        'edad_meses' => $edad,
        'tamano' => $tamano,
        'color' => $color,
        'descripcion' => $descripcion,
        'historia_rescate' => $historia,
        'fecha_rescate' => $fechaRescate,
        'estado_salud' => $salud,
        'esterilizado' => $ester,
        'vacunado' => $vacun,
        'imagen' => $imagen,
        'publicado' => $public,
        'disponible' => ($estado === 'Adoptado') ? 0 : 1,
        'activo' => 1,
        'estado' => $estado,
        'usuario_registro_id' => current_user()['id']
    ];

    $ok = ($id > 0)
        ? $this->animalModel->update($id, $data)
        : $this->animalModel->create($data);

    if ($ok) {
        flash_set($id > 0 ? 'success' : 'success', $id > 0 ? 'Animal actualizado correctamente.' : 'Animal registrado correctamente.');
        redirect_to('admin_animales');
    }

    flash_set('error', 'No se pudo guardar el animal.');
    redirect_to('admin_animales');
}

    public function delete(): void
    {
        require_admin();

        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0 && $this->animalModel->deactivate($id)) {
            flash_set('success', 'Animal eliminado correctamente.');
        } else {
            flash_set('error', 'No se pudo eliminar el animal.');
        }

        redirect_to('admin_animales');
    }

    public function togglePublish(): void
    {
        require_admin();

        $id = (int)($_GET['id'] ?? 0);
        $publicado = (int)($_GET['publicado'] ?? 0);

        if ($id > 0 && $this->animalModel->togglePublic($id, $publicado)) {
            flash_set('success', 'Estado de publicación actualizado.');
        } else {
            flash_set('error', 'No se pudo cambiar la publicación.');
        }

        redirect_to('admin_animales');
    }
}