<?php

namespace Sherlockode\CrudBundle\Form\Type\Filter;

use Sherlockode\CrudBundle\Filter\BooleanFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BooleanFilterType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'choices' => [
                    'sherlockode_crud.filter.boolean.yes' => BooleanFilter::TRUE,
                    'sherlockode_crud.filter.boolean.no' => BooleanFilter::FALSE,
                ],
                'data_class' => null,
                'required' => false,
                'placeholder' => 'sherlockode_crud.filter.boolean.all',
                'translation_domain' => 'SherlockodeCrud',
            ])
        ;
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
