<?php

namespace App\Controller;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{

    private $postList;

    public function __construct(private PostRepository $PostRepository)
    {

    }
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $postList = $this->PostRepository->fetchPostsWithUsername();
        return $this->render('home/index.html.twig', [
            'messages' => $postList
        ]);
    }
}
