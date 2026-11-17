<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('header_footers', function (Blueprint $table) {
            $table->id();
            $table->string('telephone')->nullable();
            $table->string('duty_clark')->nullable();
            $table->string('it_phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax')->nullable();
            $table->string('sticker_query')->nullable();
            $table->string('graveyard')->nullable();
            $table->string('location')->nullable();
            $table->string('about')->nullable();
            $table->string('army_exch')->nullable();
            $table->string('duty_clerk_mil')->nullable();
            $table->string('unique_visitor')->nullable();
            $table->string('copyright')->nullable();
            $table->string('breaking_news')->nullable();
            $table->enum('is_enable_family_id', ['yes', 'no'])->default('no');
            $table->enum('is_enable_batman_id', ['yes', 'no'])->default('no');
            $table->enum('is_enable_employee_id', ['yes', 'no'])->default('no');
            $table->enum('is_sticker_login', ['yes', 'no'])->default('no');
            $table->enum('is_sticker_register', ['yes', 'no'])->default('no');
            $table->enum('is_sticker_policy', ['yes', 'no'])->default('no');
            $table->enum('is_sticker_tutorial', ['yes', 'no'])->default('no');
            $table->enum('is_graveyard_allotment', ['yes', 'no'])->default('no');
            $table->enum('is_graveyard_policy', ['yes', 'no'])->default('no');
            $table->enum('is_graveyard_application_form', ['yes', 'no'])->default('no');
            $table->enum('is_photo_gallery', ['yes', 'no'])->default('no');
            $table->enum('is_staf_org', ['yes', 'no'])->default('no');
            $table->enum('is_history', ['yes', 'no'])->default('no');
            $table->enum('is_contact', ['yes', 'no'])->default('no');
            $table->enum('is_bus', ['yes', 'no'])->default('no');
            $table->enum('is_shuttle', ['yes', 'no'])->default('no');
            $table->enum('is_general_contact_info', ['yes', 'no'])->default('no');
            $table->enum('gate_on_of_time', ['yes', 'no'])->default('no');
            $table->enum('login', ['yes', 'no'])->default('no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('header_footers');
    }
};
