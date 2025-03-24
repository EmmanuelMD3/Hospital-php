<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Paciente</title>
    <link rel="stylesheet" href="styles6.css">
</head>
<body>

    <div class="sidebar">
        <div class="profile">
            <img src="default-profile.png" alt="Foto de perfil">
            <h3>Juan Pérez</h3>
            <p>Paciente</p>
            <p><strong>NSS:</strong> 123-45-6789</p>
        </div>
        <ul>
            <li><a href="#">📅 Ver Citas</a></li>
            <li><a href="#">🗓️ Agendar Cita</a></li>
            <li><a href="#">📜 Historial Médico</a></li>
            <li><a href="#">💊 Tratamientos</a></li>
            <li><a href="#">👨‍⚕️ Médicos Disponibles</a></li>
            <li><a href="Cerrar_Sesion.php">🚪 Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h2>Bienvenido, Juan Pérez</h2>
        </header>

        <section class="info-panel">
            <div class="info-box">
                <h3>📅 Próxima Cita</h3>
                <p><strong>Servicio:</strong> Consulta General</p>
                <p><strong>Fecha:</strong> 18 de Marzo</p>
                <p><strong>Hora:</strong> 3:00 PM</p>
                <button>🔍 Ver Detalles</button>
            </div>

            <div class="info-box">
                <h3>📜 Historial Médico</h3>
                <p>Última consulta: <strong>12 de Marzo</strong></p>
                <p>Diagnóstico: <strong>Hipertensión</strong></p>
                <button>📂 Ver Historial</button>
            </div>
        </section>

        <section class="agendar-cita">
            <h2>🗓️ Agendar Nueva Cita</h2>
            <form>
                <label for="servicio">Servicio:</label>
                <select id="servicio" name="servicio">
                    <option value="consulta_general">Consulta General</option>
                    <option value="pediatria">Pediatría</option>
                    <option value="cardiologia">Cardiología</option>
                    <option value="dermatologia">Dermatología</option>
                </select>
                
                <label for="fecha">Fecha:</label>
                <input type="date" id="fecha" name="fecha">

                <label for="hora">Hora:</label>
                <input type="time" id="hora" name="hora">

                <button type="submit">✔ Agendar Cita</button>
            </form>
        </section>
    </div>

</body>
</html>
