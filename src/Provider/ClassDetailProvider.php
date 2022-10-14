<?php

namespace Tournikoti\CrudBundle\Provider;

use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;

class ClassDetailProvider
{
    public function __construct(private Generator $generator)
    {
    }

    public function getAdminClassDetail(string $admin)
    {
        return $this->generator->createClassNameDetails(
            $admin,
            'Admin\\',
            'Admin'
        );
    }

    public function getControllerClassDetail(string $controller)
    {
        return $this->generator->createClassNameDetails(
            $controller,
            'Controller\\Admin\\',
            'Controller'
        );
    }

    public function getFormClassDetail(string $entity): ClassNameDetails
    {
        $i = 0;

        do {
            $formClassDetails = $this->generator->createClassNameDetails(
                $entity . ($i ?: '') . 'Type',
                'Form\\Admin\\',
                'Type'
            );
            ++$i;
        } while (class_exists($formClassDetails->getFullName()));

        return $formClassDetails;
    }
}