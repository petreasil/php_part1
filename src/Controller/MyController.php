<?php

namespace Silviu\CsvTools\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Silviu\CsvTools\Form\CsvFormType;

final class MyController extends AbstractController
{
    #[Route('/', name: 'app_my')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(CsvFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('file')->getData();
            //transfrom file and and return a link to download transfromed file
            $this->addFlash('success', 'File uploaded successfully');
        }
        return $this->render('my/index.html.twig', [

            'form' => $form,
        ]);
    }
    #[Route('/test', name: 'app_test')]
    public function second(): JsonResponse
    {
        $data = [
            'message' => 'This is a JSON response from /test',
            'status' => 'success',
            'timestamp' => (new \DateTime())->format('Y-m-d H:i:s')
        ];
        return new JsonResponse($data);

    }
    #[Route('/test/{id}', name: 'app_single', requirements: ['id' => '\d+'])]
    public function test(int $id): Response
    {
        if ($id === 2) {
            sleep(2);
            return new RedirectResponse("https://google.com", );
        }
        return new Response("Wait");

    }

    #[Route('/number/{id}', name: 'number_route', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        return new Response("The ID is $id");
    }

}
