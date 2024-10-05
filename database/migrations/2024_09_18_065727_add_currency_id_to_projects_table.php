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
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('currency_id')->index()->after('user_id');
            $table->unsignedBigInteger('inquiry_id')->index()->nullable()->after('currency_id');
            $table->string('estimated_time')->nullable();
            $table->foreign('currency_id')->index()
                  ->references('id')
                  ->on('currencies')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropColumn('currency_id');

            $table->dropForeign(['inquiry_id']);
            $table->dropColumn('inquiry_id');
        });
    }
};
