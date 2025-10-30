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
        Schema::create('producthistories', function (Blueprint $table) {
            $table->id();
            $table->string('ProductName')->nullable();
            $table->string('StockQnty')->nullable();
            $table->string('Unit')->nullable();
            $table->string('CostPerUnit')->nullable();
            $table->string('SellinPerUnit')->nullable();
            $table->string('ReorderLevel')->nullable();
            $table->string('SupplierName')->nullable();
            $table->timestamp('last_update')->useCurrent()->useCurrentOnUpdate();        
            $table->unsignedBigInteger('userId');
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->string('description'); 
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
        Schema::dropIfExists('producthistories');
    }
};
