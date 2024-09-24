<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuAPIController extends Controller
{
    public function index()
    {
        $menu = Menu::with('kategori')
            ->whereNull('deleted_at')
            ->get(['id', 'nama', 'harga', 'kategori_id']);

        return response()->json($menu->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'harga' => $item->harga,
                'kategori' => [
                    'id' => $item->kategori->id,
                    'nama' => $item->kategori->nama,
                ],
            ];
        }));
    }
}
