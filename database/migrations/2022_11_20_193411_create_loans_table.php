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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('user_id');
            $table->enum('type', ['PERSONAL', 'GROUP'])->default('PERSONAL');
            $table->float('amount', 10, 2)->default(0);
            $table->integer('duration_unit');
            $table->enum('duration_period', ['WEEK', 'MONTH', 'YEAR']);
            $table->decimal('roi')->default(20); // rate or percent of interest
            $table->string('status')->nullable();
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
        Schema::dropIfExists('loans');
    }
};
