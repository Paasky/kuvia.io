<?php

namespace App\Constants;

class ConstPermission
{
    const ANYTHING = 'anything';
    const ANYTHING_MINE = 'anything mine';

    const ACTION_CREATE = 'create';
    const ACTION_READ = 'read';
    const ACTION_USE = 'use';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';
    const ACTION_MODERATE = 'moderate';

    const ACTIONS = [
        self::ACTION_CREATE,
        self::ACTION_READ,
        self::ACTION_USE,
        self::ACTION_UPDATE,
        self::ACTION_DELETE,
        self::ACTION_MODERATE,
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
