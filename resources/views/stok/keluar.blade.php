@extends('layouts.app')

@section('title', 'Stok Keluar')
@section('page_title', 'Stok Keluar')

@section('content')
    @include('stok.partials.form', [
        'action' => route('stok.keluar.store'),
        'buttonLabel' => 'Simpan Stok Keluar',
        'title' => 'Catat Stok Keluar',
        'description' => 'Kurangi stok barang untuk penjualan, kerusakan, atau kebutuhan lain dan simpan riwayat mutasinya.',
        'items' => $items,
        'type' => 'keluar',
    ])
@endsection
