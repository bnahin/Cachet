<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcrchsServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecrchs_services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('service_name');
            $table->unsignedInteger('component_id');
            $table->string('target');
            $table->timestamp('uptime')->nullable();
            $table->timestamp('downtime')->nullable();

            $table->foreign('component_id')->references('id')
                ->on('components')
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
        Schema::dropIfExists('ecrchs_services');
    }
}
