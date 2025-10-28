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
            $table->string('ProductName');
            $table->string('StockQnty');
            $table->string('Unit');
            $table->string('CostPerUnit');
            $table->string('SellinPerUnit');
            $table->string('ReorderLevel');
            $table->string('SupplierName');
            $table->timestamp('last_update')->useCurrent()->useCurrentOnUpdate();        
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
