<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('filename', 255);
            $table->string('file_hash', 255)->index('file_hash');
            $table->bigInteger('uploader_id')->unsigned()->index('uploader_id');
            $table->bigInteger('user_id')->unsigned()->nullable()->index('user_id');
            $table->bigInteger('collage_id')->unsigned()->index('collage_id');

            $table->smallInteger('status')->unsigned()->nullable()->index('status');
            $table->bigInteger('moderated_by')->unsigned()->nullable();
            $table->timestamp('moderated_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['collage_id', 'filename'], 'collage_id_filename');
            $table->unique(['collage_id', 'file_hash'], 'collage_id_file_hash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
