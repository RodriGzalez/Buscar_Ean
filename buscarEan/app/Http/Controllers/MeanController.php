<?php

namespace App\Http\Controllers;

use App\Models\mean_db;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MeanController extends Controller
{

public function buscar(Request $request)
{
    // 1. Obtenemos el término de búsqueda del input
    $buscar = $request->input('buscar');

    if (empty($buscar)) {
            $datos = collect(); // Crea una colección vacía
            return view('buscar_ean', compact('datos', 'buscar'));
        }
        
    // 2. Ejecutamos la consulta
    $datos = DB::table('mean_data')
        ->where('MEAN_MATNR', 'LIKE', '%' . $buscar) // El % al inicio ignora los ceros
        ->where('MEAN_MEINH', 'ST')
        ->where('MEAN_EANTP', 'LIKE', 'Z%')
        ->orderBy('MEAN_LFNUM')
        ->paginate(10); // Usamos paginate para que funcione tu código de la vista

    // 3. Retornamos la vista con los resultados
    return view('buscar_ean', compact('datos', 'buscar'));
}

}