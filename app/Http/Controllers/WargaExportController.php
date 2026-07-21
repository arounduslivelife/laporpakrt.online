<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warga;

class WargaExportController extends Controller
{
    public function print(Request $request)
    {
        $query = Warga::where('rt_id', auth()->user()->rt_id);

        if ($request->has('search') || $request->has('domisili') || $request->has('penghasilan') || $request->has('perkawinan')) {
            $query->when($request->search, fn($q) =>
                $q->where(fn($q) =>
                    $q->where('name', 'like', "%{$request->search}%")
                      ->orWhere('nik', 'like', "%{$request->search}%")
                )
            )
            ->when($request->domisili, fn($q) =>
                $q->where('status_domisili', $request->domisili)
            )
            ->when($request->penghasilan, fn($q) =>
                $q->where('penghasilan', $request->penghasilan)
            )
            ->when($request->perkawinan, fn($q) =>
                $q->where('status_perkawinan', $request->perkawinan)
            )
            ->when($request->disabilitas !== null && $request->disabilitas !== '', fn($q) =>
                $q->where('is_disabilitas', $request->disabilitas)
            )
            ->when($request->bantuan, fn($q) =>
                $q->whereHas('bantuans', fn($q2) => $q2->where('bantuan_id', $request->bantuan))
            );
        }

        $wargas = $query->orderBy('no_kk')->orderBy('id')->get();
        return view('exports.warga-print', compact('wargas'));
    }
}
