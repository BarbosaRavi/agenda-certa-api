<?php

namespace App\Enums;

enum UserTypeEnum: string
{
    case USER = 'user';
    case TENANT = 'tenant';
    case CLIENT = 'client';
    case SYS_ADMIN = 'sys_admin';

    public function label(): string
    {
        return match ($this) {
            static::USER => 'Usuário',
            static::TENANT => 'Prestador de Serviço',
            static::CLIENT => 'Cliente',
            static::SYS_ADMIN => 'Administrador do Sistema',
        };
    }
}