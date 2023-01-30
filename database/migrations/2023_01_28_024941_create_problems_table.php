<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problems', function (Blueprint $table) {
            $table->id();
            $table->integer('unit_id');
            $table->integer('customer_id');
            $table->string('problem_description');
            $table->JSON('actions_performed')->nullable();
            $table->JSON('recommendations')->nullable();
            $table->JSON('other_remarks')->nullable();
            $table->string('technician')->nullable();
            $table->date('repair_initialized')->nullable();
            $table->string('status')->default("Pending");
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
        Schema::dropIfExists('problems');
    }
}
