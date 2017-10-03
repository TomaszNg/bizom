<?php

/**
 * PageAdmin class
 *
 * @author Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * @copyright 2017
 */

namespace BZM\PageBundle\Admin;

use Sonata\PageBundle\Admin\PageAdmin as SonataPageAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\FormBuilderInterface;

class PageAdmin extends SonataPageAdmin
{
    protected $baseRoutePattern = 'page';

    protected function configureFormFields(FormMapper $formMapper)
    {
        // define group zoning
        $formMapper
             ->with('form_page.group_main_label', array('class' => 'col-md-6'))->end()
             ->with('form_page.group_seo_label', array('class' => 'col-md-6'))->end()
             ->with('form_page.group_advanced_label', array('class' => 'col-md-6'))->end()
        ;

        if (!$this->getSubject() || (!$this->getSubject()->isInternal() && !$this->getSubject()->isError())) {
            $formMapper
                ->with('form_page.group_main_label')
                    ->add('url', 'text', array('attr' => array('readonly' => 'readonly')))
                ->end()
            ;
        }

        if ($this->hasSubject() && !$this->getSubject()->getId()) {
            $formMapper
                ->with('form_page.group_main_label')
                    ->add('site', null, array('attr' => array('required' => true, 'readonly' => 'readonly')))
                ->end()
            ;
        }

        $formMapper
            ->with('form_page.group_main_label')
                ->add('name')
                ->add('enabled', null, array('required' => false))
                ->add('position')
            ->end()
        ;

        if ($this->hasSubject() && !$this->getSubject()->isInternal()) {
            $formMapper
                ->with('form_page.group_main_label')
                    ->add(
                        'type',
                        // NEXT_MAJOR: remove these three lines and uncomment the one following
                        method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix') ?
                            'Sonata\PageBundle\Form\Type\PageTypeChoiceType' :
                            'sonata_page_type_choice',
//                        'Sonata\PageBundle\Form\Type\PageTypeChoiceType',
                        array('required' => false)
                    )
                ->end()
            ;
        }

        $formMapper
            ->with('form_page.group_main_label')
                ->add(
                    'templateCode',
                    // NEXT_MAJOR: remove these three lines and uncomment the one following
                    method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix') ?
                        'Sonata\PageBundle\Form\Type\TemplateChoiceType' :
                        'sonata_page_template',
//                    'Sonata\PageBundle\Form\Type\TemplateChoiceType',
                    array('required' => true)
                )
            ->end()
        ;

        if (!$this->getSubject() || ($this->getSubject() && $this->getSubject()->getParent()) || ($this->getSubject() && !$this->getSubject()->getId())) {
            $formMapper
                ->with('form_page.group_main_label')
                    ->add(
                        'parent',
                        // NEXT_MAJOR: remove these three lines and uncomment the one following
                        method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix') ?
                            'Sonata\PageBundle\Form\Type\PageSelectorType' :
                            'sonata_page_selector',
//                        'Sonata\PageBundle\Form\Type\PageSelectorType',
                        array(
                            'page' => $this->getSubject() ?: null,
                            'site' => $this->getSubject() ? $this->getSubject()->getSite() : null,
                            'model_manager' => $this->getModelManager(),
                            'class' => $this->getClass(),
                            'required' => false,
                            'filter_choice' => array('hierarchy' => 'root'),
                        ), array(
                            'admin_code' => $this->getCode(),
                            'link_parameters' => array(
                                'siteId' => $this->getSubject() ? $this->getSubject()->getSite()->getId() : null,
                            ),
                        )
                    )
                ->end()
            ;
        }

        if (!$this->getSubject() || !$this->getSubject()->isDynamic()) {
            $formMapper
                ->with('form_page.group_main_label')
                    ->add('pageAlias', null, array('required' => false))
                    ->add(
                        'parent',
                        // NEXT_MAJOR: remove these three lines and uncomment the one following
                        method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix') ?
                            'Sonata\PageBundle\Form\Type\PageSelectorType' :
                            'sonata_page_selector',
//                        'Sonata\PageBundle\Form\Type\PageSelectorType',
                        array(
                            'page' => $this->getSubject() ?: null,
                            'site' => $this->getSubject() ? $this->getSubject()->getSite() : null,
                            'model_manager' => $this->getModelManager(),
                            'class' => $this->getClass(),
                            'filter_choice' => array('request_method' => 'all'),
                            'required' => false,
                        ), array(
                            'admin_code' => $this->getCode(),
                            'link_parameters' => array(
                                'siteId' => $this->getSubject() ? $this->getSubject()->getSite()->getId() : null,
                            ),
                        )
                    )
                ->end()
            ;
        }

        if (!$this->getSubject() || !$this->getSubject()->isHybrid()) {
            $formMapper
                ->with('form_page.group_seo_label')
                    ->add('slug', 'text', array('required' => false))
                    ->add('customUrl', 'text', array('required' => false))
                ->end()
            ;
        }

        $formMapper
            ->with('form_page.group_seo_label', array('collapsed' => true))
                ->add('title', null, array('required' => false))
                ->add('metaKeyword', 'textarea', array('required' => false))
                ->add('metaDescription', 'textarea', array('required' => false))
            ->end()
        ;

        if ($this->hasSubject() && !$this->getSubject()->isCms()) {
            $formMapper
                ->with('form_page.group_advanced_label', array('collapsed' => true))
                    ->add('decorate', null, array('required' => false))
                ->end()
            ;
        }

        $formMapper
            ->with('form_page.group_advanced_label', array('collapsed' => true))
                ->add('javascript', null, array('required' => false))
                ->add('stylesheet', null, array('required' => false))
                ->add('rawHeaders', null, array('required' => false))
            ->end()
        ;

        $formMapper->setHelps(array(
            'name' => 'help_page_name',
        ));

        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('content', 'sonata_formatter_type', function (FormBuilderInterface $formBuilder) {
                    return array(
                        'event_dispatcher' => $formBuilder->getEventDispatcher(),
                        'format_field'   => array('contentFormatter', '[contentFormatter]'),
                        'format_field_options' => array(
                            'choices' => array('text' => 'text', 'markdown' => 'markdown'),
                            'data' => 'markdown',
                        ),
                        'source_field'   => array('rawContent', '[rawContent]'),
                        'source_field_options'      => array(
                            'attr' => array('class' => 'span10', 'rows' => 20)
                        ),
                        'listener'       => true,
                        'target_field'   => '[content]'
                    );
                }),
            ),
            'translation_domain' => 'SonataFormatterBundle',
        ));
    }
}