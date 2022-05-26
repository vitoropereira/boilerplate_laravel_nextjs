<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('tour_destination_uuid');
            $table->foreignId('featured_image_id')->index()->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description', 450)->nullable();
            $table->boolean('available')->default(true);
            $table->string('meta_title', 450);
            $table->string('meta_description', 450)->nullable();
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
        Schema::dropIfExists('products');
    }
};
