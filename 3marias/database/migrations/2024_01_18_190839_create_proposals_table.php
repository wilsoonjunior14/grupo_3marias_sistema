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
        Schema::create('proposals', function (Blueprint $table) {
            $table->increments('id');
 
            $table->string("code", 100)->unique();
            $table->string("construction_type", 100);
            $table->string("proposal_type", 100);
            $table->double("global_value");
            $table->date("proposal_date");
            $table->string("description", 1000);
            $table->double("discount")->default(0);

            $table->integer("status")->default(0); // 0 - Negociacao, 1 - Cancelada, 2 - Aprovada

            $table->integer('address_id')->unsigned();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');

            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');

            $table->boolean("deleted")->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
