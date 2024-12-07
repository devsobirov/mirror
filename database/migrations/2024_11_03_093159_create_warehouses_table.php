<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('active')->default(true);

            $table->string('image')->nullable();
            $table->string('address')->nullable();
            $table->string('comment')->nullable();
        });

        Schema::create('warehouse_users', function (Blueprint $table) {
           $table->unsignedInteger('warehouse_id');
           $table->unsignedInteger('user_id');
           $table->integer('action')->comment('1 - Kirim, 2 - Chiqim');

           $table->unique(['warehouse_id', 'user_id', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_users');
        Schema::dropIfExists('warehouses');
    }
};
