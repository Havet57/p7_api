<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Customer;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use OpenApi\Annotations as OA;

class CustomerController extends AbstractController
{
    /** 
     * Pour se connecter
     */
    #[Route(path: '/token', name: 'app_login', methods: ['POST'])]
    public function login(Request $request): Response
    {
        
        $data = json_decode($request->getContent());
        $login = $data->login;
        $password = $data->password;
        return new JsonResponse(['login'=>$login, 'password'=>$password]);
    }

    // /** 
    //     *  @OA\Parameter(
    //     *     name="Create user",
    //     *     in="body",
    //     *     @OA\JsonContent(type="object", @OA\Property(property="name", type="string"), @OA\Property(property="email", type="string"), @OA\Property(property="password", type="string"))
    //     * )     
    // */
    /** 
     * Ajoute un user
     */
    #[Route('/api/customer/{id}/user', name: 'app_new_user', methods: ['POST'])]
    public function newUser($id, Request $request, EntityManagerInterface $em): Response
    {
        $customer = $em->getRepository(customer::class)->find($id);
        $data = json_decode($request->getContent(), true);
        $user = new User;
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setCustomer($customer);
        $em->persist($user);
        $em->flush();

        return new JsonResponse([
            'id'=>$user->getId()
        ]);
    }

    /** 
     * Détail chaque client
     */
    #[Route('/api/customer/{id}', name: 'customer_index', methods: ['GET'])]
    public function findById(EntityManagerInterface $em,  SerializerInterface $serializer, $id): Response
    {

        // Tip : Inject SerializerInterface $serializer in the controller method
        // and avoid these 3 lines of instanciation/configuration
        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        // Serialize your object in Json
        $customers = $em->getRepository(Customer::class)->find($id);
        $jsonObject = $serializer->serialize($customers, 'json', [
           'circular_reference_handler' => function ($object) {
            return $object->getId();
            }
        ]);

        // For instance, return a Response with encoded Json
        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);

    }


    /** 
     * Montre tous les clients
     */
    #[Route('/api/customers', name: 'customer_all', methods: ['GET'])]
    public function findAll(EntityManagerInterface $em, SerializerInterface $serializer): Response
    { 
        
        // Tip : Inject SerializerInterface $serializer in the controller method
        // and avoid these 3 lines of instanciation/configuration
        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        // Serialize your object in Json
        $customers = $em->getRepository(Customer::class)->findAll();
        $jsonObject = $serializer->serialize($customers, 'json', [
           'circular_reference_handler' => function ($object) {
            return $object->getId(); //je ne comprends pas cette ligne
            }
        ]);

        // For instance, return a Response with encoded Json
        return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);


    }


    /** 
     * Supprime client
     */
    #[Route('/api/customers/{id}', name: 'customer_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    { 
        $customer = $em->getRepository(Customer::class)->find($id);
        $em->remove($customer);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);

    }


}
