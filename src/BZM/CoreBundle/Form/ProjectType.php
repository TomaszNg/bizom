<?php

/**
 * Project type form
 *
 * @author Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * @copyright 2017
 */

namespace BZM\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use BZM\CoreBundle\Entity\Project;


class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project_name', TextType::class, array('label' => 'form.project_name', 'translation_domain' => 'BZMCoreBundle'))
            ->add('company_name', TextType::class, array('label' => 'form.company_name', 'translation_domain' => 'BZMCoreBundle'))
            ->add('company_adress', TextareaType::class, array('label' => 'form.company_adress', 'translation_domain' => 'BZMCoreBundle'))
            ->add('install', SubmitType::class, array('label' => 'form.btn.install', 'translation_domain' => 'BZMCoreBundle'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Project::class,
        ));
    }
}