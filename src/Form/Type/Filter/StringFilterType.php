<?php

namespace Sherlockode\CrudBundle\Form\Type\Filter;

use Sherlockode\CrudBundle\Filter\StringFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StringFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!isset($options['type'])) {
            $builder
                ->add('type', ChoiceType::class, [
                    'label' => sprintf('sherlockode_crud.filter.string_condition'),
                    'choices' => [
                        'sherlockode_crud.filter.contains' => StringFilter::TYPE_CONTAINS,
                        'sherlockode_crud.filter.not_contains' => StringFilter::TYPE_NOT_CONTAINS,
                        'sherlockode_crud.filter.equal' => StringFilter::TYPE_EQUAL,
                    ],
                ])
            ;
        }

        $builder
            ->add('value', TextType::class, [
                'required' => false,
                'label' => 'sherlockode_crud.filter.value',
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
        $resolver
            ->setDefault('translation_domain', 'SherlockodeCrud')
            ->setDefined('type')
            ->setAllowedValues('type', [
                StringFilter::TYPE_CONTAINS,
                StringFilter::TYPE_NOT_CONTAINS,
                StringFilter::TYPE_EQUAL,
            ])
        ;
    }
}

