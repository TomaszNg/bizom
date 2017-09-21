<?php

/**
 * Form generator
 *
 * @author Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * @copyright 2017
 */

namespace BZM\CoreBundle\Service;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\YamlEncoder;

class ParametersManager
{
    private $encoder;
    private $normalizer;
    private $parametersDir;

    public function __construct($parametersDir) {
        $this->parametersDir = $parametersDir . '\config\parameters.yml';
        $this->encoder       = new YamlEncoder();
        $this->normalizer    = new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter());
    }

    public function saveParameters($parameters, $data) {
        $this->normalizer->setIgnoredAttributes(array('id'));
        $data = $this->normalizer->normalize($data);
        $parameters['parameters'] = array_merge($parameters['parameters'], $data);
        $yaml = $this->encoder->encode($parameters, 'yaml', ['yaml_inline' => 2, 'yaml_indent' => 0]);
        
        file_put_contents($this->parametersDir, $yaml);
    }

    public function decodeParameters() {
        $parameters = file_get_contents($this->parametersDir);
        $parameters = $this->encoder->decode($parameters, 1);
        
        return $parameters;
    }
}