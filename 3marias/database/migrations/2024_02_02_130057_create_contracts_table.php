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
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
 
            $table->string("code", 100)->unique();
            
            $table->string("building_type", 255);
            $table->string("description", 1000);
            $table->string("meters", 1000);
            $table->double("value");

            $table->date("date");

            $table->string("witness_one_name", 255);
            $table->string("witness_one_cpf", 14);
            $table->string("witness_two_name", 255);
            $table->string("witness_two_cpf", 14);

            $table->integer('address_id')->unsigned();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');

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
        Schema::dropIfExists('contracts');
    }
};
