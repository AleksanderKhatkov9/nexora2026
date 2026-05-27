<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_integrations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('driver')->unique();
            $table->string('name');
            $table->boolean('enabled')->default(false);
            $table->text('credentials')->nullable();
            $table->json('settings')->nullable();
            $table->timestamp('last_tested_at')->nullable();
            $table->string('last_test_status', 32)->nullable();
            $table->text('last_test_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_integrations');
    }
};
