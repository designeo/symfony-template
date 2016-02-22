<?php

namespace AppBundle\Tests;

use AppBundle\Tests\DataFixtures\ORM\UsersData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class LoginFunctionalTest
 * @package AppBundle\Tests
 * @author  Ondřej Musil <omusil@gmail.com>
 */
class LoginFunctionalTest extends WebTestCase
{

    /**
     * @var string
     */
    private $loginUrl = '/api/v1/login/';


    private $headers = [
      'CONTENT_TYPE' => 'application/json',
      'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ];

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
    }

    public function testUserPasswordLogin()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\Test\UsersData',
        ));

        $client = static::makeClient();
        $content = $this->createJsonContent('admin@localhost', 'secret', 'password');
        $client->request('POST', $this->loginUrl, [], [], $this->headers, $content);
        $data = json_decode($client->getResponse()->getContent(), true);

        $expected = [
          'data' => [
            'token' => 'abcd',
            'user' => [
              'name' => 'Petr',
            ],
          ],
        ];

        $this->assertSame($expected, $data);
    }


    public function testUserPasswordMismatch()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\Test\UsersData',
        ));

        $client = static::makeClient();
        $content = $this->createJsonContent('admin@localhost', 'totaly incorrect password', 'password');
        $client->request('POST', $this->loginUrl, [], [], $this->headers, $content);
        $data = json_decode($client->getResponse()->getContent(), true);

        $expected = [
            'error' => 'Credentials mismatch'
        ];

        $this->assertSame($expected, $data);
    }


    public function testUserFacebookMismatch()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\Test\UsersData',
        ));

        $client = static::makeClient();
        $content = $this->createJsonContent('admin@localhost', md5(100), 'facebook');
        $client->request('POST', $this->loginUrl, [], [], $this->headers, $content);
        $data = json_decode($client->getResponse()->getContent(), true);

        $expected = [
            'error' => 'Credentials mismatch'
        ];

        $this->assertSame($expected, $data);
    }



    public function testUserFacebookLogin()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\Test\UsersData',
        ));

        $client = static::makeClient();
        $content = $this->createJsonContent('admin@localhost', md5(42), 'facebook');
        $client->request('POST', $this->loginUrl, [], [], $this->headers, $content);
        $data = json_decode($client->getResponse()->getContent(), true);

        $expected = [
            'data' => [
                'token' => 'abcd',
                'user' => [
                    'name' => 'Petr',
                ],
            ],
        ];

        $this->assertSame($expected, $data);
    }

    public function testFailedUserLogin()
    {
        $this->loadFixtures(array(
            UsersData::class,
        ));

        $content = $this->createJsonContent('admin@localhost', 'secret');
        $client = static::makeClient();
        $client->request('POST', '/api/v1/login/', [], [], $this->headers, $content);
        $this->assertSame(400, $client->getResponse()->getStatusCode());
    }

    private function createJsonContent($username = null, $password = null, $type = null)
    {
        $result = [];
        if (!is_null($username)) {
            $result['username'] = $username;
        }

        if (!is_null($password)) {
            $result['password'] = $password;
        }

        if (!is_null($type)) {
            $result['type'] = $type;
        }

        return json_encode($result);
    }
}