<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Location;
use App\Models\Property;
use App\Models\Testimonial;
use App\Models\TeamMember;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@addismark.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Create agent user
        User::create([
            'name' => 'Agent User',
            'email' => 'agent@addismark.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        // Create regular user
        User::create([
            'name' => 'Regular User',
            'email' => 'user@addismark.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        // Create categories
        $categories = [
            ['name' => 'Apartments', 'description' => 'Modern apartments in prime locations', 'is_active' => true],
            ['name' => 'Villas', 'description' => 'Luxury villas with premium amenities', 'is_active' => true],
            ['name' => 'Commercial', 'description' => 'Commercial spaces for business', 'is_active' => true],
            ['name' => 'Land', 'description' => 'Plots of land for development', 'is_active' => true],
            ['name' => 'Offices', 'description' => 'Office spaces in business districts', 'is_active' => true],
            ['name' => 'Luxury Homes', 'description' => 'Exclusive luxury residences', 'is_active' => true],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create locations
        $locations = [
            [
                'city' => 'Addis Ababa',
                'sub_city' => 'Bole',
                'area_name' => 'Bole',
                'is_popular' => true,
            ],
            [
                'city' => 'Addis Ababa',
                'sub_city' => 'Arada',
                'area_name' => 'Piassa',
                'is_popular' => true,
            ],
            [
                'city' => 'Addis Ababa',
                'sub_city' => 'Kirkos',
                'area_name' => 'Meskel Square',
                'is_popular' => true,
            ],
            [
                'city' => 'Addis Ababa',
                'sub_city' => 'Nifas Silk',
                'area_name' => 'Jemo',
                'is_popular' => true,
            ],
            [
                'city' => 'Addis Ababa',
                'sub_city' => 'Gullele',
                'area_name' => 'Piazza',
                'is_popular' => true,
            ],
            [
                'city' => 'Addis Ababa',
                'sub_city' => 'Yeka',
                'area_name' => 'CMC',
                'is_popular' => true,
            ],
            [
                'city' => 'Addis Ababa',
                'sub_city' => 'Bole',
                'area_name' => 'Gerji',
                'is_popular' => false,
            ],
            [
                'city' => 'Addis Ababa',
                'sub_city' => 'Bole',
                'area_name' => 'Summit',
                'is_popular' => false,
            ],
        ];

        foreach ($locations as $locationData) {
            Location::create($locationData);
        }

        // Create sample testimonials
        Testimonial::create([
            'client_name' => 'Meron Tadesse',
            'client_position' => 'Business Owner',
            'client_company' => 'MT Enterprises',
            'content' => 'Addis Mark helped me find the perfect commercial space for my business. Their team was professional and knowledgeable about the local market.',
            'rating' => 5,
            'is_active' => true,
        ]);

        Testimonial::create([
            'client_name' => 'Samuel Haile',
            'client_position' => 'Software Engineer',
            'client_company' => 'Tech Solutions',
            'content' => 'Excellent service! Found my dream apartment in Bole within just two weeks of working with Addis Mark. Highly recommended!',
            'rating' => 5,
            'is_active' => true,
        ]);

        Testimonial::create([
            'client_name' => 'Helen Mekonnen',
            'client_position' => 'Doctor',
            'client_company' => 'St. Paul Hospital',
            'content' => 'Very satisfied with the professional service. They found us a beautiful villa in CMC area that exceeded our expectations.',
            'rating' => 5,
            'is_active' => true,
        ]);

        // Create team members
        TeamMember::create([
            'name' => 'Abebe Kebede',
            'position' => 'CEO & Founder',
            'bio' => 'Over 15 years of experience in Ethiopian real estate market.',
            'image' => 'team/team-1.jpg',
            'email' => 'abebe@addismark.com',
            'phone' => '+251911234567',
            'social_links' => json_encode([
                'linkedin' => 'https://linkedin.com/in/abebe-kebede',
                'twitter' => 'https://twitter.com/abebe_k',
            ]),
            'is_active' => true,
            'sort_order' => 1,
        ]);

        TeamMember::create([
            'name' => 'Tigist Haile',
            'position' => 'Head of Sales',
            'bio' => 'Expert in luxury properties and commercial real estate.',
            'image' => 'team/team-2.jpg',
            'email' => 'tigist@addismark.com',
            'phone' => '+251922345678',
            'social_links' => json_encode([
                'linkedin' => 'https://linkedin.com/in/tigist-haile',
            ]),
            'is_active' => true,
            'sort_order' => 2,
        ]);

        TeamMember::create([
            'name' => 'Dawit Assefa',
            'position' => 'Senior Property Consultant',
            'bio' => 'Specialized in residential properties and first-time home buyers.',
            'image' => 'team/team-3.jpg',
            'email' => 'dawit@addismark.com',
            'phone' => '+251933456789',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        TeamMember::create([
            'name' => 'Sara Mohammed',
            'position' => 'Customer Relations Manager',
            'bio' => 'Dedicated to ensuring client satisfaction and smooth transactions.',
            'image' => 'team/team-4.jpg',
            'email' => 'sara@addismark.com',
            'phone' => '+251944567890',
            'is_active' => true,
            'sort_order' => 4,
        ]);

        // Create sample properties
        $this->createSampleProperties();
    }

    /**
     * Create sample properties for testing
     */
    private function createSampleProperties(): void
    {
        $categories = Category::all();
        $locations = Location::all();
        $adminUser = User::where('email', 'admin@addismark.com')->first();
        $agentUser = User::where('email', 'agent@addismark.com')->first();

        $propertyTypes = ['apartment', 'villa', 'commercial', 'land', 'office'];
        $priceTypes = ['sale', 'rent'];
        
        $sampleProperties = [
            [
                'title' => 'Luxury 3 Bedroom Apartment in Bole',
                'description' => 'Stunning modern apartment with panoramic city views. Features include high-end finishes, open floor plan, and premium appliances.',
                'category' => 'Apartments',
                'location' => 'Bole',
                'price' => 8500000,
                'price_type' => 'sale',
                'property_type' => 'apartment',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area_sqm' => 180,
                'is_featured' => true,
            ],
            [
                'title' => 'Spacious 4 Bedroom Villa in CMC',
                'description' => 'Beautiful villa with large garden, private pool, and modern amenities. Perfect for families seeking luxury living.',
                'category' => 'Villas',
                'location' => 'CMC',
                'price' => 15000000,
                'price_type' => 'sale',
                'property_type' => 'villa',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area_sqm' => 350,
                'is_featured' => true,
            ],
            [
                'title' => 'Commercial Space in Meskel Square',
                'description' => 'Prime commercial property in the heart of Addis. High foot traffic area, perfect for retail or office use.',
                'category' => 'Commercial',
                'location' => 'Meskel Square',
                'price' => 45000,
                'price_type' => 'rent',
                'property_type' => 'commercial',
                'area_sqm' => 200,
                'is_featured' => true,
            ],
            [
                'title' => 'Modern 2 Bedroom in Gerji',
                'description' => 'Contemporary apartment with modern finishes. Close to schools, shopping, and public transportation.',
                'category' => 'Apartments',
                'location' => 'Gerji',
                'price' => 25000,
                'price_type' => 'rent',
                'property_type' => 'apartment',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'area_sqm' => 95,
                'is_featured' => false,
            ],
            [
                'title' => 'Office Space in Summit',
                'description' => 'Professional office space with parking, security, and backup generator. Ideal for startups and established businesses.',
                'category' => 'Offices',
                'location' => 'Summit',
                'price' => 35000,
                'price_type' => 'rent',
                'property_type' => 'office',
                'area_sqm' => 120,
                'is_featured' => false,
            ],
        ];

        foreach ($sampleProperties as $propertyData) {
            $category = $categories->where('name', $propertyData['category'])->first();
            $location = $locations->where('area_name', $propertyData['location'])->first();
            
            if ($category && $location) {
                Property::create([
                    'title' => $propertyData['title'],
                    'description' => $propertyData['description'],
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'user_id' => rand(0, 1) ? $adminUser->id : $agentUser->id,
                    'price' => $propertyData['price'],
                    'price_type' => $propertyData['price_type'],
                    'property_type' => $propertyData['property_type'],
                    'bedrooms' => $propertyData['bedrooms'] ?? null,
                    'bathrooms' => $propertyData['bathrooms'] ?? null,
                    'area_sqm' => $propertyData['area_sqm'] ?? null,
                    'parking' => rand(0, 1),
                    'security' => rand(0, 1),
                    'elevator' => $propertyData['property_type'] === 'apartment' ? rand(0, 1) : false,
                    'furnished' => rand(0, 1),
                    'status' => 'available',
                    'is_featured' => $propertyData['is_featured'],
                    'is_active' => true,
                    'address' => $location->full_address,
                ]);
            }
        }
    }
}