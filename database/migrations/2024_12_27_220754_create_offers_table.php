<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->float('cost')->default(0)->comment("Сумма заказа");
            $table->integer('status')->nullable()->comment("Статус заказа");
            $table->integer('discount')->nullable()->comment("Кол. скидки");

            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();

            $table->string('client_name');
            $table->string('phone');
            $table->string('phone2')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
