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
     * @dataProvider adminUrlProvider
     * @param string $url
     */
    public function testAdmin($url)
    {
        $this->signInAsAdmin();
        $this->client->request('GET', $url);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     *
     */
    protected function signInAsAdmin()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('_submit')->form(array(
            '_username'  => 'admin@localhost',
            '_password'  => 'secret',
        ));

        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
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

    /**
     * @return array
     */
    public function adminUrlProvider()
    {
        return array(
            array('/admin'),
        );
    }
}