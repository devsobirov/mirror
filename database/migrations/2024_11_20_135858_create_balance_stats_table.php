<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('balance_stats', function (Blueprint $table) {
            $table->id();

            $table->integer('amount');
            $table->unsignedBigInteger('good_id')->index();
            $table->unsignedBigInteger('warehouse_id')->index();

            $table->year('year')->index();
            $table->timestamp('last_income_date')->nullable();
            $table->timestamp('last_expense_date')->nullable();
            $table->timestamp('updated_at')
                ->nullable()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balance_stats');
    }
};
