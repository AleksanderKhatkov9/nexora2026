<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('show_in_footer')->default(false)->after('show_in_menu');
            $table->string('footer_group', 50)->nullable()->after('show_in_footer');
            $table->unsignedInteger('footer_order')->default(0)->after('footer_group');
            $table->string('footer_label')->nullable()->after('footer_order');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['show_in_footer', 'footer_group', 'footer_order']);
        });
    }
};
