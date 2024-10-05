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
        Schema::table('clients', function (Blueprint $table) {
            $table->foreignId('country_id')->index()->nullable()->constrained(table: 'countries')->onDelete('cascade');
            $table->foreignId('state_id')->index()->nullable()->constrained(table: 'states')->onDelete('cascade');
            $table->foreignId('city_id')->index()->nullable()->constrained(table: 'cities')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['country_id']); // Drop foreign key
            $table->dropColumn('country_id');     // Drop the column
            $table->dropForeign(['state_id']); // Drop foreign key
            $table->dropColumn('state_id');     // Drop the column
            $table->dropForeign(['city_id']); // Drop foreign key
            $table->dropColumn('city_id');     // Drop the column
        });
    }
};
