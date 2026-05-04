<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('rating');               // 1–5
            $table->tinyInteger('quality_rating')->nullable();
            $table->tinyInteger('responsiveness_rating')->nullable();
            $table->tinyInteger('punctuality_rating')->nullable();
            $table->tinyInteger('professionalism_rating')->nullable();
            $table->string('title')->nullable();
            $table->text('body');
            $table->string('service_used')->nullable();
            $table->date('service_date')->nullable();
            $table->decimal('price_paid', 10, 2)->nullable();
            $table->boolean('would_hire_again')->default(true);
            $table->boolean('verified')->default(false);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('owner_response')->nullable();
            $table->timestamp('owner_responded_at')->nullable();
            $table->integer('helpful_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['business_id', 'user_id']); // one review per user per business
        });

        // Helpful votes
        Schema::create('review_helpfuls', function (Blueprint $table) {
            $table->foreignId('review_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['review_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_helpfuls');
        Schema::dropIfExists('reviews');
    }
};