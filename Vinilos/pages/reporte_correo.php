<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Enviar Reporte por Correo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #1a237e, #7e57c2);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .form-container {
            background-color: rgba(0, 0, 0, 0.85);
            padding: 40px;
            border-radius: 15px;
            width: 100%;
            max-width: 500px;
            text-align: center;
        }
        .btn-enviar {
            background-color: #4caf50;
            border: none;
            padding: 10px;
            width: 100%;
            font-size: 16px;
        }
        .btn-enviar:hover {
            background-color: #388e3c;
        }
        .grafica-container {
            margin-top: 20px;
            width: 100%;
            max-width: 600px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>📩 Enviar Reporte de Ventas</h2>
    <form action="enviar_reporte.php" method="post" onsubmit="convertirGrafica();">
        <div class="mb-3">
            <label for="usuario" class="form-label">Usuario de Administrador:</label>
            <input type="text" class="form-control" id="usuario" name="usuario" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha Inicial:</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
        </div>

        <div class="mb-3">
            <label for="fecha_fin" class="form-label">Fecha Final:</label>
            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
        </div>

        <input type="hidden" id="graficaBase64" name="grafica">

        <div class="text-center">
            <button type="submit" class="btn btn-enviar">Enviar Reporte</button>
        </div>
    </form>
</div>

<!-- Contenedor de la gráfica -->
<div class="grafica-container">
    <canvas id="graficoVentas"></canvas>
</div>

<script>
// **Generar gráfica de ventas**
const ctx = document.getElementById('graficoVentas').getContext('2d');

const data = {
    labels: ['Producto A', 'Producto B', 'Producto C'], // 🔹 Aquí debes cargar los datos de ventas
    datasets: [{
        label: 'Unidades Vendidas',
        data: [10, 20, 30], // 🔹 Aquí debes cargar los valores reales de cada producto
        backgroundColor: 'rgba(54, 162, 235, 0.5)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
    }]
};

const config = {
    type: "bar",
    data: data,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Cantidad Vendida' }
            },
            x: {
                title: { display: true, text: 'Nombre del Producto' }
            }
        }
    }
};

// **Crear la gráfica**
const chart = new Chart(ctx, config);

// **Convertir gráfica en imagen para el PDF**
function convertirGrafica() {
    const imgData = document.getElementById('graficoVentas').toDataURL('image/png');
    document.getElementById('graficaBase64').value = imgData;
}
</script>

</body>
</html>
