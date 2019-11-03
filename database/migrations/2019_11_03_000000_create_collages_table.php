<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCollagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 255);
            $table->string('slug', 255)->unique()->index('slug');
            $table->string('shortcode', 8)->unique()->index('shortcode');
            $table->bigInteger('user_id')->unsigned()->nullable()->index('user_id');
            $table->boolean('is_auto_approve');

            $table->smallInteger('status')->unsigned()->nullable()->index('status');
            $table->bigInteger('moderated_by')->unsigned()->nullable();
            $table->timestamp('moderated_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collages');
    }
}
