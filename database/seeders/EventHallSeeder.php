<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Franchise;
use App\Models\EventHall;
use App\Models\HallPricingRule;
use App\Models\Addon;
use App\Models\HallTimeSlot;

class EventHallSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Franchises
        $hq = Franchise::firstOrCreate(
            ['code' => 'SHR-HQ'],
            [
                'name' => 'Savanna Hill Resort (Ulu Tiram)',
                'company_name' => 'Savanna Hill Resort Sdn Bhd',
                'address' => 'Jalan Sungai Tiram, 81800 Ulu Tiram, Johor',
                'contact_person' => 'Ahmad Razali',
                'phone' => '07-861 2345',
                'email' => 'booking@savannahill.com.my',
                'status' => 'active',
                'logo' => 'seed-images/shr_hq_logo.jpg',
            ]
        );

        $jb = Franchise::firstOrCreate(
            ['code' => 'SHR-JB'],
            [
                'name' => 'Savanna Event Space (JB City)',
                'company_name' => 'Savanna Events City Sdn Bhd',
                'address' => 'Jalan Wong Ah Fook, 80000 Johor Bahru, Johor',
                'contact_person' => 'Sarah Tan',
                'phone' => '07-223 4567',
                'email' => 'jb@savannahill.com.my',
                'status' => 'active',
                'logo' => 'seed-images/shr_jb_logo.jpg',
            ]
        );

        // 2. Create Event Halls
        $ballroom = EventHall::firstOrCreate(
            ['code' => 'HQ-GRAND'],
            [
                'franchise_id' => $hq->id,
                'name' => 'Grand Savanna Ballroom',
                'hall_type' => 'Ballroom',
                'capacity' => 1000,
                'floor_area' => 800.5,
                'description' => 'Our flagship grand ballroom featuring crystal chandeliers, pillarless design, and state-of-the-art audiovisual systems. Perfect for grand weddings and corporate annual dinners.',
                'amenities' => ['Air Conditioning', 'VIP Waiting Room', 'Private Restrooms', 'Bridal Suite'],
                'facilities' => ['PA System', 'LED Screen', 'Stage', 'Lighting System', 'High-Speed WiFi'],
                'min_booking_hours' => 4,
                'max_booking_hours' => 12,
                'buffer_before' => 120, // 2 hours setup
                'buffer_after' => 120, // 2 hours teardown
                'is_active' => true,
                'cover_image' => 'seed-images/hq_grand.jpg',
            ]
        );

        $dewan = EventHall::firstOrCreate(
            ['code' => 'HQ-DEWAN'],
            [
                'franchise_id' => $hq->id,
                'name' => 'Dewan Sri Savanna',
                'hall_type' => 'Banquet Hall',
                'capacity' => 400,
                'floor_area' => 400.0,
                'description' => 'A beautifully designed banquet hall perfect for medium-sized weddings, seminars, and corporate meetings.',
                'amenities' => ['Air Conditioning', 'Surau', 'Holding Room'],
                'facilities' => ['PA System', 'Projector', 'Whiteboard'],
                'min_booking_hours' => 4,
                'buffer_before' => 60,
                'buffer_after' => 60,
                'is_active' => true,
                'cover_image' => 'seed-images/hq_dewan.jpg',
            ]
        );

        $sky = EventHall::firstOrCreate(
            ['code' => 'JB-SKY'],
            [
                'franchise_id' => $jb->id,
                'name' => 'Sky View Hall',
                'hall_type' => 'Rooftop Hall',
                'capacity' => 200,
                'floor_area' => 250.0,
                'description' => 'Stunning rooftop hall with panoramic views of Johor Bahru city center. Ideal for exclusive events, product launches, and private parties.',
                'amenities' => ['Air Conditioning', 'Outdoor Balcony'],
                'facilities' => ['Sound System', 'Mood Lighting', 'Bar Area'],
                'min_booking_hours' => 3,
                'buffer_before' => 60,
                'buffer_after' => 60,
                'is_active' => true,
                'cover_image' => 'seed-images/jb_sky.jpg',
            ]
        );

        // 3. Create Pricing Rules
        // Grand Ballroom Pricing
        HallPricingRule::firstOrCreate(['event_hall_id' => $ballroom->id, 'label' => 'Standard Hourly'], [
            'rate_type' => 'hourly',
            'day_type' => 'weekday',
            'price' => 800.00,
        ]);
        HallPricingRule::firstOrCreate(['event_hall_id' => $ballroom->id, 'label' => 'Weekend Hourly'], [
            'rate_type' => 'hourly',
            'day_type' => 'weekend',
            'price' => 1200.00,
        ]);
        HallPricingRule::firstOrCreate(['event_hall_id' => $ballroom->id, 'label' => 'Full Day Wedding Package'], [
            'rate_type' => 'full_day',
            'day_type' => 'all',
            'price' => 8500.00,
            'full_day_hours' => 12,
        ]);

        // Dewan Pricing
        HallPricingRule::firstOrCreate(['event_hall_id' => $dewan->id, 'label' => 'Standard Hourly'], [
            'rate_type' => 'hourly',
            'day_type' => 'all',
            'price' => 450.00,
        ]);
        HallPricingRule::firstOrCreate(['event_hall_id' => $dewan->id, 'label' => 'Half Day Seminar'], [
            'rate_type' => 'half_day',
            'day_type' => 'weekday',
            'price' => 1500.00,
            'half_day_hours' => 4,
        ]);

        // Sky View Pricing
        HallPricingRule::firstOrCreate(['event_hall_id' => $sky->id, 'label' => 'Hourly Rate'], [
            'rate_type' => 'hourly',
            'day_type' => 'all',
            'price' => 600.00,
        ]);

        // 4. Create Add-ons
        // Catering Add-ons
        Addon::firstOrCreate(['event_hall_id' => $ballroom->id, 'name' => 'Premium Dome Catering'], [
            'category' => 'Catering',
            'description' => 'Standard menu with 5 dishes, rice, dessert, and drinks',
            'price' => 45.00,
            'unit' => 'pax',
            'is_quantifiable' => true,
            'is_active' => true,
        ]);
        Addon::firstOrCreate(['event_hall_id' => $ballroom->id, 'name' => 'VIP Table Serving'], [
            'category' => 'Catering',
            'description' => 'Special VIP menu with exclusive servers',
            'price' => 800.00,
            'unit' => 'table',
            'is_quantifiable' => true,
            'is_active' => true,
        ]);
        
        // Equipment Add-ons
        Addon::firstOrCreate(['event_hall_id' => $ballroom->id, 'name' => 'Grand LED Screen'], [
            'category' => 'Equipment',
            'description' => 'Massive 30ft x 10ft P2.5 LED Screen for spectacular visuals',
            'price' => 2500.00,
            'unit' => 'event',
            'is_quantifiable' => false,
            'is_active' => true,
        ]);
        Addon::firstOrCreate(['event_hall_id' => $ballroom->id, 'name' => 'Smoke Machine & Lighting'], [
            'category' => 'Equipment',
            'description' => 'Heavy smoke machine for grand entrance',
            'price' => 500.00,
            'unit' => 'event',
            'is_quantifiable' => false,
            'is_active' => true,
        ]);

        Addon::firstOrCreate(['event_hall_id' => $dewan->id, 'name' => 'Basic PA System'], [
            'category' => 'Equipment',
            'description' => 'Includes 2 mics and standard speakers',
            'price' => 300.00,
            'unit' => 'event',
            'is_quantifiable' => false,
            'is_active' => true,
        ]);
        Addon::firstOrCreate(['event_hall_id' => $dewan->id, 'name' => 'Buffet Catering (Standard)'], [
            'category' => 'Catering',
            'description' => 'Standard buffet spread',
            'price' => 30.00,
            'unit' => 'pax',
            'is_quantifiable' => true,
            'is_active' => true,
        ]);

        // 5. Create Time Slots
        HallTimeSlot::firstOrCreate(['event_hall_id' => $ballroom->id, 'label' => 'Morning Session'], [
            'start_time' => '08:00:00',
            'end_time' => '14:00:00',
        ]);
        HallTimeSlot::firstOrCreate(['event_hall_id' => $ballroom->id, 'label' => 'Evening Session'], [
            'start_time' => '16:00:00',
            'end_time' => '23:00:00',
        ]);
        
        HallTimeSlot::firstOrCreate(['event_hall_id' => $dewan->id, 'label' => 'Morning Slot'], [
            'start_time' => '09:00:00',
            'end_time' => '13:00:00',
        ]);
        HallTimeSlot::firstOrCreate(['event_hall_id' => $dewan->id, 'label' => 'Afternoon Slot'], [
            'start_time' => '14:00:00',
            'end_time' => '18:00:00',
        ]);
        HallTimeSlot::firstOrCreate(['event_hall_id' => $dewan->id, 'label' => 'Night Slot'], [
            'start_time' => '19:00:00',
            'end_time' => '23:00:00',
        ]);
    }
}
