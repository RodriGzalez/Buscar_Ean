<!DOCTYPE html>
<html lang="es">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Buscador EAN</title>
</head>
<body class="bg-light">

    <div class="container mt-5">
    <form method="POST" action="{{ route('logout') }}">
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
    @csrf
    <button class="btn btn-danger" type="submit">
        Cerrar Sesión
    </div>
    </button>
    </form>
        <h2 class="mb-4">Buscar EAN</h2>
    
        
        <form action="{{ url('/buscar') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar por Material o EAN..." value="{{ $buscar }}">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </form>

        <table class="table table-striped table-hover bg-white shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Material (MATNR)</th>
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


    </div>

</body>
</html>