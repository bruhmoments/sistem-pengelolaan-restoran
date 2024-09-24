@extends('adminlte::page')

@section('title', 'Menu')

@section('content_header')
    <h1>Laporan Harian Pesanan</h1>

    <br/>
    <form action="{{ route('pesanan.laporan') }}" method="GET">
        <label for="date_from">Tanggal Dari:</label>
        <input type="date" name="date_from" value="{{ old('date_from', $dateFrom->format('Y-m-d') ?? '') }}" required>
        <br/>
        <label for="date_to">Tanggal Sampai:</label>
        <input type="date" name="date_to" value="{{ old('date_to', $dateTo->format('Y-m-d') ?? '') }}" required>

        <button type="submit">Filter</button>
    </form>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <hr/>
    <div style="margin: 16px 0 64px 0">
        <h4>Jumlah Transaksi: {{ $jumlahPesanan }}</h4>
    </div>
    <div style="margin: 16px 0 64px 0">
        <h4>Total Penjualan (Rupiah): {{ number_format($jumlahPemasukan, 0, ',', '.') }}</h4>
    </div>

    <table class="table">
        <thead>
            <th colspan="3"> Tabel Data Penjualan Berdasarkan Kategori </th>
            <tr>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Total Penjualan (Rupiah)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporanKategori as $data)
                <tr>
                    <td>{{ $data->nama }}</td>
                    <td>{{ $data->total_qty }}</td>
                    <td>{{ number_format($data->total_harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th colspan="3"> Tabel Data Penjualan Berdasarkan Menu </th>
            </tr>
            <tr>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Total Penjualan (Rupiah)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporanMenu as $data)
                <tr>
                    <td>{{ $data->nama }}</td>
                    <td>{{ $data->total_qty }}</td>
                    <td>{{ number_format($data->total_harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
