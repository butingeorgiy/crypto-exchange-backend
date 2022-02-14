<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            # User Info.

            $table->bigInteger('user_id', unsigned: true)->nullable();
            $table->string('user_full_name')->nullable();
            $table->string('user_phone_number', 14)->nullable();
            $table->string('user_email')->nullable();

            # Do not add foreign key for `direction_id`.
            # You should not rely on this field.
            $table->bigInteger('direction_id', unsigned: true);

            $table->boolean('inverted');

            # Transaction Info.

            # Do not add foreign key for `given_entity_id`.
            # You should not rely on this field.
            $table->bigInteger('given_entity_id', unsigned: true);
            $table->text('given_entity_name');
            $table->double('given_entity_amount', 12, 5, true);
            $table->double('given_entity_cost', 14, 4, true);

            # Do not add foreign key for `received_entity_id`.
            # You should not rely on this field.
            $table->bigInteger('received_entity_id', unsigned: true);
            $table->text('received_entity_name');
            $table->double('received_entity_amount', 12, 5, true);
            $table->double('received_entity_cost', 14, 4, true);
            $table->enum('type', [
                'e_money_to_crypto',
                'crypto_to_e_money',
                'crypto_to_crypto',
                'cash_to_crypto',
                'crypto_to_cash'
            ]);

            $table->tinyInteger('status_id', unsigned: true);
            $table->json('options')->nullable();
            $table->timestamps();

            # Foreign keys

            $table->foreign('status_id')
                ->references('id')
                ->on('transaction_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
