<?php

use App\RagStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdeasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ideas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('submitter_id');
            $table->string('title');
            $table->integer('change_type_id');
            $table->integer('justification_id');
            $table->integer('impacted_supercircle_id');
            $table->integer('impacted_circle_id');
            $table->double('expected_benefit');
            $table->string('expected_benefit_type');
            $table->integer('expected_effort')->nullable();
            $table->integer('actual_effort')->nullable();
            $table->integer('rag_status_id')->default(RagStatus::$AMBER);
            $table->string('sme_id');
            $table->uuid('pending_at_id');
            $table->integer('status_id');
            $table->text('description');
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
        Schema::dropIfExists('ideas');
    }
}
