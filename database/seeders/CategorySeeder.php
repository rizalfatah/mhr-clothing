<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'T-Shirt',
                'description' => 'Koleksi kaos dengan berbagai desain dan warna',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Kemeja',
                'description' => 'Kemeja formal dan casual untuk berbagai acara',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Jaket',
                'description' => 'Jaket stylish untuk berbagai cuaca',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Celana',
                'description' => 'Celana panjang dan pendek untuk aktivitas sehari-hari',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Dress',
                'description' => 'Dress cantik untuk wanita',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Aksesoris',
                'description' => 'Topi, tas, dan aksesoris fashion lainnya',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => $category['is_active'],
                'sort_order' => $category['sort_order'],
            ]);
        }
    }
}
