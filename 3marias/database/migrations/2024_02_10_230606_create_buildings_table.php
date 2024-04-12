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
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();

            $table->string("code", 100)->unique();
            $table->string("description", 255);
            $table->date("start_date");
            $table->date("end_date");
            $table->string("art_number", 100)->nullable();
            $table->string("art_document", 100)->nullable();
            $table->string("engineer_name", 255)->nullable();
            $table->string("engineer_cpf", 14)->nullable();
            $table->string("engineer_crea", 100)->nullable();

            $table->integer('contract_id')->unsigned()->nullable();
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');

            $table->boolean("deleted")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
