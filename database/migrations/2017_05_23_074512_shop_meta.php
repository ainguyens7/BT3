<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShopMeta extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_meta', function (Blueprint $table) {
			$table->string('shop_id');
			$table->tinyInteger('style')->nullable();
			$table->text('setting')->nullable();
			$table->text('translate')->nullable();
			$table->integer('approve_review')->default(1);
			$table->integer('is_translate')->default(1);
			$table->integer('is_comment_default')->default(0);
			$table->string("rand_comment_default")->nullable()->default(json_encode(config('settings.rand_comment_default')));
			$table->text("style_customize")->nullable();
			$table->text("code_css")->nullable();
			$table->integer('is_code_css')->default(0);
			$table->integer("rating_point")->default(1);
            $table->integer("rating_card")->default(1);
			$table->integer("active_frontend")->default(1);
			$table->text("page_reviews")->nullable();
			$table->timestamps();
			$table->primary('shop_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('shop_meta');
	}
}
