<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratController extends Controller
{
    public function pdf($id)
    {
        $surat = Surat::where('rt_id', auth()->user()->rt_id)->with(['warga', 'rt'])->findOrFail($id);
        
        $pdf = Pdf::loadView('exports.surat-pdf', compact('surat'));
        return $pdf->stream('Surat_Pengantar_' . str_replace('/', '_', $surat->nomor_surat) . '.pdf');
    }
}
