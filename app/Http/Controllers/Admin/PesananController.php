<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with('detailPesanan')->get();

        return view('admin.pesanan.index', compact('pesanan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $statusPesanan = ['pending', 'diproses', 'dikirim', 'selesai'];
        $indexCurrentStatus = array_search($pesanan->status, $statusPesanan);

        if ($indexCurrentStatus !== false && $indexCurrentStatus < count($statusPesanan) - 1) {
            $pesanan->status = $statusPesanan[$indexCurrentStatus + 1];
            $pesanan->save();

            Log::info('Send email to '.$pesanan->email_pembeli.': status pesanan anda sekarang adalah '.$pesanan->status);
            return redirect()->back()->with('success', 'Status pesanan diubah.');
        }

        return redirect()->back()->with('error', 'Status pesanan gagal terubah.');
    }

    public function laporan(Request $request)
    {
        $dateFrom = $request->input('date_from', today()->startOfDay()->toDateString());
        $dateTo = $request->input('date_to', today()->endOfDay()->toDateString());

        // Karena input date ngga ada time jadi otomatis startofday
        $dateFrom = Carbon::parse($dateFrom);
        $dateTo = Carbon::parse($dateTo)->endOfDay();

        $jumlahPesanan = Pesanan::whereBetween('created_at', [$dateFrom, $dateTo])
        ->count();

        $jumlahPemasukan = Pesanan::whereBetween('created_at', [$dateFrom, $dateTo])
        ->sum('total');

        $laporanKategori = DB::table('pesanan as p')
        ->join('detail_pesanan as dp', 'p.id', '=', 'dp.pesanan_id')
        ->join('menu as m', 'dp.menu_id', '=', 'm.id')
        ->join('kategori as k', 'm.kategori_id', '=', 'k.id')
        ->select('k.nama as nama',
                 DB::raw('SUM(dp.qty) as total_qty'),
                 DB::raw('SUM(dp.qty * m.harga) as total_harga'))
        ->whereBetween('p.created_at', [$dateFrom, $dateTo])
        ->groupBy('k.nama')
        ->get();

        $laporanMenu = DB::table('pesanan as p')
        ->join('detail_pesanan as dp', 'p.id', '=', 'dp.pesanan_id')
        ->join('menu as m', 'dp.menu_id', '=', 'm.id')
        ->join('kategori as k', 'm.kategori_id', '=', 'k.id')
        ->select('m.nama as nama',
                 DB::raw('SUM(dp.qty) as total_qty'),
                 DB::raw('SUM(dp.qty * m.harga) as total_harga'))
        ->whereBetween('p.created_at', [$dateFrom, $dateTo])
        ->groupBy('m.nama')
        ->get();

        $pesanan = Pesanan::whereBetween('created_at', [$dateFrom, $dateTo])
        ->with('detailPesanan')
        ->get();

        return view('admin.pesanan.report', compact('jumlahPesanan', 'jumlahPemasukan', 'pesanan', 'laporanKategori', 'laporanMenu', 'dateFrom', 'dateTo'));
    }
}
