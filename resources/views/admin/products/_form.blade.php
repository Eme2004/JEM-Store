@csrf

@if (isset($product))
    @method('PUT')
@endif

@if ($errors->any())
    <div class="jem-alert jem-alert-error mb-4" role="alert">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-3">
    <label for="name" class="auth-label">
        Nombre
    </label>

    <input id="name" type="text" name="name" class="form-control auth-input"
        value="{{ old('name', $product->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="description" class="auth-label">
        Descripción
    </label>

    <textarea id="description" name="description" rows="4" class="form-control auth-input">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="admin-form-grid mb-3">
    <div>
        <label for="category_id" class="auth-label">
            Categoría
        </label>

        <select id="category_id" name="category_id" class="form-select auth-input" required>
            <option value="">Seleccionar...</option>

            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                    {{ $category->parent?->name }}
                    @if ($category->parent)
                        /
                    @endif
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="audience" class="auth-label">
            Público
        </label>

        <select id="audience" name="audience" class="form-select auth-input" required>
            <option value="hombre" @selected(old('audience', $product->audience ?? '') === 'hombre')>
                Hombre
            </option>

            <option value="mujer" @selected(old('audience', $product->audience ?? '') === 'mujer')>
                Mujer
            </option>

            <option value="unisex" @selected(old('audience', $product->audience ?? '') === 'unisex')>
                Unisex
            </option>
        </select>
    </div>
</div>

<div class="admin-form-grid mb-3">
    <div>
        <label for="price" class="auth-label">
            Precio (₡)
        </label>

        <input id="price" type="number" name="price" min="0" step="1" class="form-control auth-input"
            value="{{ old('price', $product->price ?? '') }}" required>
    </div>

    <div>
        <label for="sale_price" class="auth-label">
            Precio en oferta (₡, opcional)
        </label>

        <input id="sale_price" type="number" name="sale_price" min="0" step="1" class="form-control auth-input"
            value="{{ old('sale_price', $product->sale_price ?? '') }}">
    </div>
</div>

<div class="admin-form-grid mb-3">
    <div>
        <label for="stock" class="auth-label">
            Stock
        </label>

        <input id="stock" type="number" name="stock" min="0" step="1" class="form-control auth-input"
            value="{{ old('stock', $product->stock ?? 0) }}" required>
    </div>

    <div class="d-flex align-items-end">
        <div class="form-check">
            <input id="active" type="checkbox" name="active" class="form-check-input"
                value="1" @checked(old('active', $product->active ?? true))>

            <label for="active" class="form-check-label auth-label mb-0">
                Producto activo (visible en la tienda)
            </label>
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="image" class="auth-label">
        Foto del producto
    </label>

    @if (! empty($product) && $product->image)
        <div class="admin-form-current-image">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
            <span class="account-orders-text mb-0">
                Imagen actual. Subí una nueva para reemplazarla.
            </span>
        </div>
    @endif

    <input id="image" type="file" name="image" accept="image/*" class="form-control auth-input">
</div>

<div class="admin-form-actions">
    <button type="submit" class="btn btn-dark account-button">
        {{ isset($product) ? 'Guardar cambios' : 'Crear producto' }}
    </button>

    <a href="{{ route('admin.products.index') }}" class="jem-outline-button btn">
        Cancelar
    </a>
</div>
