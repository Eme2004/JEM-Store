@extends('layouts.admin')

@section('content')

    <div class="admin-page-header">
        <div>
            <p class="account-kicker mb-2">
                Panel de administración
            </p>

            <h1 class="admin-page-title jem-editorial-title mb-2">
                Productos
            </h1>

            <p class="admin-page-subtitle mb-0">
                {{ $products->total() }} {{ $products->total() === 1 ? 'producto' : 'productos' }} en el catálogo.
            </p>
        </div>

        <a href="{{ route('admin.products.create') }}" class="btn btn-dark account-button">
            + Nuevo producto
        </a>
    </div>

    <div class="admin-table-wrap">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th></th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            <div class="admin-table-thumb">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                @else
                                    <span>JEM</span>
                                @endif
                            </div>
                        </td>

                        <td>
                            {{ $product->name }}
                        </td>

                        <td>
                            {{ $product->category->name }}
                        </td>

                        <td>
                            ₡{{ number_format($product->price, 0, ',', '.') }}
                        </td>

                        <td>
                            {{ $product->stock }}
                        </td>

                        <td>
                            <span class="admin-badge {{ $product->active ? 'admin-badge-active' : 'admin-badge-inactive' }}">
                                {{ $product->active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>

                        <td>
                            <div class="admin-table-actions">
                                <a href="{{ route('admin.products.edit', $product) }}">
                                    Editar
                                </a>

                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                    onsubmit="return confirm('¿Eliminar {{ addslashes($product->name) }}? Esta acción no se puede deshacer.');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            Todavía no hay productos. Creá el primero con "+ Nuevo producto".
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($products->hasPages())
        <div class="catalog-pagination">
            {{ $products->links() }}
        </div>
    @endif

@endsection
