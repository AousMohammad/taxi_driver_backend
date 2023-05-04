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
        Schema::create('carowners', function (Blueprint $table) {
            $table->id();
            $table->string('model')->nullable();
            $table->string('location')->nullable();
            $table->tinyInteger('free')->nullable();
            $table->integer('driver_id')->unsigned();

            $table->foreign('driver_id')->references('id')->on('users')
                ->onDelete('cascade');
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
        Schema::dropIfExists('carowners');
    }
};
