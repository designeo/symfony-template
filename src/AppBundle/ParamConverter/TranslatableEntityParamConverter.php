<?php

namespace AppBundle\ParamConverter;


use AppBundle\Locale\LocaleProvider;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class TranslatableEntityParamConverter
 * @package AppBundle\ParamConverter
 */
class TranslatableEntityParamConverter implements ParamConverterInterface
{
    const DEFAULT_IDENTIFIER = 'slug';

    const USE_TRANSLATABLE_PARAM_CONVERTER = 'useTranslatable';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var LocaleProvider
     */
    private $localeProvider;

    /**
     * @param EntityManager  $em
     * @param LocaleProvider $localeProvider
     */
    public function __construct(EntityManager $em, LocaleProvider $localeProvider)
    {
        $this->em = $em;
        $this->localeProvider = $localeProvider;
    }

    /**
     * Stores the object in the request.
     *
     * @param Request        $request       The request
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool True if the object has been successfully set, else false
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $name = $configuration->getName();
        $entityName = $configuration->getClass();

        $id = $this->getIdentifier($request, $configuration->getOptions(), self::DEFAULT_IDENTIFIER);

        $options = $configuration->getOptions();
        if (isset($options['repository_method'])) {
            $repositoryMethod = $options['repository_method'];
        } else {
            $repositoryMethod = 'find';
        }

        $repository = $this->getEntityRepository($entityName);
        $locale = $this->localeProvider->getLocale();
        $result = $repository->$repositoryMethod($id, $locale);

        if (is_null($result)) {
            throw new NotFoundHttpException('Requested object does not exist.');
        }

        $request->attributes->set($name, $result);

        return true;
    }

    /**
     * Checks if the object is supported.
     *
     * @param ParamConverter $configuration Should be an instance of ParamConverter
     *
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        $entityName = $configuration->getClass();

        return (
            isset($configuration->getOptions()[self::USE_TRANSLATABLE_PARAM_CONVERTER]) &&
            $this->hasTranslationClass($entityName)
        );
    }

    /**
     * @param string $entityName
     * @return bool
     */
    private function hasTranslationClass($entityName)
    {
        $reflection = $this->em->getClassMetadata($entityName);

        return $reflection->getReflectionClass()->hasMethod('getTranslationEntityClass');
    }

    /**
     * @param $entityName
     * @return string
     */
    private function getEntityClassName($entityName)
    {
        $className = $this->em->getClassMetadata($entityName)->getName();
        return $className;
    }

    /**
     * @param $entityName
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getEntityRepository($entityName)
    {
        $entity = $this->getEntityClassName($entityName);
        return $this->em->getRepository($entity);
    }

    /**
     * Get value of identifier from Request.
     * Inspired by DoctrineParamConverter::getIdentifier
     *
     * @param Request $request
     * @param array $options
     * @param string $name
     * @return array|bool|mixed
     */
    private function getIdentifier(Request $request, $options, $name)
    {
        if (isset($options['id'])) {
            if (!is_array($options['id'])) {
                $name = $options['id'];
            } elseif (is_array($options['id'])) {
                $id = array();
                foreach ($options['id'] as $field) {
                    $id[$field] = $request->attributes->get($field);
                }

                return $id;
            }
        }

        if ($request->attributes->has($name)) {
            return $request->attributes->get($name);
        }

        if ($request->attributes->has('id') && !isset($options['id'])) {
            return $request->attributes->get('id');
        }

        return false;
    }

}