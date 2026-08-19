@extends('layouts.admin')

@section('content')

    <div class="admin-page-header">
        <div>
            <p class="account-kicker mb-2">
                Panel de administración
            </p>

            <h1 class="admin-page-title jem-editorial-title mb-0">
                Nuevo producto
            </h1>
        </div>
    </div>

    <div class="admin-form-panel">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @include('admin.products._form')
        </form>
    </div>

@endsection
