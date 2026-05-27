<?php

namespace App\Console\Commands;

use App\Services\YandexWebmaster\YandexWebmasterService;
use Illuminate\Console\Command;

class YandexWebmasterStatusCommand extends Command
{
    protected $signature = 'yandex:webmaster:status';

    protected $description = 'Проверить подключение к API Яндекс.Вебмастера';

    public function handle(YandexWebmasterService $service): int
    {
        $status = $service->getConnectionStatus();

        $this->line('Яндекс.Вебмастер');
        $this->line('─────────────────');
        $this->line('Включено: '.($status['enabled'] ? 'да' : 'нет'));
        $this->line('Токен: '.($status['configured'] ? 'задан' : 'не задан'));

        if ($status['user_id']) {
            $this->line('User ID: '.$status['user_id']);
        }

        if ($status['host_id']) {
            $this->line('Host ID: '.$status['host_id']);
        }

        if ($status['host_url']) {
            $this->line('Сайт: '.$status['host_url']);
        }

        if ($status['verified'] !== null) {
            $this->line('Подтверждён: '.($status['verified'] ? 'да' : 'нет'));
        }

        if ($status['message']) {
            $this->warn($status['message']);

            return self::FAILURE;
        }

        if (! $service->isEnabled()) {
            $this->comment('Включите интеграцию в Nova → API-интеграции → Яндекс.Вебмастер или задайте YANDEX_WEBMASTER_* в .env');

            return self::FAILURE;
        }

        try {
            $summary = $service->getSummary();
            $this->newLine();
            $this->info('Сводка индексации');
            $this->line('ИКС: '.($summary['sqi'] ?? '—'));
            $this->line('Страниц в поиске: '.($summary['searchable_pages_count'] ?? '—'));
            $this->line('Исключено: '.($summary['excluded_pages_count'] ?? '—'));
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Подключение успешно. Дашборд: Nova → «Посещаемость (Яндекс)».');

        return self::SUCCESS;
    }
}
