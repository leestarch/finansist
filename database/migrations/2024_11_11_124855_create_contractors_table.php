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
        Schema::create('contractors', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->text('full_name')->nullable();
            $table->text('inn_kpp')->nullable();
            $table->text('ogrn')->nullable();
            $table->text('legal_address')->nullable();
            $table->text('fact_address')->nullable();
            $table->text('director')->nullable();
            $table->text('okved')->nullable();
            $table->text('checking_account')->nullable();
            $table->text('bank_name')->nullable();
            $table->text('corr_account')->nullable();
            $table->text('bank_bik')->nullable();
            $table->text('phone')->nullable();
            $table->text('email')->nullable();
            $table->text('site')->nullable();
            $table->text('okpo')->nullable();
            $table->text('oktmo')->nullable();
            $table->text('okogu')->nullable();
            $table->text('okato')->nullable();
            $table->text('okfs')->nullable();
            $table->text('okopf')->nullable();
            $table->text('registration_date')->nullable();
            $table->text('nalog_system')->nullable();
            $table->timestamps();
        });

        Schema::create('contractor_pizzeria', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contractor_id');
            $table->unsignedBigInteger('pizzeria_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractors');
        Schema::dropIfExists('contractor_pizzeria');
    }
};
