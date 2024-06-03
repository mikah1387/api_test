<?php

namespace App\Controller;

use App\Service\ApiService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    
    public function index(HttpClientInterface $client): Response
    {

     $response = $client->request(
        'GET',
        'https://jsonplaceholder.typicode.com/comments'

     );

     $posts = json_decode($response->getContent(), true);



        return $this->render('main/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/youtube', name: 'youtube')]
    
    public function youtube(ApiService $apiService): Response
    {

     try{
          $video = $apiService->searchVideo('YoanDev', 20);
        
     }catch(Exception $e){
        throw new \Exception('Failed to fetch data from YouTube API');
     }


        return $this->render('main/results.html.twig', [
            'video' => $video,
        ]);
    }
}
