<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('print_settings', function (Blueprint $table) {
            $table->id();
            $table->string('organization')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('type')->nullable();
            $table->string('thanks')->nullable();
            $table->timestamps();
        });

        DB::table('print_settings')->insert([
            'organization' => "Xonqa Mebel Jihozlari",
            'phone1' => '+998-93-944-3555',
            'phone2' => '',
            'thanks' => 'Haridingiz uchun rahmat!'
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('print_settings');
    }
};
