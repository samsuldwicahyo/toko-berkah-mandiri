@extends('layouts.app')

@section('title', 'Profil')
@section('page_title', 'Profil')

@section('content')
    <div class="grid gap-4">
        <section class="app-card p-6">
            @include('profile.partials.update-profile-information-form')
        </section>

        <section class="app-card p-6">
            @include('profile.partials.update-password-form')
        </section>

        <section class="app-card p-6">
            @include('profile.partials.delete-user-form')
        </section>
    </div>
@endsection
