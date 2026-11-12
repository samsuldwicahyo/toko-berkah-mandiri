@extends('layouts.app')

@section('title', 'Edit Barang')
@section('page_title', 'Edit Barang')

@section('content')
    <section class="app-card p-6">
        <div class="max-w-5xl space-y-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Edit Barang</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Perbarui detail barang, harga, stok, dan gambar sesuai kondisi inventory terbaru.
                </p>
            </div>

            @include('barang.partials.form', [
                'action' => route('barang.update', $barang),
                'buttonLabel' => 'Perbarui Barang',
                'barang' => $barang,
                'categories' => $categories,
                'kodeBarang' => $barang->kode_barang,
                'isEdit' => true,
            ])
        </div>
    </section>
@endsection
