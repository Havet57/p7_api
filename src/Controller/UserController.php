<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{


    /** 
     * Montre tous les users
     */
    #[Route('/api/users', name: 'user_all', methods: ['GET'])]
    public function findAll(EntityManagerInterface $em, SerializerInterface $serializer): Response
    {

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $users = $em->getRepository(User::class)->findAll();
        $jsonObject = $serializer->serialize($users, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

 
        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
    }

    /** 
     * Détail chaque user
     */
    #[Route('/api/users/{id}', name: 'user_index', methods: ['GET'])]
    public function findById(EntityManagerInterface $em,  SerializerInterface $serializer, $id): Response
    {

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $users = $em->getRepository(User::class)->find($id);
        $jsonObject = $serializer->serialize($users, 'json', [
           'circular_reference_handler' => function ($object) {
            return $object->getId();
            }
        ]);

        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);

    }


    /** 
     * Supprime user
     */
    #[Route('/api/user/{id}/delete', name: 'user_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($id);
        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
