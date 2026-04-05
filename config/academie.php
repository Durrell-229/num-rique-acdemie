<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Codes d'accès
    |--------------------------------------------------------------------------
    | admin_code      → réservé aux administrateurs
    | enseignant_code → réservé aux enseignants
    | Ces codes doivent être définis dans le .env
    */
    'admin_code'      => env('ADMIN_CODE',      'ADMIN2025'),
    'enseignant_code' => env('ENSEIGNANT_CODE',  'ENSEIGNANT2025'),

    /*
    |--------------------------------------------------------------------------
    | FedaPay
    |--------------------------------------------------------------------------
    */
    'fedapay_public_key' => env('FEDAPAY_PUBLIC_KEY', 'pk_live_X_DmtE7HnbtVA7i1nmjgcXJ0'),
];
