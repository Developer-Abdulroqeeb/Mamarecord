<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string("product_name")->nullable();
            $table->integer('product_id')->nullable();
            $table->string("Quantity")->nullable();
            $table->decimal('price')->nullable();
            $table->decimal('subtotal')->nullable();
            $table->timestamp('date')->useCurrent()->useCurrentOnUpdate();  
            $table->string('seller_name')->nullable();
            $table->unsignedBigInteger('userId');
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
};
