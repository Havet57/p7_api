<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;



class ProductController extends AbstractController
{
    /**
     * List all products.
     *
     *
     * @Route("/api/products", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns all products",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class, groups={"full"}))
     *     )
     * )
     * @OA\Tag(name="products")
     */

    public function index(EntityManagerInterface $em, SerializerInterface $serializer): Response
    { 
        
        $products = $em->getRepository(Product::class)->findAll();
        return new Response($serializer->serialize($products, 'json'), 200, ['Accept' => 'application/json', 'Content-Type'=>'application/json']);

    }

   

    /** 
     * détail chaque produit
     */
    #[Route('/api/products/{id}', name: 'product_index', methods: ['GET'])]
    // Ce qui va suivre c'est plutôt la manière moderne d'écrire la route pour atteindre l'api
    #[OA\Tag(name:"products")]
    public function findById(EntityManagerInterface $em,  SerializerInterface $serializer, $id): Response
    {
        $product = $em->getRepository(Product::class)->find($id);
        return new Response($serializer->serialize($product, 'json'), Response::HTTP_OK, ['Accept'=>'application/json', 'Content-Type'=>'application/json']);
    }
}
