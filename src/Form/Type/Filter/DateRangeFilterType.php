<?php

declare(strict_types=1);

namespace Sherlockode\CrudBundle\Form\Type\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateRangeFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('from', DateType::class, [
                'label' => 'sherlockode_crud.filter.from',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('to', DateType::class, [
                'label' => 'sherlockode_crud.filter.to',
                'widget' => 'single_text',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data' => null,
            'translation_domain' => 'SherlockodeCrud'
        ]);
    }
}
