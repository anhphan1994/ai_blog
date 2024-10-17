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
        Schema::create('blog_post_parameters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blog_post_id')->nullable();
            $table->string('short_description')->nullable();
            $table->string(column: 'post_style')->nullable();
            $table->string(column: 'keywords')->nullable();
            $table->string(column: 'section_number')->nullable();
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
        Schema::dropIfExists('blog_post_parameters');
    }
};
