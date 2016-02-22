<?php

namespace AppBundle\Tests;

use AppBundle\Tests\Base\AWebDatabaseTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class ApplicationAvailabilityFunctionalTest
 * @package AppBundle\Tests
 * @author  Ondřej Musil <omusil@gmail.com>
 */
class ApplicationAvailabilityFunctionalTest extends AWebDatabaseTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    /**
     * @dataProvider webUrlProvider
     * @param string $url
     */
    public function testWeb($url)
    {
        $this->client->request('GET', $url);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     * @return array
     */
    public function webUrlProvider()
    {
        return array(
            array('/en/'),
            array('/login'),
        );
    }
}