<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product',methods: ['GET'])]
    public function index(ProductRepository $repository): Response
    {
        $products = $repository ->findAll();
        return $this->render('Pages/product/index.html.twig', [
        'products' => $products
        ]);

    }
    #[route('/product/nouveau', name:'product.new', methods:['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response{
        $product = new product();
        $form = $this->createForm(ProductType::class, $product);

        $form>$form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $product = $form->getData();
            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute('app_product');
        }

        return $this->render('Pages/Product/new.html.twig', [
            'form' => $form->createView()
        ]);

    }

    #[route('/product/edit/{id}', name:'product.edit', methods:['GET','POST'])]
    public function edit(product $product, Request $request, EntityManagerInterface $manager): Response{
        
        $form = $this->createForm(ProductType::class, $product);
        $form>$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $product = $form->getData();
            
            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute('app_product');
        }

        return $this->render('Pages/Product/edit.html.twig', [
            'form' => $form->createView()
        ]);

    }

    #[Route('/product/suppression/{id}', 'product.delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager,product $product,): Response {
        $manager->remove($product);
        $manager->flush();

        return $this->redirectToRoute('app_product');
    }
}





