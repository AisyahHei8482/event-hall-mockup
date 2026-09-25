<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            // ACCOMMODATIONS
            [
                'name' => 'Hotel Rooms',
                'type' => 'accommodation',
                'short_description' => 'Comfortable and fully furnished hotel rooms perfect for couples and small families.',
                'description' => 'Our Hotel Rooms offer a cozy, modern stay equipped with essential amenities, offering a serene view of the resort surroundings.',
                'capacity' => 2,
                'price' => 150.00,
                'price_unit' => 'per night',
                'amenities' => ['Air Conditioning', 'En-suite Bathroom', 'TV', 'Free WiFi'],
                'is_featured' => true,
                'sort_order' => 1,
                'cover_image' => 'seed-images/fac_hotel.jpg',
            ],
            [
                'name' => 'Bungalows',
                'type' => 'accommodation',
                'short_description' => 'European concept bungalows ideal for large families and group getaways.',
                'description' => 'Experience a touch of Europe with our spacious bungalows. Perfect for family gatherings, featuring multiple rooms, a living area, and private surroundings.',
                'capacity' => 8,
                'price' => 450.00,
                'price_unit' => 'per night',
                'amenities' => ['Multiple Bedrooms', 'Living Room', 'Kitchenette', 'Private Parking'],
                'is_featured' => true,
                'sort_order' => 2,
                'cover_image' => 'seed-images/fac_bungalow.jpg',
            ],
            [
                'name' => 'Trainer Rooms',
                'type' => 'accommodation',
                'short_description' => 'Dedicated rooms for event organizers, trainers, and facilitators.',
                'description' => 'Conveniently located near event halls, our Trainer Rooms are designed for professionals managing events, ensuring a comfortable rest between sessions.',
                'capacity' => 2,
                'price' => 120.00,
                'price_unit' => 'per night',
                'amenities' => ['Work Desk', 'Air Conditioning', 'En-suite Bathroom', 'Free WiFi'],
                'is_featured' => false,
                'sort_order' => 3,
                'cover_image' => 'seed-images/fac_trainer.jpg',
            ],
            [
                'name' => 'Dormitories',
                'type' => 'accommodation',
                'short_description' => 'Spacious dormitories built for school camps, team buildings, and large groups.',
                'description' => 'Our dormitories provide comfortable bunk beds and shared bathroom facilities, perfect for large group stays such as team-building retreats or student camps.',
                'capacity' => 20,
                'price' => 35.00,
                'price_unit' => 'per person/night',
                'amenities' => ['Bunk Beds', 'Shared Bathrooms', 'Air Conditioning', 'Lockers'],
                'is_featured' => false,
                'sort_order' => 4,
                'cover_image' => 'seed-images/fac_dorm.jpg',
            ],
            [
                'name' => 'Dallas Suites Hostel',
                'type' => 'accommodation',
                'short_description' => 'Premium hostel experience for budget-conscious groups.',
                'description' => 'Dallas Suites Hostel offers an upgraded shared living space with added comfort, ideal for corporate groups or large families looking for an affordable yet premium stay.',
                'capacity' => 10,
                'price' => 50.00,
                'price_unit' => 'per person/night',
                'amenities' => ['Comfortable Beds', 'Lounge Area', 'Air Conditioning', 'Shared Bathrooms'],
                'is_featured' => true,
                'sort_order' => 5,
                'cover_image' => 'seed-images/fac_hostel.jpg',
            ],
            // EXPERIENCES & FACILITIES
            [
                'name' => 'Soccer Field',
                'type' => 'facility',
                'short_description' => 'A spacious open field for soccer, rugby, netball, camping, and outdoor group activities.',
                'description' => "Our expansive soccer field is the heart of Savanna Hill Resort's outdoor activities. Suitable for soccer, rugby, netball matches, camping setups, and large-scale outdoor events. Surrounded by nature, the field offers a refreshing change from indoor sports facilities.",
                'capacity' => 200,
                'price' => 300.00,
                'price_unit' => 'per hour',
                'amenities' => ['Floodlights', 'Changing Rooms', 'Parking', 'Seating Area'],
                'is_featured' => true,
                'sort_order' => 1,
                'cover_image' => 'seed-images/fac_soccer.jpg',
            ],
            [
                'name' => 'Swimming Pool',
                'type' => 'facility',
                'short_description' => 'A refreshing pool area for guests to relax and cool off amid the resort greenery.',
                'description' => 'Take a dip in our resort swimming pool, surrounded by lush landscaping. Perfect for families and groups looking to unwind after a day of activities.',
                'capacity' => 80,
                'price' => 15.00,
                'price_unit' => 'per person',
                'amenities' => ['Lifeguard on Duty', 'Poolside Seating', 'Changing Rooms', 'Showers'],
                'is_featured' => true,
                'sort_order' => 2,
                'cover_image' => 'seed-images/fac_pool.jpg',
            ],
            [
                'name' => 'Karaoke House',
                'type' => 'facility',
                'short_description' => 'A dedicated entertainment space for group singing sessions and celebrations.',
                'description' => 'Our Karaoke House offers a fun, private space for groups to sing, celebrate, and bond. Equipped with a modern sound system and an extensive song library.',
                'capacity' => 40,
                'price' => 150.00,
                'price_unit' => 'per hour',
                'amenities' => ['Sound System', 'Air-conditioning', 'Extensive Song Library', 'Seating Lounge'],
                'is_featured' => false,
                'sort_order' => 3,
                'cover_image' => 'seed-images/fac_karaoke.jpg',
            ],
            [
                'name' => 'Animal Feeding',
                'type' => 'activity',
                'short_description' => 'An interactive experience where guests can feed and learn about resort animals.',
                'description' => 'Get up close with friendly farm animals in a guided feeding session. A favourite among families and children, this activity teaches guests about animal care in a safe, supervised environment.',
                'capacity' => 30,
                'price' => 25.00,
                'price_unit' => 'per person',
                'amenities' => ['Guided Session', 'Animal Feed Included', 'Safety Briefing'],
                'is_featured' => true,
                'sort_order' => 4,
                'cover_image' => 'seed-images/fac_animal.jpg',
            ],
            [
                'name' => "Makan D'Hutan",
                'type' => 'dining',
                'short_description' => 'Nature dining experience combined with survival skills training in the forest.',
                'description' => "Makan D'Hutan blends dining with adventure — guests learn basic survival and forest-cooking skills before enjoying a meal prepared amid nature. A unique bonding activity for corporate groups and families alike.",
                'capacity' => 50,
                'price' => 65.00,
                'price_unit' => 'per person',
                'amenities' => ['Guided Instructor', 'Meal Included', 'Forest Setting'],
                'is_featured' => true,
                'sort_order' => 5,
                'cover_image' => 'seed-images/fac_makan.jpg',
            ],
            [
                'name' => 'Corporate Event Venues',
                'type' => 'facility',
                'short_description' => 'Flexible indoor and outdoor spaces for corporate retreats, meetings, and team-building.',
                'description' => 'Host your next corporate retreat, seminar, or team-building event at Savanna Hill Resort. Our venues offer flexible layouts, natural surroundings, and full event support.',
                'capacity' => 150,
                'price' => 500.00,
                'price_unit' => 'per day',
                'amenities' => ['Projector & AV', 'WiFi', 'Catering Available', 'Breakout Rooms'],
                'is_featured' => false,
                'sort_order' => 6,
                'cover_image' => 'seed-images/fac_event.jpg',
            ],
        ];

        foreach ($facilities as $facility) {
            Facility::updateOrCreate(
                ['slug' => Str::slug($facility['name'])],
                array_merge($facility, ['slug' => Str::slug($facility['name']), 'is_active' => true])
            );
        }
    }
}
