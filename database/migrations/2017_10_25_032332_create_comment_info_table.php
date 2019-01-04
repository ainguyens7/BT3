<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_info', function (Blueprint $table) {
            $table->increments('id');
	        $table->string('shop_id');
	        $table->string('comment_id');
	        $table->string('ip_like')->nullable();
	        $table->string('ip_unlike')->nullable();
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
        Schema::dropIfExists('comment_info');
    }
}
