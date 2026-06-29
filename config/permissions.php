<?php

return [
    'super_admin' => ['*'],

    'matron' => [
        'patients.*',
        'treatments.*',
        'antenatal.*',
        'reports.*',
    ],

    'doctor' => [
        'patients.*',
        'treatments.*',
        'antenatal.*',
        'reports.*',
    ],

    'nurse' => [
        'patients.view',
        'patients.search',
        'treatments.*',
        'antenatal.view',
    ],

    'receptionist' => [
        'patients.*',
        'finance.invoices.view',
        'finance.payments.create',
    ],

    'accountant' => [
        'finance.*',
        'reports.financial',
    ],
];
