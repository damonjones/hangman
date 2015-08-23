<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testGame()
    {
        $id = $this->doTestNewGame();
        $this->doTestGuess($id);
    }

    public function doTestNewGame()
    {
        $client = static::createClient();

        $container = $client->getContainer();
        $route = $container->get('router')->generate('game');

        // POST to /game
        $client->request('POST', $route);

        // Expect a 201 CREATED status code
        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $content = $client->getResponse()->getContent();
        // Should not contain an error, but should contain at least data and id
        $this->assertNotContains('error', $content);
        $this->assertContains('data', $content);
        $this->assertContains('id', $content);

        // Get the new game ID
        $data = json_decode($content, true);

        return $data['data']['id'];
    }

    public function doTestGuess($id)
    {
        $client = static::createClient();

        $container = $client->getContainer();
        $route = $container->get('router')->generate('guess', ['id' => $id]);

        // PUT to /game/{id} with a JSON body containing a letter
        $client->request(
            'PUT',
            $route,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{ "letter": "a" }'
        );

        // Expect a 200 OK status code
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $content = $client->getResponse()->getContent();
        // Should not contain an error, but should contain at least data, word, tries_left and status
        $this->assertNotContains('error', $content);
        $this->assertContains('data', $content);
        $this->assertContains('word', $content);
        $this->assertContains('tries_left', $content);
        $this->assertContains('status', $content);
    }
}
