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
        Schema::create('returned_goods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id');
            $table->integer('qnty_return')->nullable();
            $table->integer('cost_per_unit')->nullable();
            $table->foreignId('userId')->constrained('users')->onDelete('cascade');
            $table->string('reason')->nullable();
            $table->decimal('total')->nullable();
            $table->decimal('amount');
            $table->string("mode_refund");
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
        Schema::dropIfExists('returned_goods');
    }
};
