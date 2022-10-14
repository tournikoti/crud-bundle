<?php

namespace Tournikoti\CrudBundle\Security;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Tournikoti\CrudBundle\Utils\RoleNameGenerator;

class Security
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private string                                 $type,
    )
    {
    }

    public function can(string $name)
    {
        return $this->authorizationChecker->isGranted(RoleNameGenerator::generate($this->type, $name));
    }
}