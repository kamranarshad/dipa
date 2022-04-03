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
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->string('code')->unique();
            $table->boolean('pending')->default(false);
            $table->string('description');
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('running_balance', 10, 2)->nullable();
            $table->string('type');
            $table->string('category');
            $table->string('name')->nullable();
            $table->json('meta');
            $table->dateTime('payment_at');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
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
