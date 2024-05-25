<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class IndexControllerTest
 *
 * This class contains test cases for the IndexController.
 *
 * @package App\Tests\Controller
 */
class IndexControllerTest extends WebTestCase
{
    /**
     * @var KernelBrowser testing client instance.
     */
    private KernelBrowser $client;

    /**
     * Set up before each test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        parent::setUp();
    }

    /**
     * Tests the index action of the IndexController.
     *
     * @return void
     */
    public function testIndex(): void
    {
        // make get request
        $this->client->request('GET', '/');

        // get response content
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        // assert response
        $this->assertResponseStatusCodeSame(JsonResponse::HTTP_OK);
        $this->assertEquals('success', $responseContent['status']);
        $this->assertEquals(JsonResponse::HTTP_OK, $responseContent['code']);
        $this->assertEquals('Welcome in Sentinel API!', $responseContent['message']);
    }
}
