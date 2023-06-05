<?php

namespace Sherlockode\CrudBundle\Form\Type\Filter;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EntityFilterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'class' => null,
                'label' => false,
                'placeholder' => 'sherlockode_crud.filter.all',
                'translation_domain' => 'SherlockodeCrud',
            ])
        ;
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
