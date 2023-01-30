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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->require();
            $table->string('customer_contact_no')->require();
            $table->string('other_contact_platform')->nullable();
            $table->string('customer_municipality')->nullable();;
            $table->string('customer_barangay')->nullable();;
            $table->string('customer_purok')->nullable();;
            $table->string('where_did_you_find_us')->nullable();;
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
        Schema::dropIfExists('customers');
    }
};
