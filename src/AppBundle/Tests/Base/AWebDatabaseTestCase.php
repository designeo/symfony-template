<?php

namespace AppBundle\Tests\Base;

use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\DependencyInjection\Container;

/**
 *
 *
 * @author Tomáš Polívka <tomas.polivka@designeo.cz>
 * @copyright 2014 Designeo Creative s.r.o.
 */
abstract class AWebDatabaseTestCase extends WebTestCase
{
    /**
    * @var \Doctrine\ORM\EntityManager
    */
    protected $em;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Application
     */
    protected static $application;

    /**
     *
     */
    public function setUp()
    {

        parent::setUp();
        self::bootKernel();
        $this->databaseInit();
    }

    /**
     * Initialize database
     */
    protected function databaseInit()
    {

        $this->container = static::$kernel->getContainer();
        $em = $this->container->get('doctrine.orm.entity_manager');

        // some error occured while attempting to boot the application and therefore entity manager is null.
        if (is_null($em)) {
            die('Error occured during the application boot. Check your configurations please.');
        } else {
            $this->em = $em;
        }

        static::$application = new Application(static::$kernel);

        static::$application->setAutoExit(false);
        $this->runConsole('d:s:d', array('--force' => true));
        //$this->runConsole('d:m:m');
        //$this->em->createNativeQuery('TRUNCATE migration_versions;', $rsm = new ResultSetMapping())->execute();
        $output = $this->runConsole('d:m:m');
        //dump($output->fetch());
        $output = $this->runConsole('doctrine:fixtures:load', ['--append' => null]);
        $this->runConsole('cache:warmup');
    }

    /**
     * Executes a console command
     *
     * @param string $command
     * @param array $options
     * @return Output
     */
    protected function runConsole($command, Array $options = array())
    {
        $options['--env'] = 'test';
        $options['--quiet'] = null;
        //$options['-vvv'] = null;
        $options['--no-interaction'] = null;
        $options = array_merge($options, array('command' => $command));
        static::$application->run(new ArrayInput($options), $buf = new BufferedOutput());

        return $buf;
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
    }
} 