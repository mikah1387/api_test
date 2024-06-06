<?php

namespace App\Controller;

use App\Form\SearchTagFormType;
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
    
    public function youtube(ApiService $apiService, Request $request): Response
    {
        
        $form = $this->createForm(SearchTagFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->get('tag')->getData();
            
            
        if ($data !== null) {

            try{
                $datas = $apiService->searchVideo($data, 30);
                // dd($datas);
                return $this->render('main/results.html.twig', [
                 'videos'=> $datas,
                 'formSearch' => $form
                ]);
            }catch(Exception $e){
                throw new \Exception('echec de recuperation de la recherche sur YouTube API');
             } 

        }            
        //    try{

        //     return $this->redirectToRoute('youtube', [
        //         'tag' => $data,
        //     ]);
        //    }catch(Exception $e){
        //       throw new \Exception('echec de recuperation de la recherche sur YouTube API');
        //    }     

        }

        // $tag = $request->query->get('tag');      
        // if ($tag !== null) {
        // $video = $apiService->searchVideo($tag, 30);
        //    return $this->render('main/results.html.twig', [
        //     'video'=> $video,
        //     'formSearch' => $form
        //    ]);
        // }

        return $this->render('main/results.html.twig', [
            
            'formSearch' => $form
        ]);

    }
}
