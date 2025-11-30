<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan direktori products ada
        if (!Storage::disk('public')->exists('products')) {
            Storage::disk('public')->makeDirectory('products');
        }

        $products = [
            // T-Shirts
            [
                'category' => 'T-Shirt',
                'name' => 'Classic Cotton T-Shirt White',
                'description' => 'T-shirt katun premium dengan kualitas terbaik. Nyaman dipakai untuk aktivitas sehari-hari.',
                'details' => "Bahan: 100% Cotton Combed 30s\nUkuran: S, M, L, XL, XXL\nPerawatan: Cuci dengan air dingin\nModel: Unisex",
                'original_price' => 150000,
                'selling_price' => 99000,
                'weight' => 200,
                'tokopedia_url' => 'https://tokopedia.com/mhr-clothing/tshirt-white',
                'shopee_url' => 'https://shopee.co.id/mhr-clothing/tshirt-white',
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&h=1000&fit=crop',
                    'https://images.unsplash.com/photo-1622445275463-afa2ab738c34?w=800&h=1000&fit=crop',
                ]
            ],
            [
                'category' => 'T-Shirt',
                'name' => 'Oversized T-Shirt Black',
                'description' => 'T-shirt oversized dengan model trendy dan nyaman. Perfect untuk streetwear style.',
                'details' => "Bahan: Cotton Combed 24s\nUkuran: M, L, XL\nModel: Oversized\nWarna: Black",
                'original_price' => 180000,
                'selling_price' => 129000,
                'weight' => 250,
                'shopee_url' => 'https://shopee.co.id/mhr-clothing/oversized-black',
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=800&h=1000&fit=crop',
                    'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=800&h=1000&fit=crop',
                ]
            ],
            [
                'category' => 'T-Shirt',
                'name' => 'Striped T-Shirt Navy',
                'description' => 'T-shirt garis-garis dengan desain klasik yang timeless.',
                'details' => "Bahan: Cotton Combed 30s\nUkuran: S, M, L, XL\nPattern: Striped\nWarna: Navy & White",
                'original_price' => 160000,
                'selling_price' => 119000,
                'weight' => 220,
                'tokopedia_url' => 'https://tokopedia.com/mhr-clothing/striped-navy',
                'images' => [
                    'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=800&h=1000&fit=crop',
                ]
            ],

            // Kemeja
            [
                'category' => 'Kemeja',
                'name' => 'Formal White Shirt',
                'description' => 'Kemeja putih formal untuk acara resmi dan kerja. Material premium dengan jahitan rapi.',
                'details' => "Bahan: Cotton Oxford\nUkuran: S, M, L, XL, XXL\nKerah: Regular\nLengan: Panjang",
                'original_price' => 280000,
                'selling_price' => 199000,
                'weight' => 300,
                'tokopedia_url' => 'https://tokopedia.com/mhr-clothing/formal-white',
                'tiktok_url' => 'https://tiktok.com/@mhr-clothing/formal-white',
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=800&h=1000&fit=crop',
                    'https://images.unsplash.com/photo-1603252109303-2751441dd157?w=800&h=1000&fit=crop',
                ]
            ],
            [
                'category' => 'Kemeja',
                'name' => 'Flannel Shirt Checkered',
                'description' => 'Kemeja flannel dengan motif kotak-kotak. Cocok untuk gaya casual.',
                'details' => "Bahan: Flannel Cotton\nUkuran: M, L, XL\nPattern: Checkered\nLengan: Panjang",
                'original_price' => 250000,
                'selling_price' => 179000,
                'weight' => 350,
                'shopee_url' => 'https://shopee.co.id/mhr-clothing/flannel-checkered',
                'images' => [
                    'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&h=1000&fit=crop',
                ]
            ],

            // Jaket
            [
                'category' => 'Jaket',
                'name' => 'Denim Jacket Classic',
                'description' => 'Jaket denim klasik yang tidak lekang oleh waktu. Must have item untuk fashion enthusiast.',
                'details' => "Bahan: Denim Premium\nUkuran: S, M, L, XL\nWarna: Light Blue\nModel: Regular Fit",
                'original_price' => 450000,
                'selling_price' => 349000,
                'weight' => 600,
                'tokopedia_url' => 'https://tokopedia.com/mhr-clothing/denim-classic',
                'shopee_url' => 'https://shopee.co.id/mhr-clothing/denim-classic',
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800&h=1000&fit=crop',
                    'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=800&h=1000&fit=crop',
                ]
            ],
            [
                'category' => 'Jaket',
                'name' => 'Bomber Jacket Black',
                'description' => 'Jaket bomber dengan desain modern dan stylish. Perfect untuk cuaca dingin.',
                'details' => "Bahan: Polyester Premium\nUkuran: M, L, XL, XXL\nWarna: Black\nFitur: Water Resistant",
                'original_price' => 380000,
                'selling_price' => 299000,
                'weight' => 550,
                'tiktok_url' => 'https://tiktok.com/@mhr-clothing/bomber-black',
                'images' => [
                    'https://images.unsplash.com/photo-1544022613-e87ca75a784a?w=800&h=1000&fit=crop',
                ]
            ],

            // Celana
            [
                'category' => 'Celana',
                'name' => 'Chino Pants Khaki',
                'description' => 'Celana chino dengan warna khaki yang versatile. Cocok untuk berbagai acara.',
                'details' => "Bahan: Cotton Twill\nUkuran: 28, 30, 32, 34, 36\nWarna: Khaki\nModel: Slim Fit",
                'original_price' => 320000,
                'selling_price' => 249000,
                'weight' => 400,
                'tokopedia_url' => 'https://tokopedia.com/mhr-clothing/chino-khaki',
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=800&h=1000&fit=crop',
                ]
            ],
            [
                'category' => 'Celana',
                'name' => 'Cargo Pants Olive',
                'description' => 'Celana cargo dengan banyak kantong. Praktis dan stylish untuk aktivitas outdoor.',
                'details' => "Bahan: Canvas Cotton\nUkuran: 28, 30, 32, 34\nWarna: Olive Green\nKantong: 6 pockets",
                'original_price' => 350000,
                'selling_price' => 269000,
                'weight' => 450,
                'shopee_url' => 'https://shopee.co.id/mhr-clothing/cargo-olive',
                'images' => [
                    'https://images.unsplash.com/photo-1624378515195-6bbdb73dff1a?w=800&h=1000&fit=crop',
                ]
            ],

            // Dress
            [
                'category' => 'Dress',
                'name' => 'Floral Maxi Dress',
                'description' => 'Dress maxi dengan motif floral yang cantik. Perfect untuk acara semi formal.',
                'details' => "Bahan: Rayon Premium\nUkuran: S, M, L, XL\nPanjang: Maxi (140cm)\nPattern: Floral",
                'original_price' => 380000,
                'selling_price' => 289000,
                'weight' => 350,
                'tokopedia_url' => 'https://tokopedia.com/mhr-clothing/floral-maxi',
                'shopee_url' => 'https://shopee.co.id/mhr-clothing/floral-maxi',
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=800&h=1000&fit=crop',
                    'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=800&h=1000&fit=crop',
                ]
            ],
            [
                'category' => 'Dress',
                'name' => 'Casual Mini Dress',
                'description' => 'Dress mini casual untuk hangout dan daily wear. Nyaman dan cute.',
                'details' => "Bahan: Cotton Blend\nUkuran: S, M, L\nPanjang: Mini (90cm)\nWarna: White, Black, Pink",
                'original_price' => 220000,
                'selling_price' => 169000,
                'weight' => 250,
                'tiktok_url' => 'https://tiktok.com/@mhr-clothing/casual-mini',
                'images' => [
                    'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=800&h=1000&fit=crop',
                ]
            ],

            // Aksesoris
            [
                'category' => 'Aksesoris',
                'name' => 'Bucket Hat Black',
                'description' => 'Topi bucket dengan desain simple dan trendy. Melindungi dari sinar matahari.',
                'details' => "Bahan: Canvas Cotton\nUkuran: All Size\nWarna: Black\nTipe: Bucket Hat",
                'original_price' => 120000,
                'selling_price' => 79000,
                'weight' => 150,
                'shopee_url' => 'https://shopee.co.id/mhr-clothing/bucket-black',
                'images' => [
                    'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=800&h=1000&fit=crop',
                ]
            ],
            [
                'category' => 'Aksesoris',
                'name' => 'Canvas Tote Bag',
                'description' => 'Tas tote bag dari canvas yang kuat dan stylish. Cocok untuk daily use.',
                'details' => "Bahan: Canvas Premium\nUkuran: 40x35x10 cm\nWarna: Natural, Black\nTali: Adjustable",
                'original_price' => 180000,
                'selling_price' => 129000,
                'weight' => 300,
                'tokopedia_url' => 'https://tokopedia.com/mhr-clothing/canvas-tote',
                'images' => [
                    'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=800&h=1000&fit=crop',
                ]
            ],
        ];

        foreach ($products as $productData) {
            $this->command->info("Creating product: {$productData['name']}");

            // Cari kategori
            $category = Category::where('slug', Str::slug($productData['category']))->first();

            if (!$category) {
                $this->command->warn("Category not found: {$productData['category']}");
                continue;
            }

            // Buat product
            $product = Product::create([
                'category_id' => $category->id,
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'description' => $productData['description'],
                'details' => $productData['details'],
                'tokopedia_url' => $productData['tokopedia_url'] ?? null,
                'shopee_url' => $productData['shopee_url'] ?? null,
                'tiktok_url' => $productData['tiktok_url'] ?? null,
                'original_price' => $productData['original_price'],
                'selling_price' => $productData['selling_price'],
                'weight' => $productData['weight'],
                'rating' => rand(40, 50) / 10, // Random rating 4.0 - 5.0
                'total_reviews' => rand(10, 100),
                'is_active' => true,
                'is_featured' => $productData['is_featured'] ?? false,
                'sort_order' => 0,
            ]);

            // Download dan simpan gambar
            foreach ($productData['images'] as $index => $imageUrl) {
                try {
                    $this->command->info("  Downloading image from: {$imageUrl}");

                    // Download gambar dari Unsplash
                    $response = Http::timeout(30)->get($imageUrl);

                    if ($response->successful()) {
                        // Generate nama file unik
                        $filename = 'product_' . $product->id . '_' . ($index + 1) . '_' . time() . '.jpg';
                        $path = 'products/' . $filename;

                        // Simpan gambar ke storage
                        Storage::disk('public')->put($path, $response->body());

                        // Buat record ProductImage
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $path,
                            'is_primary' => $index === 0, // Gambar pertama jadi primary
                            'sort_order' => $index,
                        ]);

                        $this->command->info("  ✓ Image saved: {$path}");
                    } else {
                        $this->command->error("  ✗ Failed to download image: {$imageUrl}");
                    }
                } catch (\Exception $e) {
                    $this->command->error("  ✗ Error downloading image: " . $e->getMessage());
                }
            }

            $this->command->info("✓ Product created successfully\n");
        }

        $this->command->info('All products seeded successfully!');
    }
}
