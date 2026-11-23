<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Ps14 Migration',
    'description' => 'Migration of older TYPO3 projects to the ps14 template (TYPO3 v14): schema update, SQL scripts and upgrade wizards. Installed only during a migration.',
    'category' => 'misc',
    'author' => 'Christian Pschorr',
    'author_email' => 'pschorr.christian@gmail.com',
    'author_company' => '',
    'state' => 'alpha',
    'version' => '14.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '14.0.0-14.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
