@extends('layouts.main')

@section('scripts')
    @vite('resources/js/manifest.js')
    @vite('resources/js/vendor.js')
    @vite('resources/js/app.js')
@endsection

@section('content')
    <noscript>Para ver esta página necesitas activar Javascript en tu navegador</noscript>
    <div id="app"><router-view></router-view></div>
@endsection
