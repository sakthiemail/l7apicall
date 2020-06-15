<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('account_id');
            $table->string('account_name');
            $table->text('description')->nullable();
            $table->bigInteger('account_number')->default(0);            
            $table->string('account_phone')->nullable();
            $table->string('account_type');
            $table->string('industry');
            $table->bigInteger('employees')->nullable();
            $table->string('website')->nullable();
            $table->string('billing_street')->nullable();
            $table->string('billing_city');
            $table->string('billing_state');    
            $table->string('billing_country');
            $table->Integer('billing_code');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
