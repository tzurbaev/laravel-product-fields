<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fieldables', function (Blueprint $table) {
            $table->unsignedInteger('field_id')->index();
            $table->unsignedInteger('field_value_id')->index();
            $table->string('fieldable_type')->index();
            $table->unsignedInteger('fieldable_id')->index();

            $table->unique(['field_id', 'field_value_id', 'fieldable_type', 'fieldable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fieldables');
    }
}
