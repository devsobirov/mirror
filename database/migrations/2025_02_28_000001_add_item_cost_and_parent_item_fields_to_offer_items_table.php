<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offer_items', function (Blueprint $table) {
            $table->float('item_cost', 10, 2)->nullable()->comment('Стоимость единицы товара');
            $table->unsignedBigInteger('parent_item_id')->nullable()->index()->comment('ID родительской позиции');

            $table->foreign('parent_item_id')
                ->references('id')
                ->on('offer_items')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('offer_items', function (Blueprint $table) {
            $table->dropForeign(['parent_item_id']);
            $table->dropColumn(['item_cost', 'parent_item_id']);
        });
    }
};