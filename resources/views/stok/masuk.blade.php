@extends('layouts.app')

@section('title', 'Stok Masuk')
@section('page_title', 'Stok Masuk')

@section('content')
    @include('stok.partials.form', [
        'action' => route('stok.masuk.store'),
        'buttonLabel' => 'Simpan Stok Masuk',
        'title' => 'Catat Stok Masuk',
        'description' => 'Tambahkan stok barang yang baru datang dan simpan jejak mutasinya secara otomatis.',
        'items' => $items,
        'type' => 'masuk',
    ])
@endsection
