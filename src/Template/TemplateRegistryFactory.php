<?php

namespace Tournikoti\CrudBundle\Template;

class TemplateRegistryFactory
{
    public function create(string $type): TemplateRegistryInterface
    {
        return (new TemplateRegistry())
            ->setTemplateIndex(new Template('@TournikotiCrud/CRUD/index.html.twig', ucfirst(strtolower($type)) . ' List'))
            ->setTemplateNew(new Template('@TournikotiCrud/CRUD/new.html.twig', ucfirst(strtolower($type)) . ' New'))
            ->setTemplateShow(new Template('@TournikotiCrud/CRUD/show.html.twig', ucfirst(strtolower($type)) . ' Show'))
            ->setTemplateEdit(new Template('@TournikotiCrud/CRUD/edit.html.twig', ucfirst(strtolower($type)) . ' Edit'))
            ->setTemplateDelete(new Template('@TournikotiCrud/CRUD/delete.html.twig', ucfirst(strtolower($type)) . ' Delete'));
    }
}