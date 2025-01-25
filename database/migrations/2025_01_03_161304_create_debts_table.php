<?php

use App\Enums\Finance\DebtStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->index()->comment('ид клиента');
            $table->integer('amount')->comment('сумма долга');
            $table->timestamp('payment_date')->index()->comment('назначенная дата платежа');
            $table->timestamp('closed_at')->nullable()->comment('дата фактического закрытия долга');
            $table->tinyInteger('status')
                ->default(DebtStatusEnum::Pending)
                ->comment('статус долга');
            $table->string('caption')->nullable()->comment('примечания/комментарий');
            $table->unsignedBigInteger('offer_id')->nullable()->comment('ид заказа, если есть');
            $table->float('rating', 2, 1)->nullable()->comment("Оценка от 1 до 5, после закрытие долга");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
