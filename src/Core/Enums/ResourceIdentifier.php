<?php
declare(strict_types = 1);

namespace Cerberus\Core\Enums;

use MabeEnum\Enum;

class ResourceIdentifier extends Enum
{
    const CLASS_NAME = 'className';
    const RESOURCE_ID = 'resource:resource-id';
    const RESOURCE_TYPE = 'resource:resource-type';
}
