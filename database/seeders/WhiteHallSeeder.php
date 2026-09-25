<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Franchise;
use App\Models\EventHall;
use App\Models\HallPricingRule;
use App\Models\Addon;
use App\Models\HallTimeSlot;

class WhiteHallSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Franchise/Location for White Hall Event Space
        $whiteHallFranchise = Franchise::firstOrCreate(
            ['code' => 'WHITEHALL'],
            [
                'name' => 'White Hall Event Space',
                'company_name' => 'White Hall Event Space (Demo)',
                'address' => '1, Jalan Kempas Lama 2/6, 81300 Johor Bahru, Johor, Malaysia',
                'contact_person' => 'Event Manager',
                'phone' => '+60 12-345 6789',
                'email' => 'hello@whitehalleventspace.com',
                'status' => 'active',
                'logo' => 'seed-images/whitehall_logo.jpg',
            ]
        );

        // 2. Create the Event Hall venue
        $mainHall = EventHall::firstOrCreate(
            ['code' => 'WH-MAIN'],
            [
                'franchise_id' => $whiteHallFranchise->id,
                'name' => 'White Hall Main Event Space',
                'hall_type' => 'Banquet Hall',
                'capacity' => 1000,
                'floor_area' => 1200.0,
                'description' => 'A modern, pillarless, all-white event space. Perfect for weddings, engagements, corporate functions, annual dinners, graduations, and private celebrations.',
                'amenities' => [
                    'Air Conditioning', 
                    'Prayer Facilities (Surau)', 
                    'Spacious Parking', 
                    'Complimentary Buggy Service',
                    'Wheelchair Accessible'
                ],
                'facilities' => [
                    'Pillarless Layout',
                    'Stage',
                    'Microphone / AV System',
                    'Basic Lighting',
                ],
                'min_booking_hours' => 4,
                'max_booking_hours' => 14,
                'buffer_before' => 120, // 2 hours setup
                'buffer_after' => 120, // 2 hours teardown
                'is_active' => true,
                'cover_image' => 'seed-images/whitehall_main.jpg',
            ]
        );

        // 3. Create Pricing Rules (DEMO DATA as requested)
        HallPricingRule::firstOrCreate(
            ['event_hall_id' => $mainHall->id, 'rate_type' => 'hourly'],
            [
                'label' => 'Standard Hourly Rate (Demo)',
                'price' => 1200.00,
                'is_active' => true,
                'priority' => 1,
            ]
        );

        HallPricingRule::firstOrCreate(
            ['event_hall_id' => $mainHall->id, 'rate_type' => 'half_day'],
            [
                'label' => 'Half-Day Event Rate (Demo)',
                'price' => 5000.00,
                'is_active' => true,
                'priority' => 2,
            ]
        );

        HallPricingRule::firstOrCreate(
            ['event_hall_id' => $mainHall->id, 'rate_type' => 'full_day'],
            [
                'label' => 'Full-Day Wedding Package (Demo)',
                'price' => 12000.00,
                'is_active' => true,
                'priority' => 3,
            ]
        );

        // 4. Create Addons & Services
        $addons = [
            ['name' => 'Premium Catering (Demo)', 'category' => 'catering', 'price' => 60.00, 'unit' => 'per pax', 'is_quantifiable' => true],
            ['name' => 'Standard Catering (Demo)', 'category' => 'catering', 'price' => 45.00, 'unit' => 'per pax', 'is_quantifiable' => true],
            ['name' => 'Floral Stage Decoration', 'category' => 'decoration', 'price' => 2500.00, 'unit' => 'per event', 'is_quantifiable' => false],
            ['name' => 'Premium PA System & LED Screen', 'category' => 'equipment', 'price' => 1800.00, 'unit' => 'per event', 'is_quantifiable' => false],
            ['name' => 'Event Photography Service', 'category' => 'service', 'price' => 1200.00, 'unit' => 'per event', 'is_quantifiable' => false],
            ['name' => 'Cleaning Fee (Mandatory for Catering)', 'category' => 'service', 'price' => 300.00, 'unit' => 'per event', 'is_quantifiable' => false],
        ];

        foreach ($addons as $a) {
            Addon::firstOrCreate(
                ['name' => $a['name'], 'event_hall_id' => $mainHall->id],
                [
                    'description' => 'Service/Addon for ' . $whiteHallFranchise->name,
                    'price' => $a['price'],
                    'unit' => $a['unit'],
                    'category' => $a['category'],
                    'is_active' => true,
                    'is_quantifiable' => $a['is_quantifiable'],
                ]
            );
        }

        // 5. Create Time Slots
        $slots = [
            ['start' => '08:00:00', 'end' => '13:00:00', 'label' => 'Morning Slot (8am-1pm)'],
            ['start' => '14:00:00', 'end' => '18:00:00', 'label' => 'Afternoon Slot (2pm-6pm)'],
            ['start' => '19:00:00', 'end' => '23:00:00', 'label' => 'Evening Slot (7pm-11pm)'],
        ];

        foreach ($slots as $s) {
            HallTimeSlot::firstOrCreate(
                ['event_hall_id' => $mainHall->id, 'start_time' => $s['start']],
                [
                    'end_time' => $s['end'],
                    'label' => $s['label'],
                    'is_active' => true,
                ]
            );
        }
    }
}
