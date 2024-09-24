@extends('adminlte::page')

@section('title', 'Form Menu')

@section('content_header')
    <h1>Form Menu</h1>
@stop

@section('content')
    <form action="{{ isset($menu) ? route('menu.update', $menu->id) : route('menu.store') }}" method="POST">
        @csrf
        @if (isset($menu))
            @method('PUT')
        @endif
        <div class="form-group">
            <label for="nama">Nama Menu</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $menu->nama ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $menu->deskripsi ?? '') }}</textarea>
        </div>

        <div class="form-group">
            <label for="harga">Harga (Rp.)</label>
            <input type="number" name="harga" class="form-control" value="{{ old('harga', $menu->harga ?? '') }}" step="500" required>
        </div>

        <div class="form-group">
            <label for="kategori_id">Kategori</label>
            <select name="kategori_id" class="form-control" required>
                @foreach ($categories as $kategori)
                    <option value="{{ $kategori->id }}" {{ (isset($menu) && $kategori->id == $menu->kategori_id) ? 'selected' : '' }}>
                        {{ $kategori->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($menu) ? 'Update' : 'Simpan' }}</button>
    </form>
@stop
