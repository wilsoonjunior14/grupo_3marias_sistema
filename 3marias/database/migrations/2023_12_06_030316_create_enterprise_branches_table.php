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
        Schema::create('enterprise_branches', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name", 255);
            $table->string("cnpj", 30);
            $table->string("phone", 20);

            $table->integer('address_id')->unsigned();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');

            $table->integer('enterprise_id')->unsigned();
            $table->foreign('enterprise_id')->references('id')->on('enterprises')->onDelete('cascade');
        
            $table->boolean("deleted")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enterprise_branches');
    }
};
