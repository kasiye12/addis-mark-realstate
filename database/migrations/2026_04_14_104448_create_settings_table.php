<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, image, video, json
            $table->timestamps();
        });

        // Insert default settings
        \DB::table('settings')->insert([
            ['key' => 'site_logo', 'value' => null, 'type' => 'image'],
            ['key' => 'site_favicon', 'value' => null, 'type' => 'image'],
            ['key' => 'homepage_video', 'value' => null, 'type' => 'video'],
            ['key' => 'homepage_video_poster', 'value' => null, 'type' => 'image'],
            ['key' => 'site_name', 'value' => 'Addis Mark Real Estate', 'type' => 'text'],
            ['key' => 'site_description', 'value' => 'Premium Real Estate in Ethiopia', 'type' => 'text'],
            ['key' => 'contact_email', 'value' => 'info@addismark.com', 'type' => 'text'],
            ['key' => 'contact_phone', 'value' => '+251 11 234 5678', 'type' => 'text'],
            ['key' => 'contact_address', 'value' => 'Bole Road, Addis Ababa, Ethiopia', 'type' => 'text'],
            ['key' => 'facebook_url', 'value' => '#', 'type' => 'text'],
            ['key' => 'instagram_url', 'value' => '#', 'type' => 'text'],
            ['key' => 'twitter_url', 'value' => '#', 'type' => 'text'],
            ['key' => 'linkedin_url', 'value' => '#', 'type' => 'text'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};