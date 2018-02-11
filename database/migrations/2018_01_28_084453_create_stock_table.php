<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('title')->nullable();
            $table->string('code')->nullable();
            $table->float('sellingPrice', 12, 2)->nullable();
            $table->text('description')->nullable();
            $table->integer('ean')->nullable();
            $table->integer('item_id')->nullable();    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock');
    }
}
