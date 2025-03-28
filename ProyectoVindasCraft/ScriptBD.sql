CREATE DATABASE IF NOT EXISTS muebleria;
 
USE muebleria;

-- Tabla Roles
CREATE TABLE IF NOT EXISTS roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(50) NOT NULL
);

-- Tabla Usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    identificacion VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    id_rol INT NOT NULL,
    FOREIGN KEY (id_rol) REFERENCES roles(id_rol)
);


------------------------------------------------------------------------------------------
-- Insertar Roles
INSERT INTO roles (nombre_rol) VALUES ('cliente'), ('administrador');

-- Insertar Usuario Administrador
INSERT INTO usuarios (identificacion, nombre, email, password, id_rol) 
VALUES 
    ('admin', 'Soporte VindasCraft', 'soporte.vindascraft@gmail.com', 
    '$2y$10$emIqUklb2i8wQXo.MWhfQeH7q7R4gBy4F7IExeMgg/QBIdpl76gJW', 2)
ON DUPLICATE KEY UPDATE email = VALUES(email), password = VALUES(password);
------------------------------------------------------------------------------------------


-- Tabla Categorías
CREATE TABLE IF NOT EXISTS categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL
);

-- Tabla Estilos
CREATE TABLE IF NOT EXISTS estilos (
    id_estilo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL
);

-- Tabla Proyectos
CREATE TABLE IF NOT EXISTS proyectos (
    id_proyecto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    detalles TEXT,
    categoria_id INT NOT NULL,
    imagen VARCHAR(255) NOT NULL,
    estilo_id INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id_categoria),
    FOREIGN KEY (estilo_id) REFERENCES estilos(id_estilo),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);




-- Tabla Presupuestos
CREATE TABLE IF NOT EXISTS presupuestos (
    id_presupuesto INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    detalles TEXT NOT NULL,
    nombre_proyecto VARCHAR(255) NOT NULL,
    monto_estimado DECIMAL(10,2) DEFAULT NULL,
    estado ENUM('En proceso', 'Aprobado', 'Rechazado', 'Cancelado') DEFAULT 'En proceso',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);


CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_nombre VARCHAR(255) NOT NULL,
    cliente_email VARCHAR(255) DEFAULT NULL,
    cliente_telefono VARCHAR(50) DEFAULT NULL,
    detalles_producto TEXT,
    fecha_estimada_entrega DATE NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'procesado', 'completado', 'cancelado') DEFAULT 'pendiente'
);
ALTER TABLE pedidos
ADD COLUMN prioridad ENUM('baja', 'media', 'alta') DEFAULT 'media';

-- Tabla Inventario
CREATE TABLE IF NOT EXISTS inventario (
    id_material INT AUTO_INCREMENT PRIMARY KEY,
    nombre_material VARCHAR(100) NOT NULL,
    proveedor VARCHAR(100) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS auditoria (
    id_auditoria INT AUTO_INCREMENT PRIMARY KEY,
    id_material INT,
    accion ENUM('agregar', 'editar', 'eliminar') NOT NULL,
    nombre_material_anterior VARCHAR(100),
    precio_anterior DECIMAL(10, 2),
    stock_anterior INT,
    proveedor_anterior VARCHAR(100),
    nombre_material_nuevo VARCHAR(100),
    precio_nuevo DECIMAL(10, 2),
    stock_nuevo INT,
    proveedor_nuevo VARCHAR(100),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla alertas
CREATE TABLE IF NOT EXISTS alertas (
    id_alerta INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    mensaje TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    leida BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id)
);

--Relacion entre usuarios y proyectos, aqui se almacenaran los proyectos guardados por cada usuario
CREATE TABLE IF NOT EXISTS usuarios_proyectos (
    id_usuario INT NOT NULL,
    id_proyecto INT NOT NULL,
    fecha_guardado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_usuario, id_proyecto),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_proyecto) REFERENCES proyectos(id_proyecto) ON DELETE CASCADE
) ENGINE=InnoDB;