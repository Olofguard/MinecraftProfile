<?php

namespace Navarr\Minecraft\Profile;

use Navarr\Minecraft\Profile;
use GuzzleHttp\Client;
use PHPUnit_Framework_TestCase;

class Tests extends PHPUnit_Framework_TestCase
{
    public function testFromUsername()
    {
        $this->mojangBuster();
        $profile = Profile::fromUsername('Navarr', $this->getClient());
        $this->asserts($profile);
    }

    public function testFromUuid()
    {
        $this->mojangBuster();
        $profile = Profile::fromUuid('bd95beec116b4d37826c373049d3538b', $this->getClient());

        $this->asserts($profile);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Bad JSON from API: on username Nav"arr
     */
    public function testBadUsername()
    {
        $this->mojangBuster();
        Profile::fromUsername('Nav"arr');
    }

    private function asserts(Profile $profile) {
        $this->assertEquals('bd95beec116b4d37826c373049d3538b', $profile->uuid);
        $this->assertEquals('Navarr', $profile->name);
        $this->assertTrue($profile->public);
        $this->assertTrue(strpos($profile->capeUrl, 'http://textures.minecraft.net/texture/') !== false);
        $this->assertTrue(strpos($profile->skinUrl, 'http://textures.minecraft.net/texture/') !== false);
    }

    /* To Prevent 429 */
    private function mojangBuster()
    {
        sleep(2);
    }

    private function getClient()
    {
        $client = new Client();
        $client->setDefaultOption('verify', __DIR__ . '/../data/cacert.pem');
        return new ApiClient($client);
    }
}
