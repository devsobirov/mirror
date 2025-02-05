<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offer_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id')->index()->comment('ID заказа');
            $table->unsignedBigInteger('service_id')->index()->comment('ID услуги');
            $table->float('price', 10, 2)->comment('Цена услуги (может быть изменена пользователем)');
            $table->float('original_price', 10, 2)->comment('Оригинальная цена услуги из каталога');
            $table->float('quantity', 8, 3)->comment('Количество услуг (до 3 знаков после запятой)');
            $table->float('cost', 10, 2)->comment('Общая стоимость позиции (price * quantity)');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_items');
    }
};
