<!DOCTYPE html>
<html lang="es">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/images.png') }}">
    <title>Buscador EAN</title>

    @vite(['resources/css/style.css'])
</head>

<body class="bg-light">

    <div class="container mt-5">
        <form method="GET" action="{{ route('menu') }}">
            @csrf
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn btn-primary" type="submit">Regresar al Menu</button>
            </div>
        </form>
        <h2 class="mb-4">Buscar EAN</h2>


        <form action="{{ url('/buscar') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar EAN..." value="{{ $buscar }}">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </form>
        @if(count($datos) > 0)
            <table class="table table-striped table-hover bg-white shadow-sm mb-2" id="table-ean">
                <thead class="table-dark">
                    <tr>
                        <th>SKU (MATNR)</th>
                        <th>EAN (EAN11)</th>
                        <th>Tipo (EANTP)</th>
                        <th>Lfnum (MEINH)</th>
                        <th>Unidad (MEINH)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datos as $item)
                        <tr>
                            <td>{{ $item->MEAN_MATNR }}</td>
                            <td>{{ $item->MEAN_EAN11 }}</td>
                            <td>{{ $item->MEAN_EANTP }}</td>
                            <td>{{ $item->MEAN_LFNUM }}</td>
                            <td>{{ $item->MEAN_MEINH }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">No se encontraron resultados para mostrar.</div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-5">
                <h3 class="mt-4">Actualizar Picking Result</h3>

                @if(session('success'))
                    <div class="alert alert-success mt-3">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger mt-3">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form id="formActualizar" action="{{ route('ean.actualizar') }}" method="POST" class="mb-2">
                    @csrf
                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text">Pedido</span>
                        <input type="text" name="metadata_id" id="metadata_id" class="form-control" required>
                    </div>

                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text" style="width: 110px;">SKU</span>
                        <input type="number" name="sku" id="sku" class="form-control"
                            value="{{ isset($datos[0]) ? ltrim($datos[0]->MEAN_MATNR, '0') : '' }}" required>
                    </div>

                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text">Manufacture</span>
                        <input type="text" name="manufactorecode" class="form-control"
                            value="{{ $datos[0]->MEAN_EAN11 ?? '' }}" required>
                    </div>
                    <button type="submit" class="btn btn-success w-10">Actualizar</button>
                </form>
            </div>
        </div>

    </div>

    <footer class="footer-main">
        <div class="container">
            <p>&copy; 2026 Soporte Omnicanal TI Chedraui</p>
            <p>Desarrollado por <span class="author">Rodrigo González A.</span></p>
        </div>
    </footer>
    <!-- Alerta de pedido -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        document.getElementById('formActualizar').addEventListener('submit', async function (event) {

            event.preventDefault();

            const form = document.getElementById('formActualizar');

            const pedidoId = document.getElementById('metadata_id').value;
            const skuId = document.getElementById('sku').value;
            const manufacturer = document.querySelector('input[name="manufactorecode"]').value;

            try {

                // Loader Janis
                Swal.fire({
                    title: 'Buscando información en Janis...',
                    text: 'Espere un momento...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const validarUrl = "{{ route('ean.validar_janis') }}";

                const validarResponse = await fetch(
                    `${validarUrl}?pedido=${pedidoId}&sku=${skuId}`
                );

                const validarData = await validarResponse.json();

                if (!validarResponse.ok) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: validarData.error || 'No fue posible consultar Janis.'
                    });

                    return;
                }

                // Confirmación
                const confirmacion = await Swal.fire({

                    title: '¿Estás por actualizar Picking Result?',

                    html: `
                <div style="text-align:left">
                    <p><strong>Pedido:</strong> ${validarData.commerceSequentialId}</p>
                    <p><strong>SKU:</strong> ${validarData.referenceId}</p>
                    <p><strong>Descripción:</strong> ${validarData.name}</p>
                    <p><strong>Manufacturer:</strong> ${manufacturer}</p>
                </div>
            `,

                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonText: 'Aceptar',

                    cancelButtonText: 'Cancelar',

                    confirmButtonColor: '#28a745',

                    cancelButtonColor: '#d33'

                });

                if (!confirmacion.isConfirmed) {
                    return;
                }

                // Loader Azure
                Swal.fire({
                    title: 'Actualizando EAN...',
                    html: 'Espere un momento...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const formData = new FormData(form);
                const actualizarResponse = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const resultado = await actualizarResponse.json();
                Swal.close();
                if (actualizarResponse.ok && resultado.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Actualización realizada',
                        text: resultado.message
                    });
                    form.reset();
                } else {
                    await Swal.fire({
                        icon: 'error',
                        title: 'No fue posible actualizar',
                        text: resultado.message
                    });
                }
            } catch (error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al comunicarse con el servidor.'
                });

            }

        });

    </script>
</body>


</html>