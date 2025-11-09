<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('fine_rules', function (Blueprint $table) {
            $table->id();
            $table->integer('per_day_amount');
            $table->string('currency')->default('Toman');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fine_rules');
    }
};
