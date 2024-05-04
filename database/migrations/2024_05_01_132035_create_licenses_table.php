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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->nullable();
            $table->string('license_key')->nullable();
            $table->string('purchase_code')->nullable();
            $table->decimal('amount')->default(0);
            $table->timestamp('supported_until')->nullable();
            $table->string('support_amount')->nullable();
            $table->string('buyer')->nullable();
            $table->integer('purchase_count')->default(1);
            $table->string('domain')->nullable();
            $table->string('url')->nullable();
            $table->string('ip')->nullable();
            $table->string('root_path')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
