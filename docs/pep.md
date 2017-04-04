# Cerberus

## Policy Enforcement Point (PEP)



### Configuration

Policy Mappers can be either a `ObjectMapper class`, or a `mapper configuration`.

```php
[config.php]

return [
    // ...
    'pep'             => [
        'issuer'  => 'test', // TODO: not sure what this is...
        'mappers' => [
            'classes'        => [
                ResourceMapper::class, // TODO: not sure how this works yet...
            ],
            'configurations' => [
                '/path/to/documentMapper.php',
            ],
        ],
    ],
    // ...
];
```

The policy is also defined with a PHP array.

* Mapped properties reference specific getters on the class that return that property value.
* Required attributes are `className`, `resource:resource-id`, and `resource:resource-type`.
* Attach additional properties using `resourceType:propertyName => accessorMethod`

```php
[documentMapper.php]

return [
    'className'               => TestData\Document::class,
    'resource:resource-id'    => 'getDocumentId',
    'resource:resource-type'  => TestData\Document::class,
    'document:document-name'  => 'getDocumentName',
    'document:client-name'    => 'getClientName',
    'document:document-owner' => 'getDocumentOwner',
];
```