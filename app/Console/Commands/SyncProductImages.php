<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SyncProductImages extends Command
{
    protected $signature = 'products:sync-images';

    protected $description = 'Actualiza únicamente la columna image de los productos existentes buscando storage/app/public/products/{slug}.{webp,jpg,jpeg,png}, sin tocar stock, precios ni otros datos';

    private const EXTENSIONS = ['webp', 'jpg', 'jpeg', 'png'];

    public function handle(): int
    {
        Product::query()->orderBy('slug')->each(function (Product $product): void {
            $image = collect(self::EXTENSIONS)
                ->map(fn (string $extension) => "products/{$product->slug}.{$extension}")
                ->first(fn (string $path) => Storage::disk('public')->exists($path));

            if ($image === null) {
                $this->line("[SKIP] {$product->slug} -> imagen no encontrada");

                return;
            }

            if ($product->image !== $image) {
                $product->forceFill(['image' => $image])->save();
            }

            $this->line("[OK] {$product->slug} -> {$image}");
        });

        return self::SUCCESS;
    }
}
