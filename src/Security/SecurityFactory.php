<?php

namespace Tournikoti\CrudBundle\Security;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SecurityFactory
{
    public function __construct(private readonly AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    public function create(string $type): Security
    {
        return new Security($this->authorizationChecker, $type);
    }
}