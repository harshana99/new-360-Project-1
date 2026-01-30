<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\User;
use App\Models\PropertyImage;
use Carbon\Carbon;

class PropertySeeder extends Seeder
{
    public function run()
    {
        // Find the Demo Owner
        $owner = User::where('email', 'owner@360winestate.com')->first();
        
        if (!$owner) {
            $this->command->warn("Demo Owner not found! Run OwnerMetricsSeeder first.");
            return;
        }

        $properties = [
            [
                'title' => 'Luxury 4-Bedroom Villa',
                'description' => 'A stunning masterpiece located in the heart of Lekki Phase 1. Features a swimming pool, smart home automation, and 24/7 security. Perfect for high-yield rental income.',
                'location' => 'Lekki Phase 1, Lagos',
                'address' => '12 Admiralty Way, Lekki',
                'property_type' => 'residential',
                'status' => 'active',
                'price' => 150000000,
                'minimum_investment' => 5000000,
                'expected_return_percentage' => 18.5,
                'completion_date' => Carbon::now()->addMonths(6),
                'lease_duration_months' => 24,
                'image' => 'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=800&q=80'
            ],
            [
                'title' => 'Victoria Island Commercial Hub',
                'description' => 'Premium office space in a high-rise building overlooking the Atlantic. Ideal for corporate headquarters. High appreciation potential.',
                'location' => 'Victoria Island, Lagos',
                'address' => '1014 Saka Tinubu St',
                'property_type' => 'commercial',
                'status' => 'active',
                'price' => 450000000,
                'minimum_investment' => 10000000,
                'expected_return_percentage' => 22.0,
                'completion_date' => Carbon::now()->addMonths(12),
                'lease_duration_months' => 60,
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80'
            ],
            [
                'title' => 'Mainland Mixed-Use Complex',
                'description' => 'Awaiting approval. A combination of retail shops and apartments in Ikeja GRA. Close to the airport.',
                'location' => 'Ikeja GRA, Lagos',
                'address' => '45 Isaac John St',
                'property_type' => 'mixed_use',
                'status' => 'pending', // Pending Admin Review
                'price' => 200000000,
                'minimum_investment' => 2500000,
                'expected_return_percentage' => 15.0,
                'completion_date' => Carbon::now()->addMonths(18),
                'lease_duration_months' => 36,
                'image' => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=800&q=80'
            ]
        ];

        foreach ($properties as $propData) {
            $image = $propData['image'];
            unset($propData['image']);

            $propData['user_id'] = $owner->id;
            
            // Allow duplicate titles for seeding, or updateOrCreate
            $property = Property::updateOrCreate(
                ['title' => $propData['title']], 
                $propData
            );

            // Add Image
            PropertyImage::firstOrCreate(
                ['property_id' => $property->id],
                [
                    'image_url' => $image,
                    'is_featured' => true,
                    'image_alt_text' => $property->title
                ]
            );
        }
    }
}
