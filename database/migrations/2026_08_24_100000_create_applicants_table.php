<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();

            // Contact
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('business_name')->nullable();
            $table->string('service_area')->nullable();

            // Experience
            $table->string('years_experience'); // lt1, 1-3, 3-5, 5plus
            $table->boolean('has_turnover_experience')->default(false);
            $table->text('experience_details')->nullable();
            $table->json('services')->nullable();

            // Availability & crew
            $table->string('lead_time'); // same_day, 1_2_days, 3_5_days, 1_week_plus
            $table->string('crew_size'); // solo, 2_3, 4_plus
            $table->boolean('has_backup')->default(false);
            $table->boolean('weekend_availability')->default(false);

            // Business & protection
            $table->boolean('is_insured')->default(false);
            $table->boolean('is_bonded')->default(false);
            $table->boolean('provides_invoices')->default(false);

            // Pricing (move-out clean, flat rate)
            $table->decimal('price_1br', 8, 2)->nullable();
            $table->decimal('price_2br', 8, 2)->nullable();
            $table->decimal('price_3br', 8, 2)->nullable();
            $table->text('pricing_notes')->nullable();

            // Quality
            $table->boolean('reclean_guarantee')->default(false);
            $table->boolean('sends_photos')->default(false);

            // References & misc
            $table->json('references')->nullable();
            $table->text('additional_notes')->nullable();

            // Admin review
            $table->string('status')->default('new'); // new, interviewing, hired, rejected
            $table->json('scores')->nullable();
            $table->unsignedTinyInteger('score_total')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('interview_at')->nullable();

            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
