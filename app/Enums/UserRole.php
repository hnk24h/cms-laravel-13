<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin       = 'admin';
    case Editor      = 'editor';
    case Contributor = 'contributor';

    public function label(): string
    {
        return match ($this) {
            self::Admin       => 'Admin',
            self::Editor      => 'Người tạo bài',
            self::Contributor => 'Cộng tác viên',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $role) => [$role->value => $role->label()])
            ->all();
    }
}
