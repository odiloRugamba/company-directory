<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incubator_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('organization_name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('website')->nullable();
            $table->string('country');
            $table->string('city');
            $table->json('focus_sectors');
            $table->json('supported_stages');
            $table->integer('capacity');
            $table->string('logo')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->year('founded_year')->nullable();
            $table->timestamps();

            $table->index('country');
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incubator_profiles');
    }
};
