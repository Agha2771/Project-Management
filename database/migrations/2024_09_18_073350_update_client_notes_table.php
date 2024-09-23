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
            if (!Schema::hasColumn('client_notes', 'inquiry_id')) {
                $table->unsignedBigInteger('inquiry_id')->nullable();
            }

            $table->foreign('inquiry_id')
                  ->references('id')
                  ->on('inquiries')
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
        Schema::table('client_notes', function (Blueprint $table) {
            $table->dropForeign(['inquiry_id']);
            $table->dropColumn('inquiry_id');
        });
    }
};
