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
        Schema::table('client_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('inquiry_id')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_notes', function (Blueprint $table) {
            $table->dropForeign(['inquiry_id']);
            $table->dropColumn('inquiry_id');
        });
    }
};
