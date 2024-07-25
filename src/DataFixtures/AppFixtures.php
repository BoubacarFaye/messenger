<?php

namespace App\DataFixtures;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Like;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create();
        $numberCreated = 50;
        $newUsersList = [];
        //creation users
        for ($i=0; $i < $numberCreated; $i++)
        {
            $user = new User();
            $user->setUsername($faker->userName);
            $user->setRoles(['ROLE_USER']);
            $user->setEmail($faker->email);
            $user->setPassword($faker->password);
            // $user->setId($i);
            $manager->persist($user);
        }
        $manager->flush();


        $userList = $manager->getRepository(User::class)->findAll();


        //création posts par user selon nombre au hasard
        $maxPosts = 15;
        foreach($userList as $user)
        {
            $nbPosts = mt_rand(0,$maxPosts);
            for ($i=0; $i < $nbPosts; $i++)
            {
                $post = new Post();
                $post->setContent($faker->paragraph);
                $post->setUserId($user);
                $manager->persist($post);
            }
        };

        $manager->flush();

        //création comments à partir de users et posts créés
        $postList = $manager->getRepository(Post::class)->findAll();
        foreach($userList as $user)
        {
            if (mt_rand(0,3) == 0)
            {
                foreach ($postList as $post)
                {
                    if (mt_rand(0,4) == 0)
                    {
                        $comment = new Comment();
                        $comment->setUserId($user);
                        $comment->setContent($faker->paragraph);
                        $comment->setPostId($post);
                        $manager->persist($comment);
                    }
                }
            }

        };
        $manager->flush();

        $commentList = $manager->getRepository(Comment::class)->findAll();

        // TODO empêcher multiples likes par un même user
        //création likes
        foreach($userList as $user)
        {
            if (mt_rand(0,8)== 5)
            {
                $rand = mt_rand(0,1);
                if ($rand == 0)
                {
                    foreach ($postList as $post) {
                        $postLike = new Like();
                        $postLike->setUserId($user);
                        $postLike->setPostId($post);
                        $manager->persist($postLike);
                    }
                }
                elseif ($rand == 1)
                {
                    foreach ($commentList as $comment) {
                        if (mt_rand(0,4) == 0){
                            $commentLike = new Like();
                            $commentLike->setUserId($user);
                            $commentLike->setCommentId($comment);
                            $manager->persist($commentLike);
                        }
                    }
                }
            }

        };


        $manager->flush();
    }
}
