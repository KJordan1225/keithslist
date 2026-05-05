<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Category;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ────────────────────────────────────────────────────────────────
        $admin = User::factory()->create([
            'name'  => 'Admin User',
            'email' => 'admin@homeconnect.com',
            'role'  => 'admin',
        ]);

        // ── Categories ───────────────────────────────────────────────────────────
        $categories = [
            ['name' => 'Plumbing',           'icon' => '🔧'],
            ['name' => 'Electrical',          'icon' => '⚡'],
            ['name' => 'HVAC',               'icon' => '❄️'],
            ['name' => 'Roofing',            'icon' => '🏠'],
            ['name' => 'Landscaping',        'icon' => '🌿'],
            ['name' => 'Painting',           'icon' => '🎨'],
            ['name' => 'Cleaning',           'icon' => '🧹'],
            ['name' => 'Pest Control',       'icon' => '🐛'],
            ['name' => 'Flooring',           'icon' => '🪵'],
            ['name' => 'Windows & Doors',    'icon' => '🪟'],
            ['name' => 'Concrete & Masonry', 'icon' => '🧱'],
            ['name' => 'Moving',             'icon' => '📦'],
        ];

        $createdCategories = collect($categories)->map(fn($c) =>
            Category::create(['name' => $c['name'], 'slug' => Str::slug($c['name']), 'icon' => $c['icon']])
        );

        // ── Sample Providers & Businesses ────────────────────────────────────────
        $sampleBusinesses = [
            ['name' => 'Ace Plumbing & Drain', 'city' => 'Austin', 'state' => 'TX', 'categories' => [0]],
            ['name' => 'Bright Spark Electric', 'city' => 'Dallas', 'state' => 'TX', 'categories' => [1]],
            ['name' => 'CoolBreeze HVAC',       'city' => 'Phoenix', 'state' => 'AZ', 'categories' => [2]],
            ['name' => 'Summit Roofing Co.',    'city' => 'Denver', 'state' => 'CO', 'categories' => [3]],
            ['name' => 'GreenThumb Landscaping','city' => 'Seattle', 'state' => 'WA', 'categories' => [4]],
            ['name' => 'PrimeCoat Painting',   'city' => 'Portland', 'state' => 'OR', 'categories' => [5]],
        ];

        foreach ($sampleBusinesses as $b) {
            $provider = User::factory()->create(['role' => 'provider']);

            $business = Business::create([
                'user_id'          => $provider->id,
                'name'             => $b['name'],
                'slug'             => Str::slug($b['name']),
                'description'      => "We are a professional {$b['name']} serving the {$b['city']} area. With years of experience, we guarantee quality work and customer satisfaction.",
                'city'             => $b['city'],
                'state'            => $b['state'],
                'zip'              => '00000',
                'phone'            => '555-' . rand(100, 999) . '-' . rand(1000, 9999),
                'status'           => 'active',
                'licensed'         => true,
                'insured'          => true,
                'years_in_business'=> rand(3, 20),
                'featured'         => rand(0, 1),
            ]);

            $business->categories()->attach(
                $createdCategories->filter(fn($c, $k) => in_array($k, $b['categories']))->pluck('id')
            );

            // Add 3–8 reviews per business
            $homeowners = User::factory(rand(3, 8))->create(['role' => 'homeowner']);
            foreach ($homeowners as $ho) {
                $rating = rand(3, 5);
                Review::create([
                    'business_id'    => $business->id,
                    'user_id'        => $ho->id,
                    'rating'         => $rating,
                    'body'           => "Great service! The team was professional and completed the job on time. Very satisfied with the results. Would definitely recommend to friends and family.",
                    'status'         => 'approved',
                    'would_hire_again' => $rating >= 4,
                    'verified'       => rand(0, 1),
                ]);
            }
        }
    }
}
