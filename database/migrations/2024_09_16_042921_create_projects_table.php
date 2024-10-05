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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained('users')->onDelete('cascade');
            $table->string('title')->index();
            $table->date('start_date')->index()->nullable();
            $table->date('end_date')->index()->nullable();
            $table->enum('status', ['inquiry','not_started', 'in_progress', 'completed', 'on_hold' , 'declined'])->index()->default('inquiry');
            $table->decimal('budget', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index(['start_date' , 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
