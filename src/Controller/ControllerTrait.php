<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Twig\Environment;

trait ControllerTrait
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var FormFactory
     */
    private $formFactory;

    protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }

    protected function redirect(string $url, int $status = 302): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }

    protected function redirectToRoute(string $route, array $parameters = [], int $status = 302): RedirectResponse
    {
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }

    /**
     * @param $attributes
     * @param $subject
     */
    protected function isGranted($attributes, $subject = null): bool
    {
        return $this->csrfTokenManager->isGranted($attributes, $subject);
    }

    protected function renderView(string $view, array $parameters = []): string
    {
        foreach ($parameters as $k => $v) {
            if ($v instanceof FormInterface) {
                $parameters[$k] = $v->createView();
            }
        }

        return $this->twig->render($view, $parameters);
    }

    /**
     * @param Response|null $response
     *
     */
    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $content = $this->renderView($view, $parameters);
        $response ??= new Response();

        if (Response::HTTP_OK === $response->getStatusCode()) {
            foreach ($parameters as $v) {
                if ($v instanceof FormInterface && $v->isSubmitted() && !$v->isValid()) {
                    $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
                    break;
                }
            }
        }

        $response->setContent($content);

        return $response;
    }

    /**
     * @param mixed|null $data
     *
     */
    protected function createForm(string $type, mixed $data = null, array $options = []): FormInterface
    {
        return $this->formFactory->create($type, $data, $options);
    }

    protected function isCsrfTokenValid(string|int $id, ?string $token): bool
    {
        return $this->csrfTokenManager->isTokenValid(new CsrfToken((string) $id, $token));
    }

    /**
     * @param \Throwable|null $previous
     *
     */
    protected function createAccessDeniedException(string $message = 'Access Denied.', \Throwable $previous = null): AccessDeniedException
    {
        if (!class_exists(AccessDeniedException::class)) {
            throw new \LogicException('You cannot use the "createAccessDeniedException" method if the Security component is not available. Try running "composer require symfony/security-bundle".');
        }

        return new AccessDeniedException($message, $previous);
    }

    /**
     * @return $this
     */
    public function setRouter(Router $router): self
    {
        $this->router = $router;

        return $this;
    }

    /**
     * @return $this
     */
    public function setCsrfTokenManager(CsrfTokenManagerInterface $csrfTokenManager): self
    {
        $this->csrfTokenManager = $csrfTokenManager;

        return $this;
    }

    /**
     * @return $this
     */
    public function setTwig(Environment $twig): self
    {
        $this->twig = $twig;

        return $this;
    }

    /**
     * @return $this
     */
    public function setFormFactory(FormFactory $formFactory): self
    {
        $this->formFactory = $formFactory;

        return $this;
    }
}
