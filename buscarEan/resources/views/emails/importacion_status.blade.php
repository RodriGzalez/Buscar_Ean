<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
        }

        .header {
            background: #0056b3;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 20px;
            border: 1px solid #ddd;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .footer {
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Reporte de Actualización Janis</h2>
    </div>
    <div class="content">
        <h3>Resumen de la operación</h3>
        <p>Se ha completado la sincronización masiva con Janis.</p>

        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <strong>Detalle de la Actualización:</strong>
            <ul style="margin-top: 10px;">
                @foreach($detalles as $resultado)
                    <li>{{ $resultado }}</li>
                @endforeach
            </ul>
        </div>
        <p class="footer">
            © 2026 Soporte Omnicanal TI Chedraui - Desarrollado por Rodrigo González A.
        </p>
    </div>
</body>

</html>