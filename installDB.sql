/**
 * @title Instalación de Base de Datos y Usuario
 * @description Crear la base de datos, las tablas y el usuario de conexión en MariaDB.
 * @author Karen Arumil Lima Uranga, Kilian Ian Pérez García, Diego Sierra Legorreta
 * @date 2026-10-30
 */

-- 1. Crear la base de datos
CREATE DATABASE IF NOT EXISTS portal_multimedia;
USE portal_multimedia;

-- 2. Crear el usuario de conexión para PHP y asignarle permisos
CREATE USER IF NOT EXISTS 'usuario_multimedia'@'localhost' IDENTIFIED BY 'ibero2026';
GRANT ALL PRIVILEGES ON portal_multimedia.* TO 'usuario_multimedia'@'localhost';
FLUSH PRIVILEGES;

-- 3. Crear las tablas
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, 
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE musica (
    id_musica INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    artista VARCHAR(100),
    ruta_archivo VARCHAR(255) NOT NULL,
    fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE fotos (
    id_foto INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    ruta_archivo VARCHAR(255) NOT NULL,
    fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE videos (
    id_video INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    ruta_archivo VARCHAR(255) NOT NULL,
    fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);