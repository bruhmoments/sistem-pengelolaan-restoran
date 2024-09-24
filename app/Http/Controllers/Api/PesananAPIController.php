<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;

class PesananAPIController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'total' => 'required|numeric',
            'status' => 'required|string',
            'detail_pesanan' => 'required|array',
        ]);

        $pesanan = Pesanan::create($validated);

        foreach ($validated['detail_pesanan'] as $item) {
            DetailPesanan::create([
                'pesanan_id' => $pesanan->id,
                'menu_id' => $item['menu_id'],
                'qty' => $item['qty'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        return response()->json($pesanan, 201);
    }
}
