@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('page_title', 'Tambah Barang')

@section('content')
    <section class="app-card p-6">
        <div class="max-w-5xl space-y-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Tambah Barang Baru</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Isi data master barang, harga, stok awal, stok minimum, dan foto opsional.
                </p>
            </div>

            @include('barang.partials.form', [
                'action' => route('barang.store'),
                'buttonLabel' => 'Simpan Barang',
                'barang' => null,
                'categories' => $categories,
                'kodeBarang' => $nextKodeBarang,
                'isEdit' => false,
            ])
        </div>
    </section>
@endsection
