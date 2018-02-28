<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportsShoptetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('stock_shoptet', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->text('categories')->nullable();
            $table->string('default_category')->nullable();
            $table->integer('shoptet_id');
            $table->string('code')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->text('images')->nullable();
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
        Schema::dropIfExists('stock_shoptet');
    }
}
