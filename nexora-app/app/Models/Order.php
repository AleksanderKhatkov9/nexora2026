<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_DONE = 'done';

    public const STATUS_CANCELLED = 'cancelled';

    public static function statuses(): array
    {
        return [
            self::STATUS_NEW => 'Новая',
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_DONE => 'Завершена',
            self::STATUS_CANCELLED => 'Отменена',
        ];
    }

    public static function channels(): array
    {
        return [
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'telegram' => 'Telegram',
            'viber' => 'Viber',
        ];
    }

    protected $fillable = [
        'name',
        'phone',
        'email',
        'message',
        'channel',
        'status',
    ];
}
