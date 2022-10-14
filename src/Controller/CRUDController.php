<?php

namespace Tournikoti\CrudBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tournikoti\CrudBundle\Admin\AdminCRUDInterface;
use Tournikoti\CrudBundle\Form\FilterType;

class CRUDController extends AbstractController
{
    protected AdminCRUDInterface $admin;

    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        if (!$this->admin->getSecurity()->can('index')) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(FilterType::class);

        $form->handleRequest($request);

        $query = $this->admin->getQueryFilter($request, $form->getData());

        $entities = $paginator->paginate($query, $request->query->getInt('page', 1), 30);

        return $this->render($this->admin->getTemplateRegistry()->getTemplate('index')->getPath(), $this->admin->getExtraParameters([
            'title' => $this->admin->getTemplateRegistry()->getTemplate('index')->getTitle(),
            'entities' => $entities,
            'form' => $form->createView(),
        ]));
    }

    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->admin->getSecurity()->can('new')) {
            throw new AccessDeniedException();
        }

        $entity = $this->admin->createEntity();

        $form = $this->createForm($this->admin->getFormType(), $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirectToRoute($this->admin->getRouter()->getRoute('index')->getName(), [], Response::HTTP_SEE_OTHER);
        }

        return $this->render($this->admin->getTemplateRegistry()->getTemplate('new')->getPath(), $this->admin->getExtraParameters([
            'title' => $this->admin->getTemplateRegistry()->getTemplate('new')->getTitle(),
            'entity' => $entity,
            'form' => $form->createView(),
        ]));
    }

    public function show(int $id): Response
    {
        if (!$this->admin->getSecurity()->can('show')) {
            throw new AccessDeniedException();
        }

        $entity = $this->admin->getEntity($id);

        return $this->render($this->admin->getTemplateRegistry()->getTemplate('show')->getPath(), $this->admin->getExtraParameters([
            'title' => $this->admin->getTemplateRegistry()->getTemplate('show')->getTitle(),
            'entity' => $entity,
        ]));
    }

    public function edit(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        if (!$this->admin->getSecurity()->can('edit')) {
            throw new AccessDeniedException();
        }

        $entity = $this->admin->getEntity($id);

        $form = $this->createForm($this->admin->getFormType(), $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute($this->admin->getRouter()->getRoute('index')->getName(), [], Response::HTTP_SEE_OTHER);
        }

        return $this->render($this->admin->getTemplateRegistry()->getTemplate('edit')->getPath(), $this->admin->getExtraParameters([
            'title' => $this->admin->getTemplateRegistry()->getTemplate('edit')->getTitle(),
            'entity' => $entity,
            'form' => $form->createView(),
        ]));
    }

    public function delete(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        if (!$this->admin->getSecurity()->can('delete')) {
            throw new AccessDeniedException();
        }

        $entity = $this->admin->getEntity($id);

        if ($request->getMethod() == Request::METHOD_POST) {
            if ($this->isCsrfTokenValid('delete' . $entity->getId(), $request->request->get('_token'))) {
                $entityManager->remove($entity);
                $entityManager->flush();
            }

            return $this->redirectToRoute($this->admin->getRouter()->getRoute('index')->getName(), [], Response::HTTP_SEE_OTHER);
        }

        return $this->render($this->admin->getTemplateRegistry()->getTemplate('delete')->getPath(), $this->admin->getExtraParameters([
            'title' => $this->admin->getTemplateRegistry()->getTemplate('delete')->getTitle(),
            'entity' => $entity,
        ]));
    }
}