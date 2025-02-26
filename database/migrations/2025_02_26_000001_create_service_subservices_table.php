<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_subservices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id')->index();
            $table->unsignedBigInteger('subservice_id')->index();

            // Ensure unique combinations of service and subservice
            $table->unique(['service_id', 'subservice_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_subservices');
    }
};