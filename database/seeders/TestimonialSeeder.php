<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    public function run()
    {
        $testimonials = [
            [
                'client_name' => 'Meron Tadesse',
                'client_position' => 'CEO',
                'client_company' => 'MT Enterprises',
                'content' => 'Addis Mark helped me find the perfect commercial space for my business. Their team was professional and knowledgeable about the local market. I highly recommend their services to anyone looking for property in Ethiopia.',
                'rating' => 5,
                'is_active' => 1,
            ],
            [
                'client_name' => 'Samuel Haile',
                'client_position' => 'Software Engineer',
                'client_company' => 'Tech Solutions Ethiopia',
                'content' => 'Excellent service! Found my dream apartment in Bole within just two weeks of working with Addis Mark. The agent was responsive and showed me multiple properties that matched my criteria perfectly.',
                'rating' => 5,
                'is_active' => 1,
            ],
            [
                'client_name' => 'Helen Mekonnen',
                'client_position' => 'Senior Doctor',
                'client_company' => 'St. Paul Hospital',
                'content' => 'Very satisfied with the professional service. They found us a beautiful villa in CMC area that exceeded our expectations. The entire process was smooth from viewing to closing.',
                'rating' => 5,
                'is_active' => 1,
            ],
            [
                'client_name' => 'Abebe Kebede',
                'client_position' => 'Managing Director',
                'client_company' => 'AK Trading PLC',
                'content' => 'The best real estate agency in Addis Ababa. Transparent process and great communication throughout. They helped us acquire a prime commercial property in a competitive market.',
                'rating' => 5,
                'is_active' => 1,
            ],
            [
                'client_name' => 'Tigist Assefa',
                'client_position' => 'Marketing Manager',
                'client_company' => 'Digital Agency',
                'content' => 'I was impressed by their professionalism and attention to detail. They made finding a rental apartment stress-free. Will definitely use their services again!',
                'rating' => 4,
                'is_active' => 1,
            ],
            [
                'client_name' => 'Dawit Mengistu',
                'client_position' => 'Architect',
                'client_company' => 'DM Designs',
                'content' => 'As an architect, I have high standards for properties. Addis Mark consistently delivered quality listings that met my specific requirements. Highly recommended!',
                'rating' => 5,
                'is_active' => 1,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}