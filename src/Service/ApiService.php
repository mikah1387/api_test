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

    public function searchVideo( string $search, int $maxResults =1): array
    {       

        $response = $this->client->request(  
            'GET',
            'https://www.googleapis.com/youtube/v3/search',
            [
                'query' => [
                    'key' => $this->apiKey,
                    'part' => 'snippet',
                    'q' => $search,
                    'order' => 'viewCount', // Trier par nombre de vues
                    'type' => 'video',
                    'maxResults' => $maxResults
                ]
            ]
        );

 

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to fetch data from YouTube API');
        }
         
         $datas = json_decode($response->getContent(), true);

        //  $videoIds = array_map(fn($item) => $item['id']['videoId'], $datas['items']);

        //  return $this->getVideosDetails($videoIds);
     
        //  dd($datas);
        return   $datas;
         
    }

    private function getVideosDetails(array $videoIds): array
    {
        $response = $this->client->request(
            'GET',
            'https://www.googleapis.com/youtube/v3/videos',
            [
                'query' => [
                    'part' => 'snippet,statistics',
                    'id' => implode(',', $videoIds),
                    'key' => $this->apiKey,
                ],
            ]
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Failed to fetch video details from YouTube API: ' . $response->getContent(false));
        }

        return $response->toArray();
    }
}
