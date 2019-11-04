<?php

namespace App\Constants;

class ConstPolicy
{
    const ACTION_CREATE = 'create';
    const ACTION_READ = 'read';
    const ACTION_USE = 'use';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';

    const ACTIONS = [
        self::ACTION_CREATE,
        self::ACTION_READ,
        self::ACTION_USE,
        self::ACTION_UPDATE,
        self::ACTION_DELETE,
    ];

    const REL_MY = 'my';
    const REL_MODERATED = 'moderated';
    const REL_ANY = 'any';

    const RELATIONS = [
        self::REL_MY,
        self::REL_MODERATED,
        self::REL_ANY,
    ];
}
