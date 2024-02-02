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
        Schema::create('enterprise_owners', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name", 255);
            $table->string("phone", 255);
            $table->string("email", 100);
            $table->string("ocupation", 255);
            $table->enum("state", ["Solteiro", "Casado", "Divorciado", "Viúvo"]);

            $table->string("nationality", 255);
            $table->string("naturality", 255);
            $table->string("rg", 13);
            $table->date("rg_date", 10);
            $table->string("rg_organ", 10);
            $table->string("cpf", 14);

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
        Schema::dropIfExists('enterprise_owners');
    }
};
