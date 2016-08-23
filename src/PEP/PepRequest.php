<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

class PepRequest
{
    public function getPepRequestAttributes($categoryIdentifier): PepRequestAttributes
    {
        PepRequestAttributes pepRequestAttributes = pepRequestAttributesMapByCategory.get(categoryIdentifier);
        if (pepRequestAttributes == null) {
            String xmlId = generateRequestAttributesXmlId();
            StdPepRequestAttributes p = new StdPepRequestAttributes(xmlId, categoryIdentifier);
            p.setIssuer(pepConfig.getIssuer());
            pepRequestAttributes = p;
            pepRequestAttributesMapByCategory.put(categoryIdentifier, pepRequestAttributes);
            wrappedRequest.add(pepRequestAttributes.getWrappedRequestAttributes());
        }
        return pepRequestAttributes;
    }
}