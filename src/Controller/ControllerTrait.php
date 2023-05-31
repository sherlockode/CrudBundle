<?php

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

    /**
     * @param string $route
     * @param array  $parameters
     * @param int    $referenceType
     *
     * @return string
     */
    protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }

    /**
     * @param string $url
     * @param int    $status
     *
     * @return RedirectResponse
     */
    protected function redirect(string $url, int $status = 302): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * @param string $route
     * @param array  $parameters
     * @param int    $status
     *
     * @return RedirectResponse
     */
    protected function redirectToRoute(string $route, array $parameters = [], int $status = 302): RedirectResponse
    {
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }

    /**
     * @param $attributes
     * @param $subject
     *
     * @return bool
     */
    protected function isGranted($attributes, $subject = null): bool
    {
        return $this->csrfTokenManager->isGranted($attributes, $subject);
    }

    /**
     * @param string $view
     * @param array  $parameters
     *
     * @return string
     */
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
     * @param string        $view
     * @param array         $parameters
     * @param Response|null $response
     *
     * @return Response
     */
    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $content = $this->renderView($view, $parameters);
        $response ??= new Response();

        if (200 === $response->getStatusCode()) {
            foreach ($parameters as $v) {
                if ($v instanceof FormInterface && $v->isSubmitted() && !$v->isValid()) {
                    $response->setStatusCode(422);
                    break;
                }
            }
        }

        $response->setContent($content);

        return $response;
    }

    /**
     * @param string     $type
     * @param mixed|null $data
     * @param array      $options
     *
     * @return FormInterface
     */
    protected function createForm(string $type, mixed $data = null, array $options = []): FormInterface
    {
        return $this->formFactory->create($type, $data, $options);
    }

    /**
     * @param string      $id
     * @param string|null $token
     *
     * @return bool
     */
    protected function isCsrfTokenValid(string $id, #[\SensitiveParameter] ?string $token): bool
    {
        return $this->csrfTokenManager->isTokenValid(new CsrfToken($id, $token));
    }

    /**
     * @param string          $message
     * @param \Throwable|null $previous
     *
     * @return AccessDeniedException
     */
    protected function createAccessDeniedException(string $message = 'Access Denied.', \Throwable $previous = null): AccessDeniedException
    {
        if (!class_exists(AccessDeniedException::class)) {
            throw new \LogicException('You cannot use the "createAccessDeniedException" method if the Security component is not available. Try running "composer require symfony/security-bundle".');
        }

        return new AccessDeniedException($message, $previous);
    }

    /**
     * @param Router $router
     *
     * @return $this
     */
    public function setRouter(Router $router): self
    {
        $this->router = $router;

        return $this;
    }

    /**
     * @param CsrfTokenManagerInterface $csrfTokenManager
     *
     * @return $this
     */
    public function setCsrfTokenManager(CsrfTokenManagerInterface $csrfTokenManager): self
    {
        $this->csrfTokenManager = $csrfTokenManager;

        return $this;
    }

    /**
     * @param Environment $twig
     *
     * @return $this
     */
    public function setTwig(Environment $twig): self
    {
        $this->twig = $twig;

        return $this;
    }

    /**
     * @param FormFactory $formFactory
     *
     * @return $this
     */
    public function setFormFactory(FormFactory $formFactory): self
    {
        $this->formFactory = $formFactory;

        return $this;
    }
}
