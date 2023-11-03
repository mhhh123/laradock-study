<?php

namespace Tests;


use App\Models\User\User;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $token;

    /** @var User $user */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    public function getAuthHeader($username = 'user123', $password = 'user1234')
    {
        $response = $this->post('/wx/auth/login', ['username' => $username, 'password' => $password]);
        $token = $response->getOriginalContent()['data']['token'] ?? '';
        $this->token = $token;
        return ['Authorization' => 'Bearer ' . $token];
    }

    public function assertLitemallApiGet($uri, $ignore = [])
    {
        $this->assertLitemallApi($uri, 'get', [], $ignore);
    }

    public function assertLitemallApiPost($uri, $data = [], $ignore = [])
    {
        $this->assertLitemallApi($uri, 'post', $data, $ignore);
    }

    public function assertLitemallApi($uri, $method = 'get', $data = [], $ignore = [])
    {
        $client = new Client();
        if ($method == 'get') {
            $response1 = $this->get($uri, $this->getAuthHeader());
            $response2 = $client->get('http://localhost:8080/' . $uri,
                ['headers' => ['X-Litemall-Token' => $this->token]]);
        } else {
            $response1 = $this->post($uri, $data, $this->getAuthHeader());
            $response2 = $client->post('http://localhost:8080/' . $uri,
                [
                    'headers' => ['X-Litemall-Token' => $this->token],
                    'json' => $data
                ]);
        }

        $content1 = $response1->getContent();
        echo "litemall=>" . json_encode(json_decode($content1), JSON_UNESCAPED_UNICODE) . PHP_EOL;
        $content1 = json_decode($content1, true);
        $content2 = $response2->getBody()->getContents();
        echo "mcshop=>$content2" . PHP_EOL;
        $content2 = json_decode($content2, true);

        foreach ($ignore as $key) {
            unset($content1[$key]);
            unset($content2[$key]);
        }

        $this->assertEquals($content2, $content1);
    }
}