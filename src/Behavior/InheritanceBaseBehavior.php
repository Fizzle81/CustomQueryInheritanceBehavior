<?php

namespace CustomQueryInheritance\Behavior;

use Propel\Generator\Model\Behavior;
use Propel\Generator\Builder\Om\ClassTools;
use Propel\Generator\Builder\Om\AbstractOMBuilder;
use Propel\Generator\Builder\Om\QueryInheritanceBuilder;

/**
 * custom base behavior for propel 2
 *
 * @author Christoph Quadt <quadt@united-domains.de>
 */
class InheritanceBaseBehavior extends Behavior {

    protected $parametes = array(
      'base' => 'default'
    );

    /**
     * get parent class for object
     *
     * @param AbstractOMBuilder $builder the builder of the object
     *
     * @return string|NULL
     */
    public function parentClass($builder) {
        if (!$builder instanceof QueryInheritanceBuilder) {
            return null;
        }
        $base_class = '';
        if (array_key_exists('base', $this->getParameters())) {
            $base_class = $this->getParameter('base');
        }
        if ($base_class === 'default' || empty($base_class)) {
            $ancestorClassName = ClassTools::classname($builder->getChild()->getAncestor());
            if ($builder->getDatabase()->hasTableByPhpName($ancestorClassName)) {
                $stub_builder = $builder->getNewStubQueryBuilder($builder->getDatabase()->getTableByPhpName($ancestorClassName));
                return $builder->getClassNameFromBuilder($stub_builder);
            }
        } else {
            $class = $builder->declareClass($base_class);
            return $class;
        }
        return null;
    }
}