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
        Schema::create('contract_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name", 100);
            $table->text("content");
            $table->enum("type", ["Corretagem", "Entrega das Chaves", "Serviço", "Venda"]);
            $table->boolean("deleted")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_models');
    }
};
