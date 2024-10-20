<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bill_tickets', function (Blueprint $table) {
            $table->increments("id");
            $table->double("value");
            $table->string("description", 255);
            $table->date("date");

            $table->integer('bill_pay_id')->unsigned()->nullable();
            $table->foreign('bill_pay_id')->references('id')->on('bill_pays')->onDelete('cascade');

            $table->integer('bill_receive_id')->unsigned()->nullable();
            $table->foreign('bill_receive_id')->references('id')->on('bill_receives')->onDelete('cascade');

            $table->boolean("deleted")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_tickets');
    }
};
