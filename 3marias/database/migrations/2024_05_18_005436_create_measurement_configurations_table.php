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
        Schema::create('measurement_configurations', function (Blueprint $table) {
            $table->increments("id");

            $table->double("incidence");

            $table->integer('measurement_item_id')->unsigned()->nullable();
            $table->foreign('measurement_item_id')->references('id')->on('measurement_items')->onDelete('cascade');
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
        Schema::dropIfExists('measurement_configurations');
    }
};
