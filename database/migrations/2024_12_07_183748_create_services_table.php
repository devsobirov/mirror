<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment("Название услуги");
            $table->string('image')->nullable()->comment("УРЛ картинки для листинга");
            $table->unsignedBigInteger('category_id')->index()->comment("ИД родительской категории");
            $table->boolean('active')->nullable()->default(false)
                ->comment("Статус - по умолчанию отключен (false)");

            $table->string('unit')->comment("Название единицы");
            $table->integer('price')->comment("Цена UZS для 1 единицы");
            $table->float('margin')->nullable()->default(0)->comment("Наценка в % для 1 единицы (н-р, 10.5%)");
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
