# Cerberus

### Installation

```
    composer require picr/cerberus
```

If you are needing to use ContentSelection to dynamically add permissions to resources, you'll
also need to create a table in your MySQL database (MySQL is currently the only supported driver). 

```mysql

CREATE TABLE `cerberus_mapped_object` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `resource_id` VARCHAR(255) NULL,
  `resource_type` VARCHAR(255) NULL,
  `subject_id` VARCHAR(255) NULL,
  `subject_type` VARCHAR(255) NULL,
  `allowed_actions` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC));

```

### Set up

The various engines and mappers need to be configured. Currently, the only allowed mapper is based on a PHP
array. 

```php
$config = [
    'factories'       => [
        'combiningAlgorithm' => \Cerberus\PDP\Policy\CombiningAlgorithmFactory::class,
        'functionDefinition' => \Cerberus\PDP\Policy\Factory\FunctionDefinitionFactory::class,
        'pdpEngine'          => \Cerberus\PDP\CerberusEngineFactory::class,
        'pipFinder'          => \Cerberus\PIP\Factory\PipFinderFactory::class,
        'policyFinder'       => \Cerberus\PDP\ArrayPolicyFinderFactory::class,
    ],
    'rootPolicies'    => [
        __DIR__ . '/policy.php',
    ],
    'pep'             => [
        'issuer'  => 'test',
        'mappers' => [
            'classes'        => [],
            'configurations' => [
            ],
        ],
    ],
    'contentSelector' => [
        'classes' => [
            'mapper'     => \Cerberus\PEP\PersistedResourceMapper::class,
            'manager'    => \Cerberus\PIP\Permission\PermissionManager::class,
            'repository' => \Cerberus\PIP\Permission\PermissionMemoryRepository::class,
        ],
        'config'  => [
            'repository' => [
                '' => '',
                '' => '',
                '' => '',
                'options' => [],
            ],
        ],
    ],
];
```

The policy is also defined with a PHP array

```php
$policy = [
    'policy' => [
        'ruleCombiningAlgorithmId' => 'rule-combining-algorithm:deny-overrides',
        'policyId'                 => 'dynamic:policy',
        'variableDefinitions'      => [
            [
                'variableId' => 'resourceMatch',
                'apply'      => [
                    'functionId' => 'function:string-equal',
                    'apply'      => [
                        [
                            'functionId'          => 'function:string-one-and-only',
                            'attributeDesignator' => [
                                'attributeId'   => 'resource:resource-type',
                                'category'      => 'attribute-category:resource',
                                'dataType'      => 'string',
                                'mustBePresent' => false,
                            ],
                        ],
                        [
                            'functionId'        => 'function:string-one-and-only',
                            'attributeSelector' => [
                                'category'          => 'attribute-category:resource',
                                'contextSelectorId' => 'content-selector',
                                'dataType'          => 'string',
                                'mustBePresent'     => false,
                                'path'              => '$.resource.type',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'variableId' => 'actionMatch',
                'apply'      => [
                    'functionId' => 'function:string-is-in',
                    'apply'      => [
                        [
                            'functionId'          => 'function:string-one-and-only',
                            'attributeDesignator' => [
                                'attributeId'   => 'action:action-id',
                                'category'      => 'attribute-category:action',
                                'dataType'      => 'string',
                                'mustBePresent' => false,
                            ],
                        ],
                        [
                            'functionId'        => 'function:string-bag',
                            'attributeSelector' => [
                                'category'          => 'attribute-category:action',
                                'contextSelectorId' => 'content-selector',
                                'dataType'          => 'string',
                                'mustBePresent'     => false,
                                'path'              => '$.actions',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'rules'                    => [
            [
                'ruleId'      => 'permission:access',
                'effect'      => 'Permit',
                'description' => 'Subject can access resource',
                'condition'   => [
                    'variableReference' => [
                        'variableId' => 'resourceMatch',
                    ],
                ],
            ],
            [
                'ruleId'      => 'permission:action',
                'effect'      => 'Permit',
                'description' => 'Subject can perform action',
                'condition'   => [
                    'variableReference' => [
                        'variableId' => 'actionMatch',
                    ],
                ],
            ],
        ],
    ],
];
```

## Usage

To grant access to a resource 

```php
$service = CerberusService($config);

$subject = new Subject('subjectId', 'subjectType'); // subjectType defaults to user
$action = new Action('read'); // can be anything
$resource = new Resource('resourceType', 'resourceId');

$service->grant($subject, $action, $resource);

```

To deny access to a resource 

```php
$service = CerberusService($config);

$subject = new Subject('subjectId');
$action = new Action('read'); 
$resource = new Resource('resourceType', 'resourceId');

$service->deny($subject, $action, $resource);

```

To check access to a resource 

```php
$service = CerberusService($config);

$subject = new Subject('subjectId', 'subjectType'); 
$action = new Action('read'); 
$resource = new Resource('resourceType', 'resourceId');

$service->can($subject, $action, $resource);

```
