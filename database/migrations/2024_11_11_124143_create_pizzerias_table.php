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
        Schema::create('pizzerias', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('inn')->nullable();
            $table->float('planfact_cash_account')->nullable();
            $table->string('dodois_unit_uid', 50)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('to_user_id')->nullable();
            $table->string('budget', 50)->nullable();
            $table->text('agentIds')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pizzerias');
    }
};
