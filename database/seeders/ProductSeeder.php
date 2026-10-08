<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryNames = [
            'electronics' => 'Elektronik',
            'sport' => 'Olahraga',
            'furniture' => 'Mebel',
            'toys' => 'Mainan',
        ];

        $products = [
            // --- ELECTRONICS ---
            [
                'name'     => 'Laptop Asus Vivobook 14',
                'price'    => 8500000,
                'category' => 'electronics',
                'image'    => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=500&auto=format&fit=crop',
            ],
            [
                'name'     => 'Smartphone Samsung Galaxy A54',
                'price'    => 5999000,
                'category' => 'electronics',
                'image'    => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&auto=format&fit=crop',
            ],
            [
                'name'     => 'Wireless Earphones TWS',
                'price'    => 350000,
                'category' => 'electronics',
                'image'    => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=500&auto=format&fit=crop',
            ],

            // --- SPORT GOODS ---
            [
                'name'     => 'Sepatu Lari Nike Revolution',
                'price'    => 899000,
                'category' => 'sport',
                'image'    => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&auto=format&fit=crop',
            ],
            [
                'name'     => 'Dumbbell Set 10kg',
                'price'    => 450000,
                'category' => 'sport',
                'image'    => 'https://images.unsplash.com/photo-1584735935682-2f2b69dff9d2?w=500&auto=format&fit=crop',
            ],
            [
                'name'     => 'Matras Yoga Anti-Slip',
                'price'    => 150000,
                'category' => 'sport',
                'image'    => 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=500&auto=format&fit=crop',
            ],

            // --- FURNITURE ---
            [
                'name'     => 'Kursi Kerja Ergonomis',
                'price'    => 1250000,
                'category' => 'furniture',
                'image'    => 'https://images.unsplash.com/photo-1572521165329-b197f9ea3da6?w=500&auto=format&fit=crop',
            ],
            [
                'name'     => 'Meja Belajar Minimalis',
                'price'    => 750000,
                'category' => 'furniture',
                'image'    => 'https://images.unsplash.com/photo-1518455027359-f3f8164ba6bd?w=500&auto=format&fit=crop',
            ],
            [
                'name'     => 'Lampu Tidur Minimalis',
                'price'    => 185000,
                'category' => 'furniture',
                'image'    => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=500&auto=format&fit=crop',
            ],

            // --- BABY TOYS ---
            [
                'name'     => 'Mobil Remot Kontrol Offroad',
                'price'    => 275000,
                'category' => 'toys',
                'image'    => 'https://images.unsplash.com/photo-1594787318286-3d835c1d207f?w=500&auto=format&fit=crop',
            ],
            [
                'name'     => 'Lego Classic Bricks Box',
                'price'    => 499000,
                'category' => 'toys',
                'image'    => 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=500&auto=format&fit=crop',
            ],
            [
                'name'     => 'Boneka Teddy Bear Jumbo',
                'price'    => 195000,
                'category' => 'toys',
                'image'    => 'https://images.unsplash.com/photo-1559454403-b8fb88521f11?w=500&auto=format&fit=crop',
            ],
        ];

        foreach ($products as $product) {
            $category = Category::firstOrCreate(['name' => $categoryNames[$product['category']]]);
            $product['category_id'] = $category->id;
            $product['category'] = $category->name;

            Product::updateOrCreate(['name' => $product['name']], $product);
        }
    }
}