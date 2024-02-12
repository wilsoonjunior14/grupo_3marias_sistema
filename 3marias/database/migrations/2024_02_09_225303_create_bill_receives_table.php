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
        Schema::create('bill_receives', function (Blueprint $table) {
            $table->increments('id');
 
            $table->string("code", 100)->unique();
            $table->string("type", 100);
            $table->double("value");
            $table->double("value_performed");
            $table->string("description", 255);
            $table->string("source", 100)->nullable();
            $table->date("desired_date")->nullable();
            $table->string("bank", 100)->nullable();
            $table->integer("status")->default(0); // 0 - in progress, 1 - done

            $table->integer('contract_id')->unsigned()->nullable();
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');

            $table->integer('purchase_order_id')->unsigned()->nullable();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');

            $table->boolean("deleted")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_receives');
    }
};
