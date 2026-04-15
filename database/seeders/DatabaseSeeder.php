<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Drink;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('order_items')->delete();
        Order::query()->delete();
        Drink::query()->delete();
        Category::query()->delete();
        User::query()->delete();

        $admin = User::factory()->create([
            'name' => 'Admin Tea Master',
            'email' => 'admin@milktea.test',
            'role' => 'admin',
            'phone' => '09170000001',
            'password' => 'password',
        ]);

        $staff = User::factory()->create([
            'name' => 'Store Staff',
            'email' => 'staff@milktea.test',
            'role' => 'staff',
            'phone' => '09170000002',
            'password' => 'password',
        ]);

        $customer = User::factory()->create([
            'name' => 'Tea Lover',
            'email' => 'customer@milktea.test',
            'role' => 'customer',
            'phone' => '09170000003',
            'password' => 'password',
        ]);

        $classic = Category::create([
            'name' => 'Classic Milk Tea',
            'slug' => 'classic-milk-tea',
            'description' => 'Best-selling milk tea favorites for everyday cravings.',
        ]);

        $fruit = Category::create([
            'name' => 'Fruit Tea',
            'slug' => 'fruit-tea',
            'description' => 'Bright and refreshing fruit-forward drinks.',
        ]);

        $specialty = Category::create([
            'name' => 'Specialty Series',
            'slug' => 'specialty-series',
            'description' => 'Premium drinks with richer flavors and toppings.',
        ]);

        $drinks = collect([
            [$classic, 'Wintermelon Milk Tea', 'Caramel-sweet and smooth.', 95, 40, true],
            [$classic, 'Okinawa Milk Tea', 'Brown sugar depth with creamy tea.', 105, 35, true],
            [$fruit, 'Lychee Green Tea', 'Light and fragrant with citrus lift.', 90, 28, false],
            [$fruit, 'Passionfruit Black Tea', 'Tangy and vibrant iced tea.', 92, 24, false],
            [$specialty, 'Brown Sugar Boba', 'Rich muscovado swirl with pearls.', 120, 30, true],
            [$specialty, 'Matcha Cheesecake', 'Earthy matcha topped with cream cheese.', 135, 18, false],
        ])->map(function (array $drink, int $index) {
            $imagePaths = [
                'pics/WinterMelon-Milk-Tea.jpg',   // <- corrected to match your file
                'drinks/okinawa-milk-tea.jpg',
                'drinks/lychee-green-tea.jpg',
                'drinks/passionfruit-black-tea.jpg',
                'drinks/brown-sugar-boba.jpg',
                'drinks/matcha-cheesecake.jpg',
            ];

            return Drink::create([
                'category_id' => $drink[0]->id,
                'name' => $drink[1],
                'slug' => Str::slug($drink[1]) . '-' . strtolower(Str::random(4)),
                'description' => $drink[2],
                'price' => $drink[3],
                'stock' => $drink[4],
                'is_featured' => $drink[5],
                'is_available' => true,
                'image_path' => $imagePaths[$index] ?? null,
            ]);
        });

        $sampleOrder = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'MT-' . now()->format('Ymd') . '-0001',
            'qr_token' => (string) Str::uuid(),
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => $customer->phone,
            'status' => 'completed',
            'subtotal' => 240,
            'total' => 240,
            'ordered_at' => now()->subDay(),
        ]);

        $sampleOrder->items()->create([
            'drink_id' => $drinks[4]->id,
            'drink_name' => $drinks[4]->name,
            'size' => 'Large',
            'sugar_level' => '75%',
            'ice_level' => 'Less Ice',
            'quantity' => 2,
            'unit_price' => 120,
            'line_total' => 240,
        ]);
    }
}
