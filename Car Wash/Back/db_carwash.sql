DROP DATABASE IF EXISTS dbcarwash;

CREATE DATABASE dbcarwash;

USE dbcarwash;

CREATE TABLE personas (
  id_usuario BIGINT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100) NOT NULL,
  telefono VARCHAR(20),
  correo VARCHAR(100) UNIQUE,
  direccion TEXT,
  contrasena VARCHAR(255) NOT NULL,
  rol TEXT NOT NULL 
);

CREATE TABLE vehiculos (
  id_vehiculo BIGINT AUTO_INCREMENT PRIMARY KEY,
  modelo VARCHAR(50) NOT NULL,
  marca VARCHAR(50) NOT NULL,
  matricula VARCHAR(20) NOT NULL UNIQUE,
  tipo VARCHAR(50) NOT NULL,
  color VARCHAR(30) NOT NULL,
  cliente_id BIGINT NOT NULL,
  FOREIGN KEY (cliente_id) REFERENCES personas(id_usuario) ON DELETE CASCADE
);

CREATE TABLE servicios (
  id_servicio BIGINT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  precio DECIMAL(10,2) NOT NULL
);

CREATE TABLE turnos (
  id_turno BIGINT AUTO_INCREMENT PRIMARY KEY,
  cliente_id BIGINT NOT NULL,
  vehiculo_id BIGINT NOT NULL,
  servicio_id BIGINT NOT NULL,
  fecha_hora DATETIME NOT NULL,
  estado ENUM('Nuevo', 'En Proceso', 'Enviado', 'Entregado') NOT NULL,
  FOREIGN KEY (cliente_id) REFERENCES personas(id_usuario) ON DELETE CASCADE,
  FOREIGN KEY (vehiculo_id) REFERENCES vehiculos(id_vehiculo) ON DELETE CASCADE,
  FOREIGN KEY (servicio_id) REFERENCES servicios(id_servicio) ON DELETE CASCADE
);

CREATE TABLE pagos (
  id_pago BIGINT AUTO_INCREMENT PRIMARY KEY,
  cliente_id BIGINT NOT NULL,
  turno_id BIGINT NOT NULL,
  monto_total DECIMAL(10,2) NOT NULL,
  fecha DATETIME NOT NULL,
  FOREIGN KEY (cliente_id) REFERENCES personas(id_usuario) ON DELETE CASCADE,
  FOREIGN KEY (turno_id) REFERENCES turnos(id_turno) ON DELETE CASCADE
);

INSERT INTO personas VALUES (1, 'Jairo', 'Lopez', '2615033284', 'jlo@gmail.com','espana 170', 'contra123', 'Admin');
INSERT INTO servicios VALUES (1, 'Limpieza Interior', 'Aspirado y limpieza profunda.', 50000);
INSERT INTO servicios VALUES (2, 'Lavado Exterior', 'Incluye lavado y encerado.', 60000);
INSERT INTO servicios VALUES (3, 'Lavado Completo y Detailing', 'Incluye limpieza interior y exterior.', 100000);


