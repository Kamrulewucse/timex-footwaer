<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartyLessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('party_lesses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('company_branch_id');
            $table->unsignedInteger('customer_id')->nullable();
            $table->tinyInteger('transaction_method')->comment('1=Cash; 2=Bank;');
            $table->float('amount', 20);
            $table->date('date');
            $table->string('note')->nullable();
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
        Schema::dropIfExists('party_lesses');
    }
}
