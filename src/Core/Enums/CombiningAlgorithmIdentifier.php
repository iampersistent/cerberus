<?php
declare(strict_types = 1);

namespace Cerberus\Core\Enums;

use MabeEnum\Enum;

class CombiningAlgorithmIdentifier extends Enum
{
    // from XACML.java and XACML3.java

    const DENY_OVERRIDES = 'rule-combining-algorithm:deny-overrides';
    const DENY_UNLESS_PERMIT = 'rule-combining-algorithm:deny-unless-permit';
}
