<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('show_in_menu')->default(false)->after('active');
            $table->unsignedInteger('menu_order')->default(0)->after('show_in_menu');
            $table->string('menu_label')->nullable()->after('menu_order');
            $table->string('menu_type')->default('route')->after('menu_label');
            $table->string('menu_hash')->nullable()->after('menu_type');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'show_in_menu',
                'menu_order',
                'menu_label',
                'menu_type',
                'menu_hash',
            ]);
        });
    }
};
