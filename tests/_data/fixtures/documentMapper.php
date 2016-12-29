<?php

$documentMapper = [
    [
        'className'               => \Test\Document::class,
        'resource:resource-id'    => 'getDocumentId',
        'resource:resource-type'  => \Test\Document::class,
        'document:document-name'  => 'getDocumentName',
        'document:client-name'    => 'getClientName',
        'document:document-owner' => 'getDocumentOwner',
    ],
];