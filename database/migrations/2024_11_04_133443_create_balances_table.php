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
        Schema::create('balances', function (Blueprint $table) {
            $table->id();

            $table->tinyInteger('type')->index();
            $table->integer('amount');

            $table->integer('price_usd')->nullable(); // Основная цена для агрегатов/расчетов
            $table->integer('price')->nullable(); // цена которого ввели в произвольной валюте
            $table->string('currency')->nullable(); // валюта на котором ввели цену
            $table->string('description')->nullable();

            $table->unsignedBigInteger('good_id')->index();
            $table->unsignedBigInteger('warehouse_id')->index();

            $table->unsignedBigInteger('user_id')->index(); // ид сотрудника
            $table->unsignedBigInteger('supplier_id')->nullable(); // ид поставщика
            $table->unsignedBigInteger('client_id')->nullable(); // ид клиента

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balances');
    }
};
