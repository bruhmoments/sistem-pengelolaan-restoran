<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\Log;

class OrderFoodController extends Controller
{
    public function index()
    {
        $menus = Menu::with('kategori')->get();
        return view('orderfood', compact('menus'));
    }

    public function storePesanan(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'email_pembeli' => 'required|email',
            'total' => 'required|numeric',
        ]);

        $validated['status'] = 'pending';

        $pesanan = Pesanan::create($validated);

        $validateDetail = $request->validate([
            'detail_pesanan' => 'required|array',
        ]);

        foreach ($validateDetail['detail_pesanan'] as $item) {
            DetailPesanan::create([
                'pesanan_id' => $pesanan->id,
                'menu_id' => $item['menu_id'],
                'qty' => $item['qty'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        Log::info('Send email to '.$validated['email_pembeli'].': Order has been placed, thank you for your order!');

        return response()->json($pesanan, 201);
    }
}
