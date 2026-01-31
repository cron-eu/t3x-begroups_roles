<?php

use IchHabRecht\BegroupsRoles\Backend\ToolbarItems\RoleSwitcher;

return [
    'role_switch' => [
        'path' => '/role/switch',
        'target' => RoleSwitcher::class . '::switchRoleAction',
    ],
];
