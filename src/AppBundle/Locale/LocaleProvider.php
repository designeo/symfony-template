<?php

namespace AppBundle\Locale;

use AppBundle\Entity\Lang;
use AppBundle\Entity\Language;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class LocaleProvider
 * @package AppBundle\Locale
 */
class LocaleProvider
{
    use ContainerAwareTrait;

    /** @var EntityManager */
    protected $em;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /** @var array */
    protected $languages;

    /** @var array */
    protected $locales;

    /** @var string */
    protected $currentLocale;


    /**
     * @param \Twig_Environment $twig
     * @param EntityManager     $em
     * @param array             $languages
     */
    public function __construct(\Twig_Environment $twig, EntityManager $em, $languages)
    {
        $this->em = $em;
        $this->twig = $twig;
        $this->languages = $languages;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->currentLocale = $event->getRequest()->getLocale();
        $this->twig->addGlobal('locale', $this->currentLocale);
        $this->twig->addGlobal('languages', $this->languages);
    }

    /**
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @return array
     */
    public function getLocales()
    {
        return array_keys($this->languages);
    }

    /**
     * Current locale from request
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->currentLocale;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->languages[$this->currentLocale];
    }
}
