<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verification_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id', unsigned: true);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('phone_number', 14)->unique();
            $table->string('telegram_login', 30);
            $table->tinyInteger('status_id', unsigned: true);

            # Foreign keys

            $table->foreign('user_id')
                ->cascadeOnDelete()
                ->references('id')
                ->on('users');

            $table->foreign('status_id')
                ->references('id')
                ->on('verification_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verification_requests');
    }
}
