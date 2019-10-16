<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->decimal('amount', 14, 2)->comment('订单价格');
            $table->integer('channel_id')->comment('渠道id');
            $table->integer('pack_id')->comment('套餐id');
            $table->string('pack_name')->comment('套餐名');
            $table->integer('number')->comment('数量');
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
        Schema::dropIfExists('statistics');
    }
}
