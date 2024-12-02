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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->length('155');
            $table->string('name')->length('155');
            $table->string('phone')->length('55');
            $table->string('email')->length('55');
            $table->integer('district')->nullable();
            $table->integer('area')->nullable();
            $table->string('address')->nullable();
            $table->string('frontnid')->default('public/uploads/default/user.png');
            $table->string('backnid')->default('public/uploads/default/user.png');
            $table->string('affiliate_request')->length('55');
            $table->string('status')->length('55');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
