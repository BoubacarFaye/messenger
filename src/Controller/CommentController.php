<?php

namespace App\Controller;
use App\Repository\CommentRepository;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentController extends AbstractController
{
    public function __construct(private CommentRepository $CommentRepository)
    {

    }
    // TODO recup commentaires selon id, formulaire de nouveau commentaire
    #[Route('/comment/post/{postId}', name: 'app_comment')]
    public function index(int $postId): Response
    {

        //TODO verif si user connecte, si non, masquer inputs

        $commentsList = $this->CommentRepository->fetchComments($postId);
        $count = count($commentsList);
        
        return $this->render('comment/index.html.twig', [
            'comments' => $commentsList,
            'count' => $count,
            'parentPost' => $postId,

        ]);
    }


    #[Route('/comment/{postId}/new', name: 'app_new_comment')]
    public function new(int $postId): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        return $this->render('comment/new.html.twig', [
            'comment_form' => $form
        ]);
    }

}
