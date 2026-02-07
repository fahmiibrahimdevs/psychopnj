<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    /**
     * Role Structure for laratrust.
     *
     * Insert the roles and permissions to be created.
     */
    'roles_structure' => [
        'pengurus' => [
            'dashboard_pengurus' => 'r',
        ],
        'anggota' => [
            'dashboard_anggota' => 'r',
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
