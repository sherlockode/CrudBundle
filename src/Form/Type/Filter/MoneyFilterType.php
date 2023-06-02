<?php

namespace Sherlockode\CrudBundle\Form\Type\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoneyFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lessThanOrEqual', MoneyType::class, [
                'label' => 'sherlockode_crud.filter.less_than_or_equal',
                'required' => false,
            ])
            ->add('greaterThanOrEqual', MoneyType::class, [
                'label' => 'sherlockode_crud.filter.greater_than_or_equal',
                'required' => false,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', null);
    }
}
