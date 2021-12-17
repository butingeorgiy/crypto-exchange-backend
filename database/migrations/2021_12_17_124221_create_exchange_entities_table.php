<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_entities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alias', 16);
            $table->string('icon');
            $table->double('cost', 14, 4, true);
            $table->double('min_limit', 12, 5, true)->nullable();
            $table->double('max_limit', 12, 5, true)->nullable();
            $table->integer('no_auth_limit', unsigned: true)->nullable();
            $table->integer('no_verify_limit', unsigned: true)->nullable();
            $table->enum('type', ['cash', 'e_money', 'crypto']);
            $table->boolean('enabled')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_entities');
    }
}
