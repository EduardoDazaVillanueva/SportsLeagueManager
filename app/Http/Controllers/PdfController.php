<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Support\Facades\Storage;

use App\Models\ParticipaEnLiga;
use App\Models\Ligas;

class PdfController extends Controller
{
    public function generateAndSendPdf($ligaId)
    {

        $liga = Ligas::find($ligaId);
        if (!$liga) {
            return response()->json(['message' => 'Liga no encontrada.'], 404);
        }
        $jugadores = ParticipaEnLiga::where('liga_id', $liga->id)
            ->join('jugadores', 'participa_en_ligas.jugadores_id', '=', 'jugadores.id')
            ->join('users', 'jugadores.user_id', '=', 'users.id')
            ->select(
                'participa_en_ligas.*',
                'users.name as user_name',
                'users.id as user_id'
            )
            ->orderBy('participa_en_ligas.puntos', 'desc')
            ->get();
        
        $user = Auth()->user();

        // Contenido HTML que quieres convertir en PDF
        $htmlContent = view('pdf_template', compact('liga', 'jugadores', 'user'))->render();

        // Configurar el objeto Html2Pdf
        $html2pdf = new Html2Pdf();
        $html2pdf->writeHTML($htmlContent);

        // Guardar el PDF en un archivo temporal
        $filePath = storage_path('app/temp_documento.pdf');
        $html2pdf->output($filePath, 'F');

        // Enviar el PDF por correo electrónico
        $this->sendPdfByEmail($filePath);

        // Eliminar el archivo temporal después de enviarlo
        unlink($filePath);

        return redirect('/');
    }

    private function sendPdfByEmail($filePath)
    {
        $recipient = 'eduardodazavillanueva@gmail.com';

        Mail::send([], [], function ($message) use ($filePath, $recipient) {
            $message->to($recipient)
                ->subject('Aquí está tu PDF')
                ->attach($filePath, [
                    'as' => 'documento.pdf',
                    'mime' => 'application/pdf',
                ])
                ->html('<p>Adjunto encontrarás el PDF que solicitaste.</p>');
        });
    }
}
