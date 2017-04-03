<?php

use Cerberus\Core\Enums\ResourceIdentifier;

return [
    [
        ResourceIdentifier::CLASS_NAME    => TestData\Document::class,
        ResourceIdentifier::RESOURCE_ID   => 'getDocumentId',
        ResourceIdentifier::RESOURCE_TYPE => TestData\Document::class,
        'document:document-name'          => 'getDocumentName',
        'document:client-name'            => 'getClientName',
        'document:document-owner'         => 'getDocumentOwner',
    ],
];