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
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name", 255);
            $table->enum("state", ["Solteiro", "Casado", "Divorciado", "Viúvo"]);
            $table->string("nationality", 255);
            $table->string("ocupation", 255);
            $table->string("rg", 13);
            $table->date("rg_date", 10);
            $table->string("rg_organ", 10);
            $table->string("cpf", 14);
            $table->date("birthdate")->nullable();
            $table->string("phone", 20)->nullable();
            $table->string("email")->nullable();

            $table->string("name_dependent", 255)->nullable();
            $table->string("nationality_dependent", 255)->nullable();
            $table->string("ocupation_dependent", 255)->nullable();
            $table->string("rg_dependent", 13)->nullable();
            $table->date("rg_dependent_date", 10)->nullable();
            $table->string("rg_dependent_organ", 10)->nullable();
            $table->string("cpf_dependent", 14)->nullable();
            $table->date("birthdate_dependent")->nullable();
            $table->string("phone_dependent", 20)->nullable();
            $table->string("email_dependent")->nullable();

            $table->integer('address_id')->unsigned();
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
        Schema::dropIfExists('clients');
    }
};
