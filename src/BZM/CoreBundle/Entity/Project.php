<?php

/**
 * Project entity
 *
 * @author Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * @copyright 2017
 */

namespace BZM\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="BZM\CoreBundle\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="project_name", type="string", length=255, unique=true)
     */
    private $projectName;

    /**
     * @var string
     *
     * @ORM\Column(name="company_name", type="string", length=255, unique=true)
     */
    private $companyName;

    /**
     * @var string
     *
     * @ORM\Column(name="company_adress", type="string", length=255, unique=true)
     */
    private $companyAdress;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set projectName
     *
     * @param string $projectName
     *
     * @return Project
     */
    public function setProjectName($projectName)
    {
        $this->projectName = $projectName;

        return $this;
    }

    /**
     * Get projectName
     *
     * @return string
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     *
     * @return Project
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set companyAdress
     *
     * @param string $companyAdress
     *
     * @return Project
     */
    public function setCompanyAdress($companyAdress)
    {
        $this->companyAdress = $companyAdress;

        return $this;
    }

    /**
     * Get companyAdress
     *
     * @return string
     */
    public function getCompanyAdress()
    {
        return $this->companyAdress;
    }
}

