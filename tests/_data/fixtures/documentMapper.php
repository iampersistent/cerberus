<?php

use Cerberus\Core\Enums\ResourceIdentifier;

return [
    [
        ResourceIdentifier::CLASS_NAME    => Test\Document::class,
        ResourceIdentifier::RESOURCE_ID   => 'getDocumentId',
        ResourceIdentifier::RESOURCE_TYPE => Test\Document::class,
        'document:document-name'          => 'getDocumentName',
        'document:client-name'            => 'getClientName',
        'document:document-owner'         => 'getDocumentOwner',
    ],
];