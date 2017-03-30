<?php
declare(strict_types = 1);

namespace Cerberus\Core\Enums;

use MabeEnum\Enum;

class SubjectIdentifier extends Enum
{
    const SUBJECT_ID = 'subject:subject-id';
    const SUBJECT_TYPE = 'subject:subject-type';
    const ROLE_ID = 'subject:role-id';
}
