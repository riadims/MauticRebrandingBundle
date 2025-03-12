<?php

namespace MauticPlugin\RebrandingBundle\Form;

use Mautic\CoreBundle\Form\Type\ColorPickerType;
use Mautic\FormBundle\Form\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RebrandingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('logo', FileType::class, [
                'label' => 'Upload Logo',
                'required' => false,
                'attr' => [
                    'accept' => 'image/*'
                ]
            ])
            ->add('title', 'text', [
                'label' => 'Site Title',
                'required' => true
            ])
            ->add('primaryColor', ColorPickerType::class, [
                'label' => 'Primary Color',
                'required' => true
            ]);
    }

    public function getBlockPrefix()
    {
        return 'rebranding_form';
    }
}
