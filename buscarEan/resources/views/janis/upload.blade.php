<!-- resources/views/janis/upload.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización Masiva Janis</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .form-label { font-weight: bold; }
    </style>
</head>
<body>

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-4">
                        <form method="GET" action="{{ route('menu') }}">
                            @csrf
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button class="btn btn-primary" type="submit">Regresar al Menu</button>
                            </div>
                        </form>
                <h3 class="text-center mb-4 text-primary">Actualizar Manufacturer Code</h3>
                
                @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('janis.update') }}" method="POST" id="janisForm">
                    @csrf
                    
                    <!-- 1. Input de Correo -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted">Notificación de envío</label>
                        <div class="alert alert-light border d-flex align-items-center">
                            <span class="me-2">📧</span>
                            <span>Se enviará el reporte a: <strong>{{ auth()->user()->email }}</strong></span>
                        </div>
                    </div>

                    <!-- 2. Text Area -->
                    <div class="mb-3">
                        <label for="sku_data" class="form-label">Listado de SKUs (sku,manufacturerCode)</label>
                        <textarea name="sku_data" 
                                  id="sku_data" 
                                  rows="12" 
                                  class="form-control font-monospace" 
                                  placeholder="3727332,750105537205&#10;3684715,750327090137" 
                                  required></textarea>
                        <div id="counter" class="form-text text-end">Registros: 0 / 1000</div>
                    </div>

                    <!-- 3. Botón -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg" id="btnSubmit">
                            Actualizar en Janis
                        </button>
                    </div>
                </form>
            </div>

            <footer class="text-center mt-4 text-muted small">
                © 2026 Soporte Omnicanal TI Chedraui - Desarrollado por Rodrigo González A.
            </footer>
        </div>
    </div>
</div>

<!-- JavaScript para conteo dinámico -->
<script>
    const textarea = document.getElementById('sku_data');
    const counter = document.getElementById('counter');
    const btn = document.getElementById('btnSubmit');

    textarea.addEventListener('input', function() {
        const lines = this.value.split('\n').filter(line => line.trim() !== '');
        const count = lines.length;
        
        counter.innerText = `Registros: ${count} / 1000`;
        
        if (count > 1000) {
            counter.classList.add('text-danger');
            btn.disabled = true;
        } else {
            counter.classList.remove('text-danger');
            btn.disabled = false;
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>