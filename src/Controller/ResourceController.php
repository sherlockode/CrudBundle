<?php

namespace Sherlockode\CrudBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sherlockode\CrudBundle\Grid\GridBuilder;
use Sherlockode\CrudBundle\Grid\GridView;
use Sherlockode\CrudBundle\Provider\DataProvider;
use Sherlockode\CrudBundle\Routing\Utils;
use Sherlockode\CrudBundle\View\ViewBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResourceController
{
    use ControllerTrait;

    /**
     * @var GridBuilder
     */
    private $gridBuilder;

    /**
     * @var ViewBuilder
     */
    private $viewBuilder;

    /**
     * @var DataProvider
     */
    private $dataProvider;

    /**
     * @var string
     */
    private $gridName;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     */
    private $resourceClass;

    /**
     * @var string
     */
    private $form;

    /**
     * @param GridBuilder            $gridBuilder
     * @param ViewBuilder            $viewBuilder
     * @param DataProvider           $dataProvider
     * @param EntityManagerInterface $em
     * @param string                 $gridName
     * @param string                 $class
     * @param string                 $form
     */
    public function __construct(GridBuilder $gridBuilder, ViewBuilder $viewBuilder, DataProvider $dataProvider, EntityManagerInterface $em, string $gridName, string $class, string $form)
    {
        $this->gridBuilder = $gridBuilder;
        $this->viewBuilder = $viewBuilder;
        $this->dataProvider = $dataProvider;
        $this->gridName = $gridName;
        $this->em = $em;
        $this->resourceClass = $class;
        $this->form = $form;
    }

    /**
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $this->grantedOrForbidden($request, 'index');

        $grid = $this->gridBuilder->build($this->gridName);
        $data = $this->dataProvider->getData($grid, $request);

        $gridView = new GridView($data, $grid);

        return $this->render($this->getTemplate($request) ?? '@SherlockodeCrud/crud/index.html.twig', [
            'gridView' => $gridView,
            'vars' => $request->attributes->get('_crud')['vars'] ?? []
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function showAction(Request $request): Response
    {
        $resource = $this->findEntityOr404($request);
        $this->grantedOrForbidden($request, 'show', $resource);

        $view = $this->viewBuilder->build($this->gridName);

        return $this->render($this->getTemplate($request) ?? '@SherlockodeCrud/crud/show.html.twig', [
            'resource' => $resource,
            'showView' => $view,
            'vars' => $request->attributes->get('_crud')['vars'] ?? []
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $resource = new $this->resourceClass;
        $this->grantedOrForbidden($request, 'create', $resource);

        $form = $this->createForm($this->form, $resource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($resource);
            $this->em->flush();

            return $this->generateRedirection($request, $resource);
        }

        return $this->render($this->getTemplate($request) ?? '@SherlockodeCrud/crud/create.html.twig', [
            'form' => $form->createView(),
            'vars' => $request->attributes->get('_crud')['vars'] ?? []
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function updateAction(Request $request): Response
    {
        $resource = $this->findEntityOr404($request);
        $this->grantedOrForbidden($request, 'edit', $resource);

        $form = $this->createForm($this->form, $resource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($resource);
            $this->em->flush();

            return $this->generateRedirection($request, $resource);
        }

        return $this->render($this->getTemplate($request) ?? '@SherlockodeCrud/crud/edit.html.twig', [
            'form' => $form->createView(),
            'vars' => $request->attributes->get('_crud')['vars'] ?? []
        ]);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request): RedirectResponse
    {
        $resource = $this->findEntityOr404($request);
        $this->grantedOrForbidden($request, 'delete', $resource);

        $submittedToken = $request->request->get('_csrf_token');
        $isValidToken = $this->isCsrfTokenValid($resource->getId(), $submittedToken);

        if (!$isValidToken) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Invalid csrf token.');
        }

        $grid = $this->gridBuilder->build($this->gridName);
        if ($grid->hasDeleteConfirmation()) {
            return $this->redirectToRoute(
                Utils::generatePathName($request->attributes->get('_route'), 'deleteconfirmation'),
                ['id' => $resource->getId()]
            );
        }

        $this->em->remove($resource);
        $this->em->flush();

        return $this->generateRedirectionToIndex($request);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function deleteConfirmationAction(Request $request): Response
    {
        $resource = $this->findEntityOr404($request);
        $this->grantedOrForbidden($request, 'delete', $resource);

        $submittedToken = $request->request->get('_csrf_token');
        $isValidToken = $this->isCsrfTokenValid($resource->getId(), $submittedToken);

        if ($isValidToken) {
            $this->em->remove($resource);
            $this->em->flush();

            return $this->generateRedirectionToIndex($request);
        }

        return $this->render('@SherlockodeCrud/crud/delete_confirmation.html.twig', [
            'resource' => $resource,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return object
     */
    private function findEntityOr404(Request $request)
    {
        $resource = $this->em->getRepository($this->resourceClass)->find($request->attributes->get('id'));

        if (null === $resource) {
            throw new NotFoundHttpException('Resource not found');
        }

        return $resource;
    }

    /**
     * @param Request $request
     * @param mixed   $resource
     *
     * @return RedirectResponse
     */
    private function generateRedirection(Request $request, $resource): RedirectResponse
    {
        $route = $request->attributes->get('_crud')['redirect'];
        $route = explode('_', $route);
        $routeAction = $route[count($route) - 1];

        if ('index' === $routeAction || 'create' === $routeAction) {
            return $this->redirectToRoute($request->attributes->get('_crud')['redirect']);
        }

        return $this->redirectToRoute($request->attributes->get('_crud')['redirect'], ['id' => $resource->getId()]);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    private function generateRedirectionToIndex(Request $request): RedirectResponse
    {
        return $this->redirectToRoute(Utils::generatePathName($request->attributes->get('_route'), 'index'));
    }

    /**
     * @param Request $request
     *
     * @return string|null
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getTemplate(Request $request): ?string
    {
        if ($request->attributes->has('_crud')) {
            if (!isset($request->attributes->get('_crud')['template'])) {
                return null;
            }

            $crudParameters = $request->attributes->get('_crud');

            if (!$this->container->has('twig')) {
                throw new \LogicException('Twig is required. Try running "composer require symfony/twig-bundle".');
            }

            return $this->container->get('twig')->getLoader()->exists($crudParameters['template'])
                ? $crudParameters['template']
                : null;
        }

        return null;
    }

    /**
     * @param Request $request
     * @param string  $action
     * @param         $resource
     *
     * @return void
     */
    private function grantedOrForbidden(Request $request, string $action, $resource = null)
    {
        if ($request->attributes->get('_crud')['permission']) {
            if (!$this->isGranted($request->attributes->get('_crud')['resource_name'].'_'.$action, $resource)) {
                throw $this->createAccessDeniedException();
            }
        }
    }
}
