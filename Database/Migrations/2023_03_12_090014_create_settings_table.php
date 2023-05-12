<?php

// Test

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->boolean('registration_state')->default(true); // State of the registration

            $table->string('app_name')->default('Yezz.Design'); // Name of the Application
            $table->string('app_name_backend')->default('Yezz.Backend'); // Name of the Applications Backend

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
