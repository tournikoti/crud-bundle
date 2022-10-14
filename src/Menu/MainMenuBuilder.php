<?php

namespace Tournikoti\CrudBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MainMenuBuilder
{
    private array $sidebarItems;

    public function __construct(private FactoryInterface $factory, array $options = [])
    {
        $this->sidebarItems = $options['sidebar'] ?? [];
    }

    public function createtopbarMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root', [
            'childrenAttributes' => [
                'class' => 'navbar-nav ml-auto'
            ],
        ]);

        $action = $menu->addChild('Action', [
            'uri' => '#',
            'childrenAttributes' => [
                'class' => 'dropdown-menu dropdown-menu-end'
            ],
            'attributes' => [
                'class' => 'nav-item dropdown'
            ],
            'linkAttributes' => [
                'class' => 'nav-link dropdown-toggle',
                'id' => 'action-dropdown',
                'role' => 'button',
                'data-bs-toggle' => 'dropdown',
                'aria-expanded' => 'false'
            ]
        ]);

        $action->addChild('Profile', [
            'route' => 'app_security_logout',
            'linkAttributes' => ['class' => 'dropdown-item']
        ]);

        $action->addChild('divider', [
            'attributes' => [
                'class' => 'dropdown-divider'
            ],
        ]);

        $action->addChild('Logout', [
            'route' => 'app_security_logout',
            'linkAttributes' => ['class' => 'dropdown-item']
        ]);

        return $menu;
    }

    public function createSidebarMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        foreach ($this->sidebarItems as $item) {
            $menu->addChild('Dashboard', [
                'route' => $item['route']
            ]);
        }
        return $menu;
    }
}