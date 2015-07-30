<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // Default Symfony2 bundles
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            // 3rd party bundles
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),
            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),
            new \Knp\DoctrineBehaviors\Bundle\DoctrineBehaviorsBundle(),
            new JMS\I18nRoutingBundle\JMSI18nRoutingBundle(),
            new JMS\TranslationBundle\JMSTranslationBundle(),
            new Cocur\Slugify\Bridge\Symfony\CocurSlugifyBundle(),

            // Application bundles
            new AppBundle\AppBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
