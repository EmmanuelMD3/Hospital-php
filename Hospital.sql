CREATE DATABASE Hospital;

USE Hospital; 


CREATE TABLE Servicio(
	IDServicio INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nombre_servicio VARCHAR(15) NOT NULL
);

CREATE TABLE Empleados(
	IDEmpleado INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    matricula INT UNIQUE NOT NULL,
    nombre VARCHAR(20) NOT NULL,
    apellidoP VARCHAR(20) NOT NULL,
    apellidoM VARCHAR(20) NOT NULL,
    IDServicio INT NOT NULL,
    sueldo DECIMAL(6,2) NOT NULL,
    correo VARCHAR(30) NOT NULL,
    usuario VARCHAR(50) NOT NULL,
    contrasenia_hash VARCHAR(255)NOT NULL,
    roles INT NOT NULL,
    foto BLOB NOT NULL,
    FOREIGN KEY (IDServicio) REFERENCES Servicio(IDServicio)
);

CREATE TABLE Paciente(
	IDPaciente INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nombre VARCHAR(20) NOT NULL,
    apellidoP VARCHAR(20) NOT NULL,
    apellidoM VARCHAR(20) NOT NULL,
    NSS INT UNIQUE NOT NULL,
    telefono VARCHAR(10) NOT NULL,
    correo VARCHAR(30) NOT NULL,
    usuario VARCHAR(10) NOT NULL,
    contrasenia_hash VARCHAR(30)NOT NULL,
    direccion VARCHAR(50) NOT NULL,
    foto BLOB NOT NULL
);

show table status;