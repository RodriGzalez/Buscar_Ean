<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment; // Importante


class ImportacionFinalizada extends Mailable
{
    use Queueable, SerializesModels;

    public $detalles;
    protected $csvData;

    public function __construct($detalles, $csvData)
    {
        $this->detalles = $detalles;
        $this->csvData = $csvData; // Aquí recibimos el array de SKUs
    }

    public function build()
    {
        return $this->subject('Reporte de Importación Janis + Archivo de Respaldo')
                    ->view('emails.importacion_status');
    }

    /**
     * Definimos los adjuntos del correo
     */
    public function attachments(): array
    {
        // Creamos el contenido del CSV en memoria
        $csvContent = "sku,manufacturerCode\n";
        foreach ($this->csvData as $item) {
            $csvContent .= "{$item['referenceId']},{$item['manufacturerCode']}\n";
        }

        return [
            Attachment::fromData(fn () => $csvContent, 'respaldo_importacion.csv')
                ->withMime('text/csv'),
        ];
    }
}