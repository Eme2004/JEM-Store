<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder

{
    public function run(): void
    {
        $products = [
            [
                'category' => 'camisetas',
                'name' => 'JEM Core Tee',
                'slug' => 'jem-core-tee',
                'description' => 'Camiseta esencial de corte limpio y estilo versátil.',
                'price' => 18900,
                'sale_price' => null,
                'stock' => 30,
                'audience' => 'unisex',
            ],
            [
                'category' => 'camisas',
                'name' => 'JEM Oxford Structure',
                'slug' => 'jem-oxford-structure',
                'description' => 'Camisa Oxford de líneas clásicas con acabado contemporáneo.',
                'price' => 32900,
                'sale_price' => null,
                'stock' => 18,
                'audience' => 'hombre',
            ],
            [
                'category' => 'camisas',
                'name' => 'JEM Linen Air',
                'slug' => 'jem-linen-air',
                'description' => 'Camisa ligera con apariencia natural y silueta relajada.',
                'price' => 34900,
                'sale_price' => 28900,
                'stock' => 15,
                'audience' => 'mujer',
            ],
            [
                'category' => 'polos',
                'name' => 'JEM Signature Polo',
                'slug' => 'jem-signature-polo',
                'description' => 'Polo de estilo refinado para un look casual y elegante.',
                'price' => 28900,
                'sale_price' => null,
                'stock' => 22,
                'audience' => 'hombre',

            ],
            [
                'category' => 'blusas',
                'name' => 'JEM Élan Blouse',
                'slug' => 'jem-elan-blouse',
                'description' => 'Blusa fluida de diseño minimalista y acabado elegante.',
                'price' => 29900,
                'sale_price' => null,
                'stock' => 14,
                'audience' => 'mujer',
            ],
            [
                'category' => 'sudaderas',
                'name' => 'JEM Studio Hoodie',
                'slug' => 'jem-studio-hoodie',
                'description' => 'Sudadera de corte relajado diseñada para uso diario.',
                'price' => 38900,
                'sale_price' => 32900,
                'stock' => 20,
                'audience' => 'unisex',
            ],
            [
                'category' => 'sueteres',
                'name' => 'JEM Soft Knit',
                'slug' => 'jem-soft-knit',
                'description' => 'Suéter tejido de textura suave y estética atemporal.',
                'price' => 41900,
                'sale_price' => null,
                'stock' => 12,
                'audience' => 'unisex',
            ],
            [
                'category' => 'chaquetas',
                'name' => 'JEM Transit Jacket',
                'slug' => 'jem-transit-jacket',
                'description' => 'Chaqueta urbana estructurada para combinar funcionalidad y estilo.',
                'price' => 64900,
                'sale_price' => null,
                'stock' => 10,
                'audience' => 'hombre',
            ],
            [
                'category' => 'chaquetas',
                'name' => 'JEM Atelier Jacket',
                'slug' => 'jem-atelier-jacket',
                'description' => 'Chaqueta femenina de líneas definidas y acabado sofisticado.',
                'price' => 67900,
                'sale_price' => 56900,
                'stock' => 8,
                'audience' => 'mujer',
            ],
            [
                'category' => 'pantalones',
                'name' => 'JEM Tailored Trousers',
                'slug' => 'jem-tailored-trousers',
                'description' => 'Pantalón de corte estructurado para una silueta limpia.',
                'price' => 42900,
                'sale_price' => null,
                'stock' => 16,
                'audience' => 'mujer',
            ],
            [
                'category' => 'jeans',
                'name' => 'JEM Straight Denim',
                'slug' => 'jem-straight-denim',
                'description' => 'Jeans de corte recto con diseño clásico y versátil.',
                'price' => 44900,
                'sale_price' => null,
                'stock' => 24,
                'audience' => 'unisex',
            ],
            [
                'category' => 'shorts',
                'name' => 'JEM Relaxed Short',
                'slug' => 'jem-relaxed-short',
                'description' => 'Short casual de corte relajado para días cálidos.',
                'price' => 24900,
                'sale_price' => 19900,
                'stock' => 17,
                'audience' => 'unisex',
            ],
            [
                'category' => 'vestidos',
                'name' => 'JEM Studio Midi',
                'slug' => 'jem-studio-midi',
                'description' => 'Vestido midi de silueta moderna y diseño elegante.',
                'price' => 52900,
                'sale_price' => null,
                'stock' => 11,
                'audience' => 'mujer',
            ],
            [
                'category' => 'faldas',
                'name' => 'JEM Pleated Motion',
                'slug' => 'jem-pleated-motion',
                'description' => 'Falda plisada con movimiento ligero y acabado contemporáneo.',
                'price' => 36900,
                'sale_price' => null,
                'stock' => 13,
                'audience' => 'mujer',
            ],
            [
                'category' => 'tenis',
                'name' => 'JEM Nova Sneakers',
                'slug' => 'jem-nova-sneakers',
                'description' => 'Tenis minimalistas diseñados para combinar con cualquier estilo.',
                'price' => 57900,
                'sale_price' => null,
                'stock' => 19,
                'audience' => 'unisex',
            ],
            [
                'category' => 'zapatos',
                'name' => 'JEM Derby Noir',
                'slug' => 'jem-derby-noir',
                'description' => 'Zapato clásico reinterpretado con una estética moderna.',
                'price' => 62900,
                'sale_price' => 52900,
                'stock' => 9,
                'audience' => 'hombre',
            ],
            [
                'category' => 'sandalias',
                'name' => 'JEM Minimal Sandal',
                'slug' => 'jem-minimal-sandal',
                'description' => 'Sandalia de diseño limpio y perfil contemporáneo.',
                'price' => 35900,
                'sale_price' => null,
                'stock' => 12,
                'audience' => 'mujer',
            ],
            [
                'category' => 'botas',
                'name' => 'JEM Urban Boot',
                'slug' => 'jem-urban-boot',
                'description' => 'Bota urbana versátil con líneas sobrias.',
                'price' => 69900,
                'sale_price' => null,
                'stock' => 8,
                'audience' => 'unisex',
            ],
            [
                'category' => 'bolsos',
                'name' => 'JEM Horizon Bag',
                'slug' => 'jem-horizon-bag',
                'description' => 'Bolso estructurado de estética limpia para uso cotidiano.',
                'price' => 48900,
                'sale_price' => null,
                'stock' => 10,
                'audience' => 'unisex',
            ],
            [
                'category' => 'cinturones',
                'name' => 'JEM Essential Belt',
                'slug' => 'jem-essential-belt',
                'description' => 'Cinturón minimalista diseñado para complementar looks clásicos.',
                'price' => 21900,
                'sale_price' => null,
                'stock' => 25,
                'audience' => 'unisex',
            ],
            [
                'category' => 'gafas',
                'name' => 'JEM Horizon Frames',
                'slug' => 'jem-horizon-frames',
                'description' => 'Gafas de líneas modernas y diseño versátil.',
                'price' => 27900,
                'sale_price' => 23900,
                'stock' => 16,
                'audience' => 'unisex',
            ],
            [
                'category' => 'joyeria',
                'name' => 'JEM Line Pendant',
                'slug' => 'jem-line-pendant',
                'description' => 'Collar minimalista pensado como detalle sutil de cualquier look.',
                'price' => 19900,
                'sale_price' => null,
                'stock' => 18,
                'audience' => 'mujer',
            ],
            [
                'category' => 'chaquetas',
                'name' => 'JEM Meridian Jacket',
                'slug' => 'jem-meridian-jacket',
                'description' => 'Chaqueta masculina estructurada de líneas limpias y estética contemporánea.',
                'price' => 68900,
                'sale_price' => null,
                'stock' => 10,
                'audience' => 'hombre',
            ],
            [
                'category' => 'bolsos',
                'name' => 'JEM Form Bag',
                'slug' => 'jem-form-bag',
                'description' => 'Bolso estructurado de diseño minimalista pensado para acompañar looks contemporáneos.',
                'price' => 52900,
                'sale_price' => null,
                'stock' => 12,
                'audience' => 'unisex',
            ],
        ];

        foreach ($products as $productData) {
            $category = Category::where('slug', $productData['category'])->firstOrFail();

            $image = collect(['png', 'jpg', 'jpeg', 'webp'])
                ->map(fn($extension) => "products/{$productData['slug']}.$extension")
                ->first(fn($path) => Storage::disk('public')->exists($path));

            Product::updateOrCreate(
                ['slug' => $productData['slug']],
                [
                    'category_id' => $category->id,
                    'name' => $productData['name'],
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'sale_price' => $productData['sale_price'],
                    'stock' => $productData['stock'],
                    'audience' => $productData['audience'],
                    'image' => $image,
                    'active' => true,
                ]
            );
        }
    }
}
