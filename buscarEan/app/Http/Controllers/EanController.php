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

    try {

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(
            'https://chwhordernotificationsprod.azurewebsites.net/asignarmanufactore',
            [
                'metadata_id' => $request->metadata_id,
                'sku' => (int)$request->sku,
                'manufactorecode' => $request->manufactorecode,
            ]
        );

        if ($response->successful()) {

            return response()->json([
                'success' => true,
                'message' => 'EAN actualizado correctamente.'
            ]);
        }

        // Intentamos obtener el mensaje de la API
        $mensaje = $response->json()['message']
            ?? $response->body()
            ?? 'La API devolvió un error.';

        return response()->json([
            'success' => false,
            'message' => $mensaje
        ], $response->status());

    } catch (\Throwable $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);

    }
}

    public function validarJanis(Request $request)
    {
        $pedidoId = $request->input('pedido');
        $skuId = $request->input('sku');

        // Preparamos los Headers con tus credenciales seguras
        $janisHeaders = [
            'Content-Type' => 'application/json',
            'janis-api-key' => config('services.janis.key'),
            'janis-api-secret' => config('services.janis.token'),
            'janis-client' => config('services.janis.client'),
        ];

        // 1. Consultar API de Pedidos
        $orderResponse = Http::withHeaders($janisHeaders)
            ->get("https://oms.janis.in/api/order/{$pedidoId}");

        // 2. Consultar API de SKU
        $skuResponse = Http::withHeaders($janisHeaders)
            ->get("https://catalog.janis.in/api/sku/?filters[referenceId]={$skuId}");

        // Validar si falló la conexión
        /*   if ($orderResponse->failed() || $skuResponse->failed()) {
              return response()->json(['error' => 'No se pudo contactar con Janis. Verifica los datos.'], 400);
          } */
        if ($orderResponse->failed() || $skuResponse->failed()) {

            return response()->json([
                'order_status' => $orderResponse->status(),
                'order_body' => $orderResponse->body(),

                'sku_status' => $skuResponse->status(),
                'sku_body' => $skuResponse->body(),
            ]);
        }

        $orderData = $orderResponse->json();
        $skuData = $skuResponse->json();

        // Extraer los datos (Janis suele devolver arrays cuando usas filtros en el catálogo)
        $nombreSku = $skuData[0]['name'] ?? 'Descripción no encontrada';
        $referenciaSku = $skuData[0]['referenceId'] ?? $skuId;
        $pedidoCorto = $orderData['commerceSequentialId'] ?? 'N/A';

        // Devolver la información lista para el modal
        return response()->json([
            'commerceSequentialId' => $pedidoCorto,
            'referenceId' => $referenciaSku,
            'name' => $nombreSku,
        ]);
    }
}
