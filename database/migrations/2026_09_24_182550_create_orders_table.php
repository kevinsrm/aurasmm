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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_order_id')->nullable();
            $table->unsignedBigInteger('service_id');
            $table->string('service_name')->nullable();
            $table->text('link');
            $table->integer('quantity');
            $table->decimal('charge', 10, 4)->default(0);
            $table->string('status')->default('Pending');
            $table->text('comments')->nullable();
            $table->string('answer_number')->nullable();
            $table->string('username')->nullable();
            $table->integer('runs')->nullable();
            $table->integer('interval')->nullable();
            $table->text('api_response')->nullable();
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
