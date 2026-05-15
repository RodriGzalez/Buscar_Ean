<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Herramientas | Soporte TI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #004a99;
            /* Azul corporativo */
            --secondary-color: #00a650;
            /* Verde éxito */
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .container {
            max-width: 900px;
        }

        .card-menu {
            border: none;
            border-radius: 20px;
            transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
            background: #ffffff;
            overflow: hidden;
            text-decoration: none !important;
            height: 100%;
        }

        .card-menu:hover {
            transform: translateY(-10px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.15), 0 10px 10px rgba(0, 0, 0, 0.12);
        }

        .icon-box {
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            background-color: #f8f9fa;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>

<body>

    <div class="logout-btn">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm shadow-sm">Cerrar Sesión</button>
        </form>
    </div>

    <div class="container">
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-dark">Herramientas Soporte Omnicanal TI</h1>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-md-5">
                <a href="{{ route('buscar') }}" class="card card-menu shadow-sm">
                    <div class="icon-box text-primary">
                        <h3 class="h5 card-title fw-bold text-dark">Buscar EAN</h3>
                    </div>
                    <div class="card-body text-center">
                        <p class="card-text text-muted">Busqueda de EAN y Actualización de Picking Result.</p>
                    </div>
                </a>
            </div>

            <div class="col-md-5">
                <a href="{{ route('janis.update') }}" class="card card-menu shadow-sm">
                    <div class="icon-box text-success">
                        <h3 class="h5 card-title fw-bold text-dark">Actualizar SKU</h3>
                    </div>
                    <div class="card-body text-center">
                        <p class="card-text text-muted">Actualización de Manufacturer Code en Janis.</p>
                    </div>
                </a>
            </div>
            @if(auth()->user()->isAdmin())
            <div class="col-md-5">
                <a href="{{ route('register') }}" class="card card-menu shadow-sm">
                    <div class="icon-box text-success">
                        <h3 class="h5 card-title fw-bold text-dark">Alta Users</h3>
                    </div>
                    <div class="card-body text-center">
                        <p class="card-text text-muted">Registrar usuarios para plataforma.</p>
                    </div>
                </a>
            </div>
            @endif
        </div>




        <div class="mt-5 text-center">
            <hr class="w-25 mx-auto">
            <p class="text-secondary small mb-0">© 2026 Soporte Omnicanal TI Chedraui</p>
            <p class="text-secondary small">Desarrollado por <strong>Rodrigo González A.</strong></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>