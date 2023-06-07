<?php

namespace Sherlockode\CrudBundle\Renderer;

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
    /**
     * @var Environment
     */
    private $env;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param Environment               $twig
     * @param PropertyAccessorInterface $propertyAccessor
     * @param FormFactoryInterface      $formFactory
     */
    public function __construct(Environment $twig, PropertyAccessorInterface $propertyAccessor, FormFactoryInterface $formFactory)
    {
        $this->env = $twig;
        $this->propertyAccessor = $propertyAccessor;
        $this->formFactory = $formFactory;
    }

    /**
     * @param Field $field
     * @param       $data
     *
     * @return mixed
     */
    public function renderField(Field $field, $data)
    {
        try {
            return $this->propertyAccessor->getValue($data, $field->getPath());
        } catch (\Exception $exception) {
            return '';
        }
    }

    /**
     * @param GridView    $gridView
     * @param array       $params
     * @param string|null $template
     *
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function renderGrid(GridView $gridView, $params = [], ?string $template = null)
    {
        return $this->env->render(
            $template ?: '@SherlockodeCrud/common/grid/grid.html.twig',
            ['gridView' => $gridView] + $params
        );
    }

    /**
     * @param Filter  $filter
     * @param Request $request
     *
     * @return string
     */
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
