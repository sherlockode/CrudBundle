<?php

namespace Sherlockode\CrudBundle\Twig;

use Sherlockode\CrudBundle\Field\FieldInterface;
use Sherlockode\CrudBundle\Grid\Field;
use Sherlockode\CrudBundle\Grid\Filter;
use Sherlockode\CrudBundle\Grid\GridView;
use Sherlockode\CrudBundle\Renderer\TwigRenderer;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CrudExtension extends AbstractExtension
{
    /**
     * @var Environment
     */
    private $env;

    /**
     * @var TwigRenderer
     */
    private $twigRenderer;

    /**
     * @param Environment  $twig
     * @param TwigRenderer $twigRenderer
     */
    public function __construct(Environment $twig, TwigRenderer $twigRenderer)
    {
        $this->env = $twig;
        $this->twigRenderer = $twigRenderer;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sherlockode_crud_render_grid', [$this, 'renderGrid'], ['is_safe' => ['html']]),
            new TwigFunction('sherlockode_crud_render_field', [$this, 'renderField'], ['is_safe' => ['html']]),
            new TwigFunction('sherlockode_crud_render_filter', [$this, 'renderFilter'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param GridView    $gridView
     * @param             $params
     * @param string|null $template
     *
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function renderGrid(GridView $gridView, $params = [], ?string $template = null): string
    {
        return $this->twigRenderer->renderGrid($gridView, $params, $template);
    }

    /**
     * @param FieldInterface $field
     * @param                $data
     *
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function renderField(FieldInterface $field, $data): string
    {
        if ($field->getTemplate() !== null) {
            return $this->env->render($field->getTemplate(), [
                'resource' => $this->twigRenderer->renderField($field, $data),
                'options' => $field->getOptions(),
            ]);
        }

        return $this->twigRenderer->renderField($field, $data);
    }

    /**
     * @param Filter  $filter
     * @param Request $request
     *
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function renderFilter(Filter $filter, Request $request): string
    {
        return $this->twigRenderer->renderFilter($filter, $request);
    }
}
