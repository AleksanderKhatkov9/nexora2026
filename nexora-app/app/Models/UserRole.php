<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserRole extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public const ROLE_ADMIN = 'Администратор';
    public const ROLE_MODERATOR = 'Модератор';
    public const ROLE_OPERATOR = 'Оператор';
    public const ROLE_CLIENT = 'Клиент';
    public const ROLES = [self::ROLE_ADMIN, self::ROLE_MODERATOR, self::ROLE_OPERATOR, self::ROLE_CLIENT];
    public const ADMINS = [self::ROLE_ADMIN, self::ROLE_MODERATOR, self::ROLE_OPERATOR];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
