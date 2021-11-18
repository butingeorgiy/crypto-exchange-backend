<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCredentialsUpdateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_credentials_update_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('salt', 16);
            $table->bigInteger('user_id', unsigned: true);
            $table->string('email')->nullable();
            $table->char('hashed_password', 64)->nullable();

            # Foreign keys

            $table->foreign('user_id')
                ->cascadeOnDelete()
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_credentials_update_requests');
    }
}
