<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->index()->comment('ид клиента');
            $table->unsignedBigInteger('debt_id')->index()->comment('долг за который принят платеж');
            $table->integer('amount')->comment('сумма платежа');
            $table->integer('type')->comment('способ оплаты');
            $table->integer('left_debt_amount')->comment('остаток долга после платежа');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
