<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\PDP\Utility\Properties;

class PepConfig
{
    public function __construct(Properties $properties)
    {
        $this->properties = $properties;
    }

//    /**
//     * @return
//     */
//String getIssuer();
//
//    /**
//     * @return
//     */
//String getDefaultSubjectId();
//
//    /**
//     * @return
//     */
//String getDefaultResourceId();
//
//    /**
//     * @return
//     */
//String getDefaultActionId();
//
//    /**
//     * @return
//     */
//PepResponseBehavior getIndeterminateBehavior();
//
//    /**
//     * @return
//     */
//PepResponseBehavior getNotApplicableBehavior();
//
//    /**
//     * @return
//     */
//List<String> getMapperClassNames();
}