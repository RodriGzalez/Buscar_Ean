<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EanController extends Controller
{
    public function actualizar(Request $request)
    {
       
        $request->validate([
            'metadata_id' => 'required',
            'sku' => 'required|numeric',
            'manufactorecode' => 'required',
        ]);

        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://chwhordernotificationsprod.azurewebsites.net/asignarmanufactore', [
            'metadata_id' => $request->metadata_id,
            'sku' => (int) $request->sku,
            'manufactorecode' => $request->manufactorecode,
        ]);

       
        if ($response->successful()) {
            return back()->with('success', 'EAN actualizado correctamente en el pedido.');
        } else {
            return back()->withErrors(['error' => 'Hubo un fallo al contactar con la API externa.']);
        }
    }
}