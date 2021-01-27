<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProductFormType;

class ProductController extends AbstractController
{
  /**
   * @Route("/products", name="products_list")
   */
  public function products(ProductRepository $productRepository): Response
  {
    return $this->render('product/products.html.twig', [
      'products' => $productRepository->findBy(['display' => true])
    ]);
  }

  /**
   * @Route("/admin/add-product", name="add_product")
   */
  public function addProduct(Request $request): Response
{
    $product = new Product();
    $form = $this->createForm(ProductFormType::class, $product);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();
    }

    return $this->render("product/product-form.html.twig", [
        "form_title" => "Ajouter un produit",
        "form_product" => $form->createView(),
    ]);
}

  /**
 * @Route("/modify-product/{id}", name="modify_product")
 */
public function modifyProduct(Request $request, int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    $product = $entityManager->getRepository(Product::class)->find($id);
    $form = $this->createForm(ProductFormType::class, $product);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
        $entityManager->flush();
    }

    return $this->render("product/product-form.html.twig", [
        "form_title" => "Modifier un produit",
        "form_product" => $form->createView(),
    ]);
}

/**
 * @Route("/delete-product/{id}", name="delete_product")
 */
public function deleteProduct(int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $product = $entityManager->getRepository(Product::class)->find($id);
    $entityManager->remove($product);
    $entityManager->flush();

    return $this->redirectToRoute("products");
}

  /**
   * @Route("/product/{id}", name="product_item")
   */
  public function product(Product $product): Response
  {
    return $this->render('product/product.html.twig', [
      'product' => $product
    ]);
  }
}
