<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Twig;

use Twig\Attribute\AsTwigFunction;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Sherlockode\CrudBundle\Field\FieldInterface;
use Sherlockode\CrudBundle\Grid\Field;
use Sherlockode\CrudBundle\Grid\Filter;
use Sherlockode\CrudBundle\Grid\GridView;
use Sherlockode\CrudBundle\Renderer\TwigRenderer;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class CrudExtension
{
    public function __construct(
        private readonly Environment $env,
        private readonly TwigRenderer $twigRenderer
    ) {
    }

    /**
     * @param             $params
     *
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    #[AsTwigFunction(name: 'sherlockode_crud_render_grid', isSafe: ['html'])]
    public function renderGrid(GridView $gridView, $params = [], ?string $template = null): string
    {
        return $this->twigRenderer->renderGrid($gridView, $params, $template);
    }

    /**
     * @param                $data
     *
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    #[AsTwigFunction(name: 'sherlockode_crud_render_field', isSafe: ['html'])]
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
     *
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    #[AsTwigFunction(name: 'sherlockode_crud_render_filter', isSafe: ['html'])]
    public function renderFilter(Filter $filter, Request $request): string
    {
        return $this->twigRenderer->renderFilter($filter, $request);
    }
}
