<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class SkuController extends Controller
{
    public function index()
    {
        return view('janis.upload');
    }

    public function update(Request $request)
    {
        $request->validate([
            'sku_data' => 'required|string',
        ]);

        // 1. Procesamiento de datos y respaldo del texto original
        $rawText = str_replace("\r", "", $request->input('sku_data'));
        $lines = explode("\n", $rawText);
        
        $dataToSync = [];
        $originalRows = []; // Aquí guardaremos lo que el usuario mandó

        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            if (empty($trimmedLine)) continue;

            $column = explode(',', $trimmedLine);
            if (count($column) == 2) {
                $sku = trim($column[0]);
                $code = trim($column[1]);
                
                $dataToSync[] = [
                    "referenceId" => $sku,
                    "manufacturerCode" => $code
                ];
                
                // Guardamos la fila original para el reporte
                $originalRows[] = ['sku' => $sku, 'code' => $code];
            }
        }

        if (empty($dataToSync)) {
            return back()->with('error', 'No se detectaron datos válidos. Revisa el formato sku,code.');
        }

        // 2. Procesamiento por lotes en Janis
        $chunks = array_chunk($dataToSync, 100);
        $batchResults = [];

        foreach ($chunks as $index => $batch) {
            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'janis-api-key' => config('services.janis.key'),
                    'janis-api-secret' => config('services.janis.token'),
                    'janis-client' => config('services.janis.client'),
                ])->post('https://catalog.janis.in/api/sku-batch', $batch);

                // Guardamos el resultado del lote para aplicarlo a las filas originales
                $status = $response->successful() ? 'Éxito' : 'Error API Janis';
                $batchResults[$index] = $status;

            } catch (\Exception $e) {
                $batchResults[$index] = 'Fallo de conexión';
                Log::error($e->getMessage());
            }
        }

        // 3. Generación del CSV con datos originales + resultados
        $fileName = 'sku_actualizado_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($originalRows, $batchResults) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM para Excel
            
            fputcsv($file, ['REPORTE DETALLADO DE IMPORTACIÓN']);
            fputcsv($file, ['Fecha', now()->toDateTimeString()]);
            fputcsv($file, []);
            
            // Encabezados de la tabla
            fputcsv($file, ['SKU (Original)', 'Código (Original)', 'Estado del Lote']);

            // Mapeamos cada fila original con el resultado de su lote correspondiente
            foreach ($originalRows as $index => $row) {
                // Calculamos a qué lote (chunk) pertenecía esta fila
                $chunkIndex = floor($index / 100);
                $status = $batchResults[$chunkIndex] ?? 'No procesado';

                fputcsv($file, [
                    $row['sku'],
                    $row['code'],
                    $status
                ]);
            }
            
            fclose($file);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
}