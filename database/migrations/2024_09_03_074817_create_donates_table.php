<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donates', function (Blueprint $table) {
            $table->id();
            $table->string('address', 200)->nullable();
            $table->string('phone', 200)->nullable();
            $table->string('name', 200)->nullable();
            $table->string('note')->nullable();
            $table->decimal('amount', 15, 1)->default(0.0);
            $table->tinyInteger('payment_type')->default(2)->index()->comment("1:later ,2:cash");
            $table->tinyInteger('payment_currency')->default(1)->index()->comment("1:$ ,2:ll");
            $table->tinyInteger('whastapp')->default(1)->comment("1:no 2:yes");
            $table->integer('status')->default(2)->comment('1:pendidng 2:complete 4:conceled')->default(1)->index();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
