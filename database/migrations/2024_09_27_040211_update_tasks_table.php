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
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->date('start_date')->nullable();
            $table->string('estimated_time')->nullable();
            $table->enum('status', ['todo', 'in_progress', 'completed', 'qa', 'bug_fixes', 'paused'])->default('todo');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'estimated_time', 'status', 'user_id']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
        });
    }
};
