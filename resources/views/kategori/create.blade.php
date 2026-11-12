@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('page_title', 'Tambah Kategori')

@section('content')
    <section class="app-card p-6">
        <div class="max-w-3xl space-y-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Tambah Kategori Baru</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Form ini dapat dipakai admin maupun staff untuk menambahkan kategori inventory baru.
                </p>
            </div>

            @include('kategori.partials.form', [
                'action' => route('kategori.store'),
                'buttonLabel' => 'Simpan Kategori',
                'category' => null,
                'isEdit' => false,
            ])
        </div>
    </section>
@endsection
