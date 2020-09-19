<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWatermarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('watermarks', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('position')->default('center');
            $table->string('offset_space')->default('15');
            $table->string('image_width')->default('600');
            $table->string('image_opacity')->default('50');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('media_file_id')->nullable();
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('watermarks');
    }
}
