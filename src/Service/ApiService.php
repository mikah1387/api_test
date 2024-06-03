<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiService
{

    private $apiKey;
    private $client;
    public function __construct( HttpClientInterface $client,string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function searchVideo( string $search, int $maxResults =20): array
    {       

        $response = $this->client->request(  
            'GET',
            'https://www.googleapis.com/youtube/v3/search',
            [
                'query' => [
                    'key' => $this->apiKey,
                    'part' => 'snippet',
                    'q' => $search,
                    'maxResults' => $maxResults
                ]
            ]
        );

 

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to fetch data from YouTube API');
        }

        return json_decode($response->getContent(), true);
         
    }
}
