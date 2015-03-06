<?php

namespace AppBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class ApplicationAvailabilityFunctionalTest
 * @package AppBundle\Tests
 * @author  Ondřej Musil <omusil@gmail.com>
 */
class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
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
        $session = $this->client->getContainer()->get('session');

        $firewall = 'main';
        $token = new UsernamePasswordToken('admin', null, $firewall, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    public function webUrlProvider()
    {
        return array(
            array('/'),
            array('/login')
        );
    }

    public function adminUrlProvider()
    {
        return array(
            array('/admin'),
        );
    }
}