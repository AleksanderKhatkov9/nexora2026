<?php

namespace App\Exceptions;

use App\Exceptions\YandexWebmasterException;
use Exception;
use Illuminate\Http\Client\Response;

class YandexWebmasterException extends Exception
{
    public static function fromResponse(Response $response): self
    {
        $payload = $response->json() ?? [];
        $code = $payload['error_code'] ?? $response->status();
        $message = $payload['error_message'] ?? $response->body();

        return new self(
            self::translateMessage((string) $code, (string) $message),
            $response->status()
        );
    }

    public static function notConfigured(): self
    {
        return new self('Яндекс.Вебмастер не настроен. Задайте YANDEX_WEBMASTER_OAUTH_TOKEN в .env.');
    }

    public static function hostNotFound(string $siteUrl): self
    {
        return new self("Сайт {$siteUrl} не найден в Яндекс.Вебмастере. Укажите YANDEX_WEBMASTER_HOST_ID вручную.");
    }

    private static function translateMessage(string $code, string $message): string
    {
        return match ($code) {
            'HOST_NOT_VERIFIED' => 'Права на сайт не подтверждены в Яндекс.Вебмастере.',
            'HOST_NOT_LOADED' => 'Данные сайта ещё не загружены в Яндекс.Вебмастер.',
            'HOST_NOT_INDEXED' => 'Сайт ещё не проиндексирован (нет Sitemap или данных).',
            'HOST_NOT_FOUND' => 'Сайт не найден в списке ваших сайтов.',
            'INVALID_USER_ID' => 'Неверный user_id. Проверьте OAuth-токен.',
            default => trim($message) !== '' ? $message : 'Ошибка API Яндекс.Вебмастера.',
        };
    }
}
