<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlteraTabelaCliente extends Migration
{
    public function up()
    {
        Schema::table("cliente", function (Blueprint $table) {
            $table->bigInteger("nrcic")->change();
        });
    }

    public function down()
    {
        Schema::table("cliente", function (Blueprint $table) {
            $table->dropColumn("nrcic");
        });
    }
}
