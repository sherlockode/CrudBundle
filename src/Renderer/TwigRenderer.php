<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Renderer;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Sherlockode\CrudBundle\Field\FieldInterface;
use Sherlockode\CrudBundle\Form\Type\FormTypeRegistry;
use Sherlockode\CrudBundle\Grid\Field;
use Sherlockode\CrudBundle\Grid\Filter;
use Sherlockode\CrudBundle\Grid\GridView;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Twig\Environment;

class TwigRenderer
{
    public function __construct(
        private readonly Environment $env,
        private readonly PropertyAccessorInterface $propertyAccessor,
        private readonly FormFactoryInterface $formFactory,
    ) {
    }

    /**
     * @param                $data
     * @return mixed
     */
    public function renderField(FieldInterface $field, object|array $data)
    {
        if ('.' === $field->getPath()) {
            return $data;
        }

        try {
            return $this->propertyAccessor->getValue($data, $field->getPath());
        } catch (\Exception) {
            return '';
        }
    }

    /**
     * @param array       $params
     *
     * @return string
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderGrid(GridView $gridView, $params = [], ?string $template = null): string
    {
        return $this->env->render(
            $template ?: '@SherlockodeCrud/common/grid/grid.html.twig',
            ['gridView' => $gridView] + $params
        );
    }

    public function renderFilter(Filter $filter, Request $request): string
    {
        $form = $this->formFactory->createNamed('criteria', FormType::class, [], [
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            'required' => false,
        ]);

        $form->add($filter->getName(), $filter->getFilterType(), array_merge(['label' => false], $filter->getOptions()));
        $form->submit($request->query->all('criteria'));

        return $this->env->render($filter->getTemplate(), [
            'form' => $form->get($filter->getName())->createView(),
            'filter' => $filter,
        ]);
    }
}
