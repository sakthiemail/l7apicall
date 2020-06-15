<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthtokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauthtokens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('useridentifier');
            $table->string('accesstoken');
            $table->string('refreshtoken');
            $table->bigInteger('expirytime');
            $table->datetime('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oauthtokens');
    }
}
