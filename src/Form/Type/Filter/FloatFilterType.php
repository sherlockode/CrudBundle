<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Form\Type\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FloatFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lessThanOrEqual', NumberType::class, [
                'label' => 'sherlockode_crud.filter.less_than_or_equal',
                'required' => false,
            ])
            ->add('greaterThanOrEqual', NumberType::class, [
                'label' => 'sherlockode_crud.filter.greater_than_or_equal',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', null);
    }
}
