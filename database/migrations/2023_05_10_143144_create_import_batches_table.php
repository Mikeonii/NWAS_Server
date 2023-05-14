<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_description');
            $table->integer('supplier_id');
            $table->integer('capital_amount');
            $table->integer('gross_amount')->default(0);
            $table->integer('net_amount')->default(0);
            $table->date('date_ordered');
            $table->date('date_arrived')->nullable();
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
        Schema::dropIfExists('import_batches');
    }
}
