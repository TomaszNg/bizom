<?php

/**
 * Parameters manager
 *
 * @author Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * @copyright 2017
 */

namespace BZM\CoreBundle\Service;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Encoder\YamlEncoder;

class ParametersManager
{
    private $encoder;
    private $file;

    public function __construct($rootDir) {
        $this->file = $rootDir . '\config\parameters.yml';
        $this->encoder = new YamlEncoder();
    }

    public function saveParameters($data) {
        $parameters = $this->decodeParameters();
        $parameters['parameters'] = array_merge($parameters['parameters'], $data);
        $yaml = $this->encoder->encode($parameters, 'yaml', ['yaml_inline' => 2, 'yaml_indent' => 0]);
        
        file_put_contents($this->file, $yaml);
    }

    public function decodeParameters() {
        $yaml = file_get_contents($this->file);
        $parameters = $this->encoder->decode($yaml, 1);
        
        return $parameters;
    }
}