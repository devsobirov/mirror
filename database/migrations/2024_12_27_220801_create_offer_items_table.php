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
            $table->unsignedBigInteger('offer_id')->index();
            $table->unsignedBigInteger('service_id')->index();
            $table->integer('amount');
            $table->integer('cost');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_items');
    }
};
