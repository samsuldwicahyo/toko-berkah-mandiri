@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('page_title', 'Edit Kategori')

@section('content')
    <section class="app-card p-6">
        <div class="max-w-3xl space-y-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Edit Kategori</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Perbarui nama kategori tanpa mengubah struktur modul inventory lainnya.
                </p>
            </div>

            @include('kategori.partials.form', [
                'action' => route('kategori.update', $kategori),
                'buttonLabel' => 'Perbarui Kategori',
                'category' => $kategori,
                'isEdit' => true,
            ])
        </div>
    </section>
@endsection
