<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Shop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop', function (Blueprint $table) {
          $table->string('shop_id');
          $table->string('shop_name',100)->nullable();
          $table->string('shop_email',200)->nullable();
          $table->boolean('shop_status')->default(1);
          $table->boolean('is_review_app')->default(0);
          $table->string('shop_country',30)->nullable();
          $table->string('shop_owner',500)->nullable();
          $table->string('plan_name')->nullable(); // plan shopify của shop
          $table->string('app_version',100)->nullable();
          $table->string('app_plan')->nullable(); // plan trong app ali của shop
          $table->boolean('init_app')->default(0);
          $table->string('billing_id',100)->nullable();
          $table->string('billing_on')->nullable();
          $table->string('cancelled_on')->nullable();
          $table->string('code_invite')->nullable(); // code invite để shop gửi cho bạn bè
          $table->dateTime('trial_date')->nullable(); // ngày trial
          $table->integer('are_trial')->default(0); // đang trong chu kỳ trial hay không
          $table->char('access_token',32)->nullable();
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
        Schema::dropIfExists('shop');
    }
}
