<?php

namespace AppBundle\Tests\Base;

use AppBundle\Tests\Helpers\TestSpool;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractApiTest extends WebTestCase
{


    /**
     * @param $input
     * @param $url
     * @param $headers
     * @param string $method
     * @param boolean $enforceValidJsonResponse TRUE if response should be checked if contains valid JSON
     * @return Response
     */
    protected function getResponseFromApi($input, $url, $headers, $method = 'POST', $enforceValidJsonResponse = true)
    {
        $data = json_encode($input);

        $client = static::makeClient();
        $client->request($method, $url, [], [], $headers, $data);

        $response = $client->getResponse();

        if ($enforceValidJsonResponse) {
            $this->parseJson($response->getContent());
        }

        return $client->getResponse();
    }

    protected function getHeaders($token)
    {
        $data = [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X-Api-Key' => 'a1897dcc6a7e949b544b8d8b1f91ca92d022c5e7',
        ];

        if ($token) {
            $data['HTTP_AUTHORIZATION'] = $token;
        }

        return $data;
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getEntityManager()
    {
        $container = static::$kernel->getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');

        return $entityManager;
    }

    /**
     * @return string
     */
    protected function getBaseUri()
    {
        return 'http://localhost';
    }

    /**
     * Fetch freshly reloaded entity with given ID from database.
     *
     * @param string $className
     * @param string $id
     * @param bool $refreshEntity
     * @return object
     */
    protected function getEntity($className, $id, $refreshEntity = true)
    {
        $entity = $this->getEntityManager()->find($className, $id);

        if ($refreshEntity) {
            $this->getEntityManager()->refresh($entity);
        }

        return $entity;
    }

    protected function assertErrorCode($body, $code)
    {
        $data = json_decode($body, true);

        $this->assertEquals(JSON_ERROR_NONE, json_last_error(), 'Response json must be valid');
        $this->assertTrue(array_key_exists('error_code', $data), 'Response must contain key "error_code"');
        $this->assertEquals($code, $data['error_code'], 'Error code mismatch');
    }

    /**
     * @return TestSpool
     */
    protected function getMailerSpool()
    {
        return $this->getContainer()->get('swiftmailer.mailer.default.spool.test_spool');
    }

    /**
     *
     * @param string $json
     * @return array
     */
    protected function parseJson($json)
    {
        $data = json_decode($json, true);

        $this->assertEquals(
            JSON_ERROR_NONE,
            json_last_error(),
            'Valid json must be given. Given input: ' . var_export($json, true)
        );

        return $data;
    }
}