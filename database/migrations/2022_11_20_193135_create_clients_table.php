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
        Schema::table('users', function (Blueprint $table) {
            $table->string('lastname')->after('name');
            $table->date('birthdate')->after('lastname');
            $table->enum('gender', ['M', 'F', 'O'])->default('M')->after('birthdate');
            $table->string('preferred_contact_method')->nullable(); // sms, whatsapp, call
            $table->string('type')->nullable();
        });

        Schema::create('user_phonenumbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('phonenumber')->nullable();
            $table->enum('type', ['MOBILE', 'HOME', 'OFFICE'])->default('MOBILE');
            $table->boolean('is_main')->default(false);
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
        Schema::dropIfExists('user_phonenumbers');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['lastname', 'birthdate', 'gender', 'preferred_contact_method', 'type']);
        });
    }
};
