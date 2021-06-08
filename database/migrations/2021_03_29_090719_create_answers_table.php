<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
/* Run the migrations.
*
* @return void
*/
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('question_id')
                ->constrained('questions')
                ->onDelete('cascade');

            $table->foreignId('answer_form_id')
                ->constrained('answer_forms')
                ->onDelete('cascade');

            $table->string('answer');
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

/* Reverse the migrations.
*
* @return void
*/
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
