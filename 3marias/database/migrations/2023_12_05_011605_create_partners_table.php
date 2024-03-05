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
        Schema::create('partners', function (Blueprint $table) {
            $table->increments('id');
            $table->string("fantasy_name", 255);
            $table->enum("partner_type", ["Física", "Jurídica"]);
            $table->string("cnpj", 20)->nullable();
            $table->string("social_reason", 255)->nullable();
            $table->string("phone", 20)->nullable();
            $table->string("email", 100)->nullable();
            $table->string("website", 255)->nullable();
            $table->string("observation", 500)->nullable();

            $table->integer('address_id')->unsigned()->nullable();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');

            $table->boolean("deleted")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
