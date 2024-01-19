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
        Schema::create('proposal_payments', function (Blueprint $table) {
            $table->increments('id');
 
            $table->string("code", 100)->unique();
            $table->string("type", 100);
            $table->double("value");
            $table->string("description", 255);
            $table->string("source", 100);
            $table->date("desired_date")->nullable();
            $table->string("bank", 100)->nullable();
            $table->integer("status")->default(0); // 0 - negociando, 1 - aguardando, 2 - pago

            $table->integer('proposal_id')->unsigned();
            $table->foreign('proposal_id')->references('id')->on('proposals')->onDelete('cascade');

            $table->boolean("deleted")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_payments');
    }
};
