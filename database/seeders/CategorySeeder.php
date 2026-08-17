<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $ropa = $this->createCategory('Ropa', 'ropa');
        $calzado = $this->createCategory('Calzado', 'calzado');
        $accesorios = $this->createCategory('Accesorios', 'accesorios');

        $this->createCategory('Camisetas', 'camisetas', $ropa->id);
        $this->createCategory('Camisas', 'camisas', $ropa->id);
        $this->createCategory('Polos', 'polos', $ropa->id);
        $this->createCategory('Blusas', 'blusas', $ropa->id);
        $this->createCategory('Sudaderas', 'sudaderas', $ropa->id);
        $this->createCategory('Suéteres', 'sueteres', $ropa->id);
        $this->createCategory('Chaquetas', 'chaquetas', $ropa->id);
        $this->createCategory('Pantalones', 'pantalones', $ropa->id);
        $this->createCategory('Jeans', 'jeans', $ropa->id);
        $this->createCategory('Shorts', 'shorts', $ropa->id);
        $this->createCategory('Vestidos', 'vestidos', $ropa->id);
        $this->createCategory('Faldas', 'faldas', $ropa->id);

        $this->createCategory('Tenis', 'tenis', $calzado->id);
        $this->createCategory('Zapatos', 'zapatos', $calzado->id);
        $this->createCategory('Sandalias', 'sandalias', $calzado->id);
        $this->createCategory('Botas', 'botas', $calzado->id);

        $this->createCategory('Bolsos', 'bolsos', $accesorios->id);
        $this->createCategory('Carteras', 'carteras', $accesorios->id);
        $this->createCategory('Cinturones', 'cinturones', $accesorios->id);
        $this->createCategory(
            'Gorras y sombreros',
            'gorras-y-sombreros',
            $accesorios->id
        );
        $this->createCategory('Gafas', 'gafas', $accesorios->id);
        $this->createCategory('Joyería', 'joyeria', $accesorios->id);
    }

    private function createCategory(
        string $name,
        string $slug,
        ?int $parentId = null
    ): Category {
        return Category::updateOrCreate(
            ['slug' => $slug],
            [
                'name' => $name,
                'parent_id' => $parentId,
            ]
        );
    }
}