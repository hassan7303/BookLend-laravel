<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('borrow_id');
            $table->integer('days_late');
            $table->integer('amount');
            $table->boolean('paid')->default(false);
            $table->date('paid_date')->nullable();
            $table->timestamps();

            $table->foreign('borrow_id')->references('id')->on('borrows')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('fines');
    }
};
