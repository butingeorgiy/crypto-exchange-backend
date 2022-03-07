<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColorToExchangeEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange_entities', function (Blueprint $table) {
            $table->enum('color_card_type', [
                'yellow', 'red', 'light-blue', 'blue', 'emerald', 'green'
            ])->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exchange_entities', function (Blueprint $table) {
            $table->dropColumn('color_card_type');
        });
    }
}
