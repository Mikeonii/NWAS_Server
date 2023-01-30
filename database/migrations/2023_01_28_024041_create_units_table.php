<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->string('unit_type');
            $table->string('unit_brand');
            $table->string('unit_model');
            $table->string('serial_no');
            $table->date('date_received');
            $table->date('picked_up_date')->nullable();
            $table->string('picked_up_by')->nullable();
            $table->json('issued_warranty')->nullable();
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
        Schema::dropIfExists('units');
    }
}
