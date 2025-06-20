<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - PetsConnect</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background: #f8fafc;
            color: #222;
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .dashboard-container {
            flex: 1;
            max-width: 900px;
            margin: 40px auto 0 auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            padding: 32px 24px;
        }
        h1 {
            color: #e07a5f;
            font-size: 2rem;
            margin-bottom: 0.5em;
            text-align: center;
        }
        .resumen {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 2em;
            flex-wrap: wrap;
        }
        .card {
            flex: 1 1 200px;
            background: #f4f4f4;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .card-title {
            font-weight: 700;
            color: #3d405b;
            margin-bottom: 0.5em;
        }
        .card-value {
            font-size: 2rem;
            color: #e07a5f;
            font-weight: bold;
        }
        .tabla-mascotas {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2em;
        }
        .tabla-mascotas th, .tabla-mascotas td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        .tabla-mascotas th {
            background: #f4a261;
            color: #fff;
        }
        .tabla-mascotas tr:last-child td {
            border-bottom: none;
        }
        .footer {
            background: #3d405b;
            color: #fff;
            text-align: center;
            padding: 18px 0 12px 0;
            margin-top: 40px;
            border-radius: 0 0 18px 18px;
        }
        @media (max-width: 700px) {
            .dashboard-container {
                padding: 16px 4px;
            }
            .resumen {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Panel de Control</h1>
        <div class="resumen">
            <div class="card">
                <div class="card-title">Mascotas adoptadas</div>
                <div class="card-value">12</div>
            </div>
            <div class="card">
                <div class="card-title">En proceso</div>
                <div class="card-value">4</div>
            </div>
            <div class="card">
                <div class="card-title">Voluntarios</div>
                <div class="card-value">8</div>
            </div>
        </div>
        <h2 style="color:#3d405b;">Mascotas en adopción</h2>
        <table class="tabla-mascotas">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Responsable</th>
                    <th>Prioridad</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Luna</td>
                    <td>Juan Pérez</td>
                    <td>Baja</td>
                </tr>
                <tr>
                    <td>Rocky</td>
                    <td>Laura Gómez</td>
                    <td>Media</td>
                </tr>
                <tr>
                    <td>Max</td>
                    <td>Andrés Ruiz</td>
                    <td>Alta</td>
                </tr>
                <tr>
                    <td>Simba</td>
                    <td>María López</td>
                    <td>Crítica</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="footer">
        Diseñado por PetsConnect &copy; 2025
    </div>
</body>
</html>