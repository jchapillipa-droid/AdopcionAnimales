IF DB_ID('AdopcionAnimalesDB') IS NULL
    CREATE DATABASE AdopcionAnimalesDB;
GO

USE AdopcionAnimalesDB;
GO

IF OBJECT_ID('historial_adopciones', 'U') IS NOT NULL DROP TABLE historial_adopciones;
IF OBJECT_ID('solicitudes_adopcion', 'U') IS NOT NULL DROP TABLE solicitudes_adopcion;
IF OBJECT_ID('animales', 'U') IS NOT NULL DROP TABLE animales;
IF OBJECT_ID('razas', 'U') IS NOT NULL DROP TABLE razas;
IF OBJECT_ID('especies', 'U') IS NOT NULL DROP TABLE especies;
IF OBJECT_ID('usuarios', 'U') IS NOT NULL DROP TABLE usuarios;
IF OBJECT_ID('roles', 'U') IS NOT NULL DROP TABLE roles;
GO

CREATE TABLE roles (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nombre NVARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE usuarios (
    id INT IDENTITY(1,1) PRIMARY KEY,
    rol_id INT NOT NULL,
    nombres NVARCHAR(80) NOT NULL,
    apellidos NVARCHAR(80) NOT NULL,
    dni NVARCHAR(20) NOT NULL UNIQUE,
    telefono NVARCHAR(20) NULL,
    direccion NVARCHAR(150) NULL,
    usuario NVARCHAR(50) NOT NULL UNIQUE,
    email NVARCHAR(120) NOT NULL UNIQUE,
    password NVARCHAR(255) NOT NULL,
    foto NVARCHAR(255) NULL,
    estado NVARCHAR(20) NOT NULL DEFAULT 'Activo',
    activo BIT NOT NULL DEFAULT 1,
    creado_en DATETIME NOT NULL DEFAULT GETDATE(),
    CONSTRAINT FK_usuarios_roles FOREIGN KEY (rol_id) REFERENCES roles(id)
);

CREATE TABLE especies (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nombre NVARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE razas (
    id INT IDENTITY(1,1) PRIMARY KEY,
    especie_id INT NOT NULL,
    nombre NVARCHAR(80) NOT NULL,
    CONSTRAINT FK_razas_especies FOREIGN KEY (especie_id) REFERENCES especies(id),
    CONSTRAINT UQ_raza_especie UNIQUE (especie_id, nombre)
);

CREATE TABLE animales (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nombre NVARCHAR(80) NOT NULL,
    especie_id INT NOT NULL,
    raza_id INT NULL,
    sexo NVARCHAR(10) NOT NULL,
    edad_meses INT NOT NULL DEFAULT 0,
    tamano NVARCHAR(20) NOT NULL,
    color NVARCHAR(50) NULL,
    descripcion NVARCHAR(MAX) NOT NULL,
    historia_rescate NVARCHAR(MAX) NULL,
    fecha_rescate DATE NULL,
    estado_salud NVARCHAR(120) NULL,
    esterilizado BIT NOT NULL DEFAULT 0,
    vacunado BIT NOT NULL DEFAULT 0,
    imagen NVARCHAR(255) NULL,
    publicado BIT NOT NULL DEFAULT 0,
    disponible BIT NOT NULL DEFAULT 1,
    activo BIT NOT NULL DEFAULT 1,
    estado NVARCHAR(20) NOT NULL DEFAULT 'Disponible',
    usuario_registro_id INT NOT NULL,
    creado_en DATETIME NOT NULL DEFAULT GETDATE(),
    actualizado_en DATETIME NOT NULL DEFAULT GETDATE(),
    CONSTRAINT FK_animales_especies FOREIGN KEY (especie_id) REFERENCES especies(id),
    CONSTRAINT FK_animales_razas FOREIGN KEY (raza_id) REFERENCES razas(id),
    CONSTRAINT FK_animales_usuarios FOREIGN KEY (usuario_registro_id) REFERENCES usuarios(id)
);

CREATE TABLE solicitudes_adopcion (
    id INT IDENTITY(1,1) PRIMARY KEY,
    animal_id INT NOT NULL,
    usuario_id INT NOT NULL,
    motivo NVARCHAR(MAX) NOT NULL,
    experiencia NVARCHAR(MAX) NULL,
    tipo_vivienda NVARCHAR(80) NOT NULL,
    miembros_hogar INT NOT NULL,
    acepta_compromiso BIT NOT NULL DEFAULT 0,
    estado_solicitud NVARCHAR(20) NOT NULL DEFAULT 'Pendiente',
    respuesta_admin NVARCHAR(MAX) NULL,
    revisado_por INT NULL,
    fecha_solicitud DATETIME NOT NULL DEFAULT GETDATE(),
    fecha_respuesta DATETIME NULL,
    CONSTRAINT FK_solicitudes_animales FOREIGN KEY (animal_id) REFERENCES animales(id),
    CONSTRAINT FK_solicitudes_usuarios FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    CONSTRAINT FK_solicitudes_admin FOREIGN KEY (revisado_por) REFERENCES usuarios(id)
);

CREATE TABLE historial_adopciones (
    id INT IDENTITY(1,1) PRIMARY KEY,
    animal_id INT NOT NULL,
    usuario_id INT NOT NULL,
    solicitud_id INT NOT NULL UNIQUE,
    fecha_adopcion DATETIME NOT NULL DEFAULT GETDATE(),
    observacion NVARCHAR(MAX) NULL,
    CONSTRAINT FK_historial_animales FOREIGN KEY (animal_id) REFERENCES animales(id),
    CONSTRAINT FK_historial_usuarios FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    CONSTRAINT FK_historial_solicitudes FOREIGN KEY (solicitud_id) REFERENCES solicitudes_adopcion(id)
);

CREATE INDEX IX_animales_publicado_estado ON animales(publicado, estado, activo);
CREATE INDEX IX_animales_busqueda ON animales(nombre, especie_id, tamano, sexo);
CREATE INDEX IX_solicitudes_estado ON solicitudes_adopcion(estado_solicitud, animal_id, usuario_id);
GO

INSERT INTO roles (nombre) VALUES ('Administrador'), ('Usuario');
GO

INSERT INTO especies (nombre) VALUES ('Perro'), ('Gato'), ('Conejo');
GO

INSERT INTO razas (especie_id, nombre) VALUES
(1, 'Mestizo'), (1, 'Labrador'), (1, 'Beagle'),
(2, 'Mestizo'), (2, 'Siamés'), (2, 'Persa'),
(3, 'Mestizo'), (3, 'Mini Lop');
GO