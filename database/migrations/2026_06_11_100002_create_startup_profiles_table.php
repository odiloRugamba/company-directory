<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('startup_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('company_name');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->text('description');
            $table->string('website')->nullable();
            $table->string('country');
            $table->string('city');
            $table->string('sector');
            $table->enum('stage', ['idea', 'mvp', 'early_traction', 'growth', 'scale']);
            $table->year('founded_year')->nullable();
            $table->integer('team_size')->default(1);
            $table->string('logo')->nullable();
            $table->timestamps();

            $table->index('slug');
            $table->index('sector');
            $table->index('country');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('startup_profiles');
    }
};
