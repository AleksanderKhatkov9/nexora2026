<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->normalizeExistingData();

        Schema::table('user_roles', function (Blueprint $table) {
            $table->unique('title');
        });

        DB::statement('ALTER TABLE pages MODIFY slug VARCHAR(255) NOT NULL');

        Schema::table('pages', function (Blueprint $table) {
            $table->index(['active', 'show_in_menu', 'menu_order', 'id'], 'pages_menu_lookup_index');
            $table->index(['active', 'show_in_footer', 'footer_group', 'footer_order', 'id'], 'pages_footer_lookup_index');
            $table->index(['active', 'menu_type', 'slug'], 'pages_sitemap_lookup_index');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->index(['active', 'year', 'id'], 'projects_listing_index');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->index(['kind', 'active', 'published_at', 'id'], 'blog_posts_feed_index');
        });

        DB::statement('ALTER TABLE orders MODIFY name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE orders MODIFY phone VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE orders MODIFY email VARCHAR(255) NOT NULL');

        Schema::table('orders', function (Blueprint $table) {
            $table->index('status');
            $table->index('channel');
            $table->index('created_at');
        });

        $this->addCheckConstraints();
    }

    public function down(): void
    {
        $this->dropCheckConstraints();

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['channel']);
            $table->dropIndex(['created_at']);
        });

        DB::statement('ALTER TABLE orders MODIFY name VARCHAR(255) NULL');
        DB::statement('ALTER TABLE orders MODIFY phone VARCHAR(255) NULL');
        DB::statement('ALTER TABLE orders MODIFY email VARCHAR(255) NULL');

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropIndex('blog_posts_feed_index');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex('projects_listing_index');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex('pages_menu_lookup_index');
            $table->dropIndex('pages_footer_lookup_index');
            $table->dropIndex('pages_sitemap_lookup_index');
        });

        DB::statement('ALTER TABLE pages MODIFY slug VARCHAR(255) NULL');

        Schema::table('user_roles', function (Blueprint $table) {
            $table->dropUnique(['title']);
        });
    }

    private function normalizeExistingData(): void
    {
        DB::table('pages')
            ->whereNull('slug')
            ->update(['slug' => DB::raw("CONCAT('page-', id)")]);

        DB::table('orders')
            ->whereNull('name')
            ->update(['name' => 'Без имени']);

        DB::table('orders')
            ->whereNull('phone')
            ->update(['phone' => 'Не указан']);

        DB::table('orders')
            ->whereNull('email')
            ->update(['email' => DB::raw("CONCAT('order-', id, '@example.invalid')")]);

        DB::table('orders')
            ->whereNotIn('status', ['new', 'in_progress', 'done', 'cancelled'])
            ->update(['status' => 'new']);

        DB::table('orders')
            ->whereNotNull('channel')
            ->whereNotIn('channel', ['email', 'phone', 'telegram', 'viber'])
            ->update(['channel' => null]);

        DB::table('blog_posts')
            ->whereNotIn('kind', ['news', 'article'])
            ->update(['kind' => 'article']);

        DB::table('pages')
            ->whereNotIn('menu_type', ['route', 'anchor', 'external'])
            ->update(['menu_type' => 'route']);

        DB::table('pages')
            ->whereNotNull('footer_group')
            ->whereNotIn('footer_group', ['sections', 'services', 'legal'])
            ->update(['footer_group' => null]);

        DB::table('api_integrations')
            ->whereNotNull('last_test_status')
            ->whereNotIn('last_test_status', ['success', 'failed'])
            ->update(['last_test_status' => null]);
    }

    private function addCheckConstraints(): void
    {
        DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_status_check CHECK (status IN ('new', 'in_progress', 'done', 'cancelled'))");
        DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_channel_check CHECK (channel IS NULL OR channel IN ('email', 'phone', 'telegram', 'viber'))");
        DB::statement("ALTER TABLE blog_posts ADD CONSTRAINT blog_posts_kind_check CHECK (kind IN ('news', 'article'))");
        DB::statement("ALTER TABLE pages ADD CONSTRAINT pages_menu_type_check CHECK (menu_type IN ('route', 'anchor', 'external'))");
        DB::statement("ALTER TABLE pages ADD CONSTRAINT pages_footer_group_check CHECK (footer_group IS NULL OR footer_group IN ('sections', 'services', 'legal'))");
        DB::statement("ALTER TABLE api_integrations ADD CONSTRAINT api_integrations_last_test_status_check CHECK (last_test_status IS NULL OR last_test_status IN ('success', 'failed'))");
    }

    private function dropCheckConstraints(): void
    {
        DB::statement('ALTER TABLE api_integrations DROP CHECK api_integrations_last_test_status_check');
        DB::statement('ALTER TABLE pages DROP CHECK pages_footer_group_check');
        DB::statement('ALTER TABLE pages DROP CHECK pages_menu_type_check');
        DB::statement('ALTER TABLE blog_posts DROP CHECK blog_posts_kind_check');
        DB::statement('ALTER TABLE orders DROP CHECK orders_channel_check');
        DB::statement('ALTER TABLE orders DROP CHECK orders_status_check');
    }
};
