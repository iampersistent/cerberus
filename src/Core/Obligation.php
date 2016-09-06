<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Ds\Map;

class Obligation
{
    protected $map;

    public function __construct()
    {
        $this->map = new Map();
    }

public function getAttributeMap(): Map {
//Map<String, List<Object>> map = new HashMap<String, List<Object>>();
//for (AttributeAssignment a : wrappedObligation.getAttributeAssignments()) {
//String attributeId = a.getAttributeId().stringValue();
//List<Object> values = map.get(attributeId);
//if (values == null) {
//values = new ArrayList<Object>();
//map.put(attributeId, values);
//}
//values.add(a.getAttributeValue().getValue());
//}

    $attributeMap = new Map();
        foreach ($this->map->pairs() as $pair) {
            $attributeMap->put($pair->key, $pair->value); // e.getValue().toArray(new Object[1]));
}
        return $attributeMap;
    }

    public function getId()
    {
        return $this->id;
    }
}