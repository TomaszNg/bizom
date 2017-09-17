<?php

/**
 * Form generator
 *
 * @author Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * @copyright 2017
 */

namespace BZM\CoreBundle\Service;

use Symfony\Component\Form\FormFactory;
use BZM\CoreBundle\Form\ProjectType;
use BZM\CoreBundle\Entity\Project;

class FormGenerator
{
    private $formFactory;
    
    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    // Returns install form
    public function getInstallForm()
    {
        $form = $this->formFactory->create(ProjectType::class, new Project());

        return $form;
    }
}