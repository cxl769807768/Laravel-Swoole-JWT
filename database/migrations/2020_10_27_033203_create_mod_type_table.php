<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mod_type', function (Blueprint $table) {
            $table->id();
            $table->char('name',10)->index('index_name');
            $table->char('icon',20);
            $table->tinyInteger('isShow')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->integer('pid')->default(0);
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
        Schema::dropIfExists('mod_type');
    }
}
