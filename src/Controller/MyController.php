<?php

namespace Silviu\CsvTools\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MyController extends AbstractController
{
    #[Route('/', name: 'app_my')]
    public function index(): Response
    {
        return $this->render('my/index.html.twig', [
            'controller_name' => 'MyController',
        ]);
    }
    #[Route('/test', name: 'app_test')]
    public function jsonroute(): JsonResponse
    {
        $data = ['message' => 'This is a JSON response from /test',
            'status' => 'success',
            'timestamp' => (new \DateTime())->format('Y-m-d H:i:s')];
        return new JsonResponse($data);

    }
    #[Route('/test/{id}', name: 'app_single',requirements: ['id' => '\d+'])]
    public function test_params(int $id): Response
    {
        if($id === 2){
            sleep(2);
            return new RedirectResponse("https://google.com",);
        }
        return new Response("Wait");

    }
}
