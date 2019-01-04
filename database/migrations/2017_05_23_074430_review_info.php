<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReviewInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_info', function (Blueprint $table) {
          $table->increments('id');
          $table->string('shop_id')->nullable();
          $table->string('product_id')->nullable();
          $table->boolean('is_review')->default(0);
          $table->text('review_info')->nullable();
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
        Schema::dropIfExists('review_info');
    }
}
