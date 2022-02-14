<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRateDashboardEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_dashboard_entities', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('exchange_entity_id', unsigned: true);
            $table->enum('card_color_type', [
                'yellow', 'red', 'light-blue', 'blue', 'emerald', 'green'
            ]);
            $table->boolean('visible')->default(true);

            # Foreign keys

            $table->foreign('exchange_entity_id')
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
        Schema::dropIfExists('rate_dashboard_entities');
    }
}
