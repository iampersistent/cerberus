<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use MabeEnum\Enum;

class Decision extends Enum
{
    /**
     * Indicates the request is permitted
     */
    const PERMIT = "Permit";

    /**
     * Indicates the request is denied
     */
    const DENY = "Deny";

    /**
     * Indicates no decision could be reached due to a processing error
     */
    const INDETERMINATE = "Indeterminate";

    /**
     * Indicates no decision could be reached due to a processing error, but it would have been permitted had
     * the error not occurred
     */
    const INDETERMINATE_PERMIT = "Indeterminate Permit";

    /**
     * Indicates no decision could be reached due to a processing error, but it would have been denied had the
     * error not occurred.
     */
    const INDETERMINATE_DENY = "Indeterminate Deny";

    /**
     * Indicates no decision could be reached due to a processing error, but either a deny or permit would
     * have been returned had the error not occurred.
     */
    const INDETERMINATE_DENY_PERMIT = "Indeterminate Deny Permit";

    /**
     * Indicates the policy in question is not applicable to the request
     */
    const NOT_APPLICABLE = "Not Applicable";
}