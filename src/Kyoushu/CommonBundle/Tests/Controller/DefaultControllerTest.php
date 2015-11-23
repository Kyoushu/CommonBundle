<?php

namespace Kyoushu\CommonBundle\Tests\Controller;

use Kyoushu\CommonBundle\Tests\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    public function testIndexAction()
    {

        $client = $this->createClient();

        $client->request('GET', '/kyoushu-common');
        $crawler = $client->getCrawler();

        $this->assertContains('Hello world!', $crawler->html());

    }

}