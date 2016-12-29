<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

class ActionMapper extends CategoryContainerMapper
{
    protected $className = Action::class;

    protected function resolveAttributeId(string $attributeId): string
    {
//        if (attributeId.equals(Action.ACTION_ID_KEY)) {
//            return getPepConfig().getDefaultActionId();
//        }
        return $attributeId;
    }
}