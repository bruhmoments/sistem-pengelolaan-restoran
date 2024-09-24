@extends('adminlte::page')

@section('title', 'Menu')

@section('content_header')
    <h1>Data Pesanan</h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Email Pelanggan</th>
                <th>Total</th>
                <th>Status</th>
                <th>Detail</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pesanan as $p)
                <tr>
                    <td>{{ $p->email_pembeli }}</td>
                    <td>Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                    <td>{{ $p->status }}</td>
                    <td>
                        <ul>
                            @foreach ($p->detailPesanan as $detail)
                                <li>{{ $detail->menu->nama }} - {{ $detail->qty }}x</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        @if ($p->status != "selesai")
                            <form action="{{ route('pesanan.updateStatus', $p->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                @php
                                    $statusUrutan = ['pending', 'diproses', 'dikirim', 'selesai'];
                                    $statusUrutanText = ['Proses Pesanan', 'Kirim Pesanan', 'Selesaikan Pesanan'];
                                    $indexCurrentStatus = array_search($p->status, $statusUrutan);
                                @endphp
                                <button type="submit">{{ $statusUrutanText[$indexCurrentStatus] }}</button>
                            </form>
                        @else
                            Pesanan Selesai
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
