-- Base de datos Vantia (Empleo Juvenil Verificado)
CREATE DATABASE IF NOT EXISTS vantia_empleabilidad_v2
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE vantia_empleabilidad_v2;

-- 1. Tabla de Usuarios Menores de 18 años (16 y 17 años)
CREATE TABLE IF NOT EXISTS usuarios_men (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    telefono VARCHAR(30) DEFAULT NULL,
    fecha_nacimiento DATE NOT NULL,
    cv_path VARCHAR(255) DEFAULT NULL,
    permiso_tutor_path VARCHAR(255) DEFAULT NULL,
    baneado TINYINT(1) NOT NULL DEFAULT 0,
    baneado_hasta DATETIME DEFAULT NULL,
    motivo_baneo VARCHAR(255) DEFAULT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabla de Tutores (Padre, madre o tutor legal a cargo del usuario menor de 18 años)
CREATE TABLE IF NOT EXISTS tutores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_men_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(30) NOT NULL,
    relacion VARCHAR(50) NOT NULL DEFAULT 'Tutor legal',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_tutor_usuario_men (usuario_men_id),
    CONSTRAINT fk_tutores_usuario_men FOREIGN KEY (usuario_men_id) REFERENCES usuarios_men(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabla de Usuarios Mayores de 18 años y Administradores
CREATE TABLE IF NOT EXISTS usuarios_may (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    tipo ENUM('empleado', 'admin') NOT NULL DEFAULT 'empleado',
    telefono VARCHAR(30) DEFAULT NULL,
    fecha_nacimiento DATE DEFAULT NULL,
    cv_path VARCHAR(255) DEFAULT NULL,
    baneado TINYINT(1) NOT NULL DEFAULT 0,
    baneado_hasta DATETIME DEFAULT NULL,
    motivo_baneo VARCHAR(255) DEFAULT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Vista unificada de compatibilidad 'usuarios' (une mayores y menores con su tutor)
CREATE OR REPLACE VIEW usuarios AS
SELECT 
    um.id, um.nombre, um.correo, um.contrasena, um.tipo, um.telefono, um.fecha_nacimiento, 
    0 AS es_menor, 
    NULL AS tutor_nombre, NULL AS tutor_telefono, NULL AS tutor_relacion, 
    um.cv_path, NULL AS permiso_tutor_path, um.fecha_registro, 'mayor' AS tabla_origen,
    um.baneado, um.baneado_hasta, um.motivo_baneo
FROM usuarios_may um
UNION ALL
SELECT 
    un.id, un.nombre, un.correo, un.contrasena, 'empleado' AS tipo, un.telefono, un.fecha_nacimiento, 
    1 AS es_menor, 
    t.nombre AS tutor_nombre, t.telefono AS tutor_telefono, t.relacion AS tutor_relacion, 
    un.cv_path, un.permiso_tutor_path, un.fecha_registro, 'menor' AS tabla_origen,
    un.baneado, un.baneado_hasta, un.motivo_baneo
FROM usuarios_men un
LEFT JOIN tutores t ON t.usuario_men_id = un.id;

-- 4. Tabla de Empresas (Organizaciones con NIT y ROE)
CREATE TABLE IF NOT EXISTS empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    nombre_empresa VARCHAR(150) GENERATED ALWAYS AS (nombre) VIRTUAL,
    nit VARCHAR(50) DEFAULT NULL,
    roe VARCHAR(255) NOT NULL,
    representante VARCHAR(150) NOT NULL,
    telefono VARCHAR(30) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    email VARCHAR(150) GENERATED ALWAYS AS (correo) VIRTUAL,
    contrasena VARCHAR(255) NOT NULL,
    password VARCHAR(255) GENERATED ALWAYS AS (contrasena) VIRTUAL,
    descripcion TEXT DEFAULT NULL,
    documento_legal_path VARCHAR(255) DEFAULT NULL,
    verificada TINYINT(1) NOT NULL DEFAULT 1,
    baneado TINYINT(1) NOT NULL DEFAULT 0,
    baneado_hasta DATETIME DEFAULT NULL,
    motivo_baneo VARCHAR(255) DEFAULT NULL,
    creada_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Tabla de Empleos / Vacantes
CREATE TABLE IF NOT EXISTS empleos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    categoria VARCHAR(80) NOT NULL,
    tipo ENUM('medio_tiempo', 'practicas', 'fines_semana', 'remoto') NOT NULL,
    descripcion TEXT NOT NULL,
    requisitos TEXT DEFAULT NULL,
    salario VARCHAR(50) DEFAULT NULL,
    ubicacion VARCHAR(120) DEFAULT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (empresa_id),
    CONSTRAINT fk_empleos_empresa FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Tabla de Postulaciones (Aplicaciones de candidatos a empleos)
CREATE TABLE IF NOT EXISTS postulaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleo_id INT NOT NULL,
    candidato_id INT NOT NULL,
    candidato_tipo ENUM('mayor', 'menor') NOT NULL DEFAULT 'mayor',
    mensaje TEXT DEFAULT NULL,
    estado ENUM('pendiente', 'revision', 'entrevista', 'contratado', 'rechazado') NOT NULL DEFAULT 'pendiente',
    fecha_postulacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (empleo_id),
    INDEX (candidato_id),
    UNIQUE KEY uk_empleo_candidato (empleo_id, candidato_id, candidato_tipo),
    CONSTRAINT fk_postulacion_empleo FOREIGN KEY (empleo_id) REFERENCES empleos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Tabla de Calificaciones (Evaluaciones de empresas por parte de empleados contratados)
CREATE TABLE IF NOT EXISTS calificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    candidato_id INT NOT NULL,
    candidato_tipo ENUM('mayor', 'menor') NOT NULL DEFAULT 'mayor',
    puntuacion TINYINT NOT NULL CHECK (puntuacion BETWEEN 0 AND 5),
    comentario TEXT DEFAULT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_calif_empresa (empresa_id),
    UNIQUE KEY uk_calif_empresa_candidato (empresa_id, candidato_id, candidato_tipo),
    CONSTRAINT fk_calif_empresa FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Tabla de Usuarios Administradores
CREATE TABLE IF NOT EXISTS usuarios_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    telefono VARCHAR(30) DEFAULT NULL,
    cargo VARCHAR(100) NOT NULL DEFAULT 'Administrador General',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Administrador Inicial Predeterminado
-- Acceso: admin@vantia.com / admin123
INSERT INTO usuarios_admin (nombre, correo, contrasena, telefono, cargo, activo)
VALUES (
    'Administrador General',
    'admin@vantia.com',
    '$2y$10$Q58KCi7JnxX7ugFBKuizP.Y.CUdsUbgpJ6RJ7lC63Doy8Kmbv5Ayi',
    '70000000',
    'Administrador General',
    1
)
ON DUPLICATE KEY UPDATE activo = 1;
