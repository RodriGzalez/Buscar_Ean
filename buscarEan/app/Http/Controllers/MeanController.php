<?php

namespace App\Http\Controllers;

use App\Models\mean_db;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MeanController extends Controller
{

public function buscar(Request $request)
{
    
    $buscar = $request->input('buscar');

    if (empty($buscar)) {
            $datos = collect(); 
            return view('buscar_ean', compact('datos', 'buscar'));
        }
        
    
    $datos = DB::table('mean_data')
        ->where('MEAN_MATNR', 'LIKE', '%' . $buscar)
        ->where('MEAN_MEINH', 'ST')
        ->where('MEAN_EANTP', 'LIKE', 'Z%')
        ->orderBy('MEAN_LFNUM')
        ->paginate(10); 

    
    return view('buscar_ean', compact('datos', 'buscar'));
}

}