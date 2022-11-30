<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->enum('type', ['home', 'office'])->default('home');
            $table->string('street');
            $table->string('house_number');
            $table->string('locality'); //  Neighborhood, Quarter, or Settlement 
            $table->string('province'); // Municipality
            $table->string('city');
            $table->string('state');
            $table->string('country', 2)->default('MX');
            $table->string('postal_code');
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
        Schema::dropIfExists('user_addresses');
    }
};
