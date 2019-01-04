<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateCommentsDefaultAdminTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments_default_admin', function (Blueprint $table) {
			$table->increments('id');
			$table->string( 'author', 255 )->nullable();
			$table->string( 'country', 20 )->nullable();
			$table->integer( 'star' )->nullable();
			$table->text( 'content' )->nullable();
			$table->mediumText( 'img' )->nullable();
			$table->mediumText( 'user_order_info' )->nullable();
			$table->string( 'email', 255 )->nullable();
			$table->string( 'avatar', 255 )->nullable();
			$table->integer( 'status' )->nullable();
			$table->integer( 'verified' )->nullable();
			$table->integer( 'pin' )->default(0);
			$table->integer( 'like' )->default(0);
			$table->integer( 'unlike' )->default(0);
			$table->string( 'source', 255 )->nullable();
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
		Schema::dropIfExists('comments_default_admin');
	}
}