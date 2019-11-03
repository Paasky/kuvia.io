<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUploadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploaders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ip', 255)->index('ip');
            $table->string('user_agent', 255)->index('user_agent');
            $table->bigInteger('user_id')->unsigned()->nullable()->index('user_id');
            $table->integer('banned_by')->unsigned()->nullable()->index('banned_by');
            $table->timestamp('banned_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['ip', 'user_agent'], 'ip_user_agent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploaders');
    }
}
