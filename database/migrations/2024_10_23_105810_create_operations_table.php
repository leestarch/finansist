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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pizzeria_id')->nullable();
            $table->date('date_at')->nullable()->default(null);
            $table->bigInteger('sber_amountRub')->nullable();
            $table->string('sber_direction')->nullable();
            $table->text('sber_paymentPurpose')->nullable();
            $table->string('sber_operationId')->nullable();
            $table->foreignId('payer_contractor_id')->nullable();
            $table->foreignId('payee_contractor_id')->nullable();

            $table->boolean('is_completed')->default(false);
            $table->boolean('is_manual')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
