<?php

use App\Models\OperationRule;
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
        Schema::create('operation_rules', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('contractor_id')->nullable();
            $table->string('purpose_expression')->nullable();
            $table->string('operation_type')->default(OperationRule::DEBIT)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_rules');
    }
};
