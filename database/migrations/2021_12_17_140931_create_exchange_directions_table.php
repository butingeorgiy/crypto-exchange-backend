<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeDirectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_directions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('first_entity_id', unsigned: true);
            $table->bigInteger('second_entity_id', unsigned: true);
            $table->float('fee_coefficient', 4, 3, true)
                ->default(0);

            $table->float('inverted_fee_coefficient', 4, 3, true)
                ->default(0);

            $table->boolean('inverting_allowed');
            $table->boolean('enabled')->default(true);


            # Foreign keys

            $table->foreign('first_entity_id')
                ->references('id')
                ->on('exchange_entities');

            $table->foreign('second_entity_id')
                ->references('id')
                ->on('exchange_entities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_directions');
    }
}
