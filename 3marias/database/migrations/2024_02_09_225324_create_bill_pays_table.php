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
        Schema::create('bill_pays', function (Blueprint $table) {
            $table->id();

            $table->string("code", 100)->unique();
            $table->double("value");
            $table->double("value_performed");
            $table->string("description", 255);
            $table->integer("status")->default(0); // 0 - in progress, 1 - done

            $table->integer('service_orders_id')->unsigned()->nullable();
            $table->foreign('service_orders_id')->references('id')->on('service_orders')->onDelete('cascade');

            $table->integer('purchase_orders_id')->unsigned()->nullable();
            $table->foreign('purchase_orders_id')->references('id')->on('purchase_orders')->onDelete('cascade');

            $table->boolean("deleted")->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_pays');
    }
};
