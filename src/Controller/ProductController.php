<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

#[Route('/api', name: 'api_')]
class ProductController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository, 
        private SerializerInterface $serializer,
        private EntityManagerInterface $em
    ){}

    #[Route('/product', methods: ["GET"], name: 'listProduct')]
    public function listProduct(): Response
    {

        try {
            
            $products = $this->productRepository->findAll();

            $status = count($products) == 0 ? 204 : 200;

            return new Response(
                $this->serializer->serialize($products, JsonEncoder::FORMAT),
                $status,
                array_merge([], ['Content-Type' => 'application/json;charset=UTF-8'])
            );
        } catch (\Exception $ex) {
            return new JsonResponse(["Error" => $ex->getMessage()], 500);
        }
        


    }

    #[Route('/product', methods: ["POST"], name: 'createProduct')]
    public function create(Request $request): Response
    {

        try {

            // Obtener el contenido crudo de la solicitud
            $content = $request->getContent();

            // Verificar el tipo de contenido
            if ($request->getContentType() != 'json') {
                // El tipo de contenido no es JSON
                return new JsonResponse(["Error" => "The content is not valid"], 400);
            }

            // Intentar decodificar el JSON
            $decodedJson = json_decode($content, true);

            // Verificar si hubo un error en la decodificación
            if (json_last_error() !== JSON_ERROR_NONE) {
                // El contenido no es un JSON válido
                return new JsonResponse(["Error" => "The content is not valid"], 400);
            }

            if (is_array($decodedJson)) {
                if (isset($decodedJson[0]) && is_array($decodedJson[0])) {
                    foreach ($decodedJson as $productData) {
                        $product = new Product;
                        $product->setSku($productData["Sku"]);
                        $product->setProductName($productData["Product_name"]);
                        $product->setDescription($productData["description"]);
                        $product->setCreatedAt( new DateTime('now') );
                        $this->em->persist( $product );
                    }
                } else {
                    $product = new Product;
                    $product->setSku($decodedJson["Sku"]);
                    $product->setProductName($decodedJson["Product_name"]);
                    $product->setDescription($decodedJson["description"]);
                    $product->setCreatedAt( new DateTime('now') );
                    $this->em->persist( $product );
                }
            } else {
                return new JsonResponse(["Error" => "The content is not valid"], 400);
            }
        
            $this->em->flush();
            return new Response("Success", 201);
            
        } catch (\Exception $ex) {
            return new JsonResponse(["Error" => $ex->getMessage()], 500);
        }


    }

    #[Route('/product', methods: ["PUT"], name: 'updateProduct')]
    public function update(Request $request): Response
    {

        try {

            // Obtener el contenido crudo de la solicitud
            $content = $request->getContent();

            // Verificar el tipo de contenido
            if ($request->getContentType() != 'json') {
                // El tipo de contenido no es JSON
                return new JsonResponse(["Error" => "The content is not valid"], 400);
            }

            // Intentar decodificar el JSON
            $decodedJson = json_decode($content, true);

            // Verificar si hubo un error en la decodificación
            if (json_last_error() !== JSON_ERROR_NONE) {
                // El contenido no es un JSON válido
                return new JsonResponse(["Error" => "The content is not valid"], 400);
            }

            if (is_array($decodedJson)) {

                if (isset($decodedJson[0]) && is_array($decodedJson[0])) {

                    foreach ($decodedJson as $productData) {

                        if (!isset($productData['Sku']))  return new JsonResponse(["Error" => "Sku missing"], 400);

                        $sku = $productData['Sku'];
                        $product = $this->em->getRepository(Product::class)->findOneBy(['Sku' => $sku]);

                        if(isset($productData["Product_name"])) $product->setProductName($productData["Product_name"]);
                        if(isset($productData["description"])) $product->setDescription($productData["description"]);

                        $product->setUpdatedAt( new DateTime('now') );
                        $this->em->persist( $product );
                    }
                } else {

                    if (!isset($decodedJson['Sku']))  return new JsonResponse(["Error" => "Sku missing"], 400);

                    $sku = $decodedJson['Sku'];
                    $product = $this->em->getRepository(Product::class)->findOneBy(['Sku' => $sku]);

                    if(isset($decodedJson["Product_name"])) $product->setProductName($decodedJson["Product_name"]);
                    if(isset($decodedJson["description"])) $product->setDescription($decodedJson["description"]);

                    $product->setUpdatedAt( new DateTime('now') );
                    $this->em->persist( $product );
                }

            } else {
                return new JsonResponse(["Error" => "The content is not valid"], 400);
            }
        
            $this->em->flush();
            return new Response("Success", 200);
            
        } catch (\Exception $ex) {
            return new JsonResponse(["Error" => $ex->getMessage()], 500);
        }


    }
}
