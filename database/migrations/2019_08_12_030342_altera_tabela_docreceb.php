<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlteraTabelaDocreceb extends Migration
{
    public function up()
    {
        Schema::table("docreceb", function (Blueprint $table) {
            $table->bigInteger("nrcic")->change();
        });
    }

    public function down()
    {
        Schema::table("docreceb", function (Blueprint $table) {
            $table->dropColumn("nrcic");
        });
    }
}
