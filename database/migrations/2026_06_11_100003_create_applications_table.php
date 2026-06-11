<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('incubator_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'under_review', 'accepted', 'rejected'])->default('pending');
            $table->text('motivation');
            $table->string('pitch_deck_url')->nullable();
            $table->text('reviewer_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('status');
            $table->index('startup_id');
            $table->index('incubator_id');
            $table->unique(['startup_id', 'incubator_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
