@extends('adminlte::page')

@section('title', 'Form Kategori')

@section('content_header')
    <h1>Form Kategori</h1>
@stop

@section('content')
    <form action="{{ isset($kategori) ? route('kategori.update', $kategori->id) : route('kategori.store') }}" method="POST">
        @csrf
        @if(isset($kategori))
            @method('PUT')
        @endif
        <div class="form-group">
            <label for="nama">Nama Kategori</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $kategori->nama ?? '') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">{{ isset($kategori) ? 'Update' : 'Simpan' }}</button>
    </form>
@stop
