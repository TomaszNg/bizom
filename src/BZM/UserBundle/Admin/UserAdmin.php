<?php

/**
 * UserAdmin class
 *
 * @author Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * @copyright 2017
 */

namespace BZM\UserBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Knp\Menu\ItemInterface as MenuItemInterface;

class UserAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'bzm_admin_user';
    protected $baseRoutePattern = 'user';
    
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', null, array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch'))
            ->add('roles', 'choice', array('choices' => array(
                    '' => array(
                        'ROLE_USER' => 'ROLE_USER',
                    ),
                    '' => array(
                        'ROLE_ADMIN' => 'ROLE_ADMIN'
                    )
                ),
                'label' => 'form.roles',
                'translation_domain' => 'BZMUserBundle',
                'multiple' => true))
            ->add('enabled', null, array('label' => 'form.enabled', 'translation_domain' => 'BZMUserBundle'));
    }
    
    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('enabled', null, array('label' => 'form.enabled', 'translation_domain' => 'BZMUserBundle'));
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', null, array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('roles', 'choice', array('choices' => array(
                    '' => array(
                        'ROLE_USER' => 'ROLE_USER',
                    ),
                    '' => array(
                        'ROLE_ADMIN' => 'ROLE_ADMIN'
                    )
                ),
                'label' => 'form.roles',
                'translation_domain' => 'BZMUserBundle',
                'multiple' => true))
            ->add('enabled', null, array('label' => 'form.enabled', 'translation_domain' => 'BZMUserBundle'))
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array()
                ),
                'label'     => 'link_actions',
                'translation_domain' => 'SonataAdminBundle'));
    }

    // Fields to be shown on show action
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
           ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
           ->add('email', null, array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
           ->add('roles', 'choice', array('choices' => array(
                    array(
                        'ROLE_USER' => 'ROLE_USER',
                    ),
                    array(
                        'ROLE_ADMIN' => 'ROLE_ADMIN'
                    )
                ),
                'label' => 'form.roles',
                'translation_domain' => 'BZMUserBundle',
                'multiple' => true))
           ->add('enabled', null, array('label' => 'form.enabled', 'translation_domain' => 'BZMUserBundle'))
           ->add('last_login', 'date', array('label' => 'form.lastlogin', 'translation_domain' => 'BZMUserBundle'));
    }
}