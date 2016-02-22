<?php

namespace AppBundle\Service;

use AppBundle\Configuration\QueryConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;


/**
 * Put specific attribute parameter to query parameters
 */
class QueryStringConverter implements ParamConverterInterface
{

    public function supports(ParamConverter $configuration)
    {
        return ($configuration instanceof QueryConverter);
    }

    public function apply(Request $request, ParamConverter $configuration) 
    {
        $param = $configuration->getName();
        $options = $configuration->getOptions();

        $params = [];

        if (array_key_exists('id', $options)) {
            $params[] = $options['id'];
        }

        if (array_key_exists('mapping', $options)) {
            $params = array_merge($params, $options['mapping']);
        }

        if (empty($params)) {
            $params[] = $param;
        }

        foreach ($params as $param) {
            $value = $request->query->get($param, null);

            if (is_null($value)) {
                $configuration->setIsOptional(true);
            }

            $request->attributes->set($param, $value);
        }
    }
}