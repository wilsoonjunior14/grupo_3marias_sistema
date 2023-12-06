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
        Schema::create('category_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name");
            $table->boolean("deleted")->default(false);
        
            $table->integer('category_products_father_id')->unsigned()->nullable();
            $table->foreign('category_products_father_id')->references('id')->on('category_products')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_products');
    }
};
