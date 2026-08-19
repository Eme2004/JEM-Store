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

    <div class="admin-form-image-preview {{ (! empty($product) && $product->image) ? '' : 'admin-form-image-preview-empty' }}"
        id="imagePreviewWrap">
        <img id="imagePreview"
            src="{{ (! empty($product) && $product->image) ? asset('storage/' . $product->image) : '' }}"
            alt="{{ $product->name ?? 'Vista previa' }}"
            style="{{ (! empty($product) && $product->image) ? '' : 'display:none;' }}">

        <span class="account-orders-text mb-0" id="imagePreviewEmptyText"
            style="{{ (! empty($product) && $product->image) ? 'display:none;' : '' }}">
            Sin foto todavía.
        </span>
    </div>

    <input id="image" type="file" name="image" accept="image/jpeg,image/jpg,image/png,image/webp"
        class="form-control auth-input mt-2">

    <span class="account-orders-text d-block mt-1">
        JPG, PNG o WEBP, máximo 4&nbsp;MB.
    </span>

    @if (! empty($product) && $product->image)
        <div class="form-check mt-2">
            <input id="remove_image" type="checkbox" name="remove_image" value="1" class="form-check-input">

            <label for="remove_image" class="form-check-label auth-label mb-0">
                Quitar la foto actual (sin subir una nueva)
            </label>
        </div>
    @endif
</div>

@once
    @push('scripts')
        <script>
            document.getElementById('image')?.addEventListener('change', function (event) {
                const file = event.target.files[0];
                const img = document.getElementById('imagePreview');
                const emptyText = document.getElementById('imagePreviewEmptyText');
                const removeCheckbox = document.getElementById('remove_image');

                if (! file) {
                    return;
                }

                if (removeCheckbox) {
                    removeCheckbox.checked = false;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    img.src = e.target.result;
                    img.style.display = '';
                    if (emptyText) {
                        emptyText.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            });

            document.getElementById('remove_image')?.addEventListener('change', function (event) {
                const img = document.getElementById('imagePreview');
                const emptyText = document.getElementById('imagePreviewEmptyText');

                if (event.target.checked) {
                    img.style.display = 'none';
                    if (emptyText) {
                        emptyText.style.display = '';
                    }
                } else {
                    img.style.display = '';
                    if (emptyText) {
                        emptyText.style.display = 'none';
                    }
                }
            });
        </script>
    @endpush
@endonce

<div class="admin-form-actions">
    <button type="submit" class="btn btn-dark account-button">
        {{ isset($product) ? 'Guardar cambios' : 'Crear producto' }}
    </button>

    <a href="{{ route('admin.products.index') }}" class="jem-outline-button btn">
        Cancelar
    </a>
</div>
