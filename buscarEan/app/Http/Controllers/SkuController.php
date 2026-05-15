<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ImportacionFinalizada;


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

        // 1. Convertimos el contenido del textarea en un array de líneas
        // str_replace limpia los saltos de línea de Windows (\r)
        $rawText = str_replace("\r", "", $request->input('sku_data'));
        $lines = explode("\n", $rawText);
        $dataToSync = [];

        foreach ($lines as $line) {
            // Saltamos líneas vacías
            if (empty(trim($line)))
                continue;

            $column = explode(',', $line);

            // Validamos que la línea tenga exactamente 2 elementos (sku y code)
            if (count($column) == 2) {
                $dataToSync[] = [
                    "referenceId" => trim($column[0]),
                    "manufacturerCode" => trim($column[1])
                ];
            }
        }

        if (empty($dataToSync)) {
            return back()->with('error', 'No se detectaron datos válidos. Revisa el formato sku,code.');
        }

        // 2. Procesamiento por lotes (Chunks de 100) para no saturar la API
        $chunks = array_chunk($dataToSync, 100);
        $results = [];

        foreach ($chunks as $index => $batch) {
            // Contamos cuántos SKUs hay en este lote específico
            $cantidadEnLote = count($batch);

            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'janis-api-key' => config('services.janis.key'),
                    'janis-api-secret' => config('services.janis.token'),
                    'janis-client' => config('services.janis.client'),
                ])->post('https://catalog.janis.in/api/sku-batch', $batch);

                if ($response->successful()) {
                    // Personalizamos el mensaje con la cantidad de SKUs
                    $results[] = "SKUs actualizados: " . $cantidadEnLote;
                } else {
                    $results[] = "Error al procesar " . $cantidadEnLote . " SKUs";
                    Log::error("Error en lote Janis", ['res' => $response->json()]);
                }

            } catch (\Exception $e) {
                $results[] = "Fallo de conexión (" . $cantidadEnLote . " SKUs)";
                Log::error($e->getMessage());
            }
        }

        // 3. Notificación por correo
        // Si no tienes login, puedes poner tu correo directamente
        $user = auth()->user();

        if ($user && $user->email) {
            try {
                // Enviamos los resultados de los lotes Y los datos originales para el CSV
                Mail::to($user->email)->send(new ImportacionFinalizada($results, $dataToSync));

                $mensaje = 'Importación exitosa. Revisa tu correo, se adjuntó el CSV de respaldo.';
            } catch (\Exception $e) {
                Log::error("Error SMTP Office365: " . $e->getMessage());
                $mensaje = 'Sincronización OK, pero el correo falló.';
            }
        }

        return back()->with('status', $mensaje);

    }

}


