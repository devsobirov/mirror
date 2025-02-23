<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offer_items', function (Blueprint $table) {
            $table->float('x', 1)->nullable()->comment("ширина в см");
            $table->float('y', 1)->nullable()->comment("высота в см");
            $table->float('dimension_amount')->nullable()->default(1)->comment("расчитанный по формуле конечный размер (количества) на ед. услуги для расчета суммы");
            $table->integer('quantity')->change();
        });
    }
    
    public function down(): void
    {
        Schema::table('offer_items', function (Blueprint $table) {
            $table->dropColumn(['x', 'y', 'dimension_amount']);
            $table->float('quantity', 8, 3)->change();
        });
    }
};
