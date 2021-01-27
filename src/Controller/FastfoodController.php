<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Fastfood;
use App\Repository\FastfoodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FastfoodFormType;

class FastfoodController extends AbstractController
{
  /**
   * @Route("/fastfoods", name="fastfoods_list")
   */
  public function fastfoods(FastfoodRepository $fastfoodRepository): Response
  {
    return $this->render('fastfood/fastfoods.html.twig', [
      'fastfoods' => $fastfoodRepository->findAll()
    ]);
  }

 /**
   * @Route("/admin/add-fastfood", name="add_fastfood")
   */
  public function addFastfood(Request $request): Response
{
    $fastfood = new Fastfood();
    $form = $this->createForm(FastfoodFormType::class, $fastfood);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($fastfood);
        $entityManager->flush();
    }

    return $this->render("fastfood/fastfood-form.html.twig", [
        "form_title" => "Ajouter un fast-food",
        "form_fastfood" => $form->createView(),
    ]);
}

  /**
 * @Route("/modify-fastfood/{id}", name="modify_fastfood")
 */
public function modifyFastfood(Request $request, int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    $fastfood = $entityManager->getRepository(Fastfood::class)->find($id);
    $form = $this->createForm(FastfoodFormType::class, $fastfood);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
        $entityManager->flush();
    }

    return $this->render("fastfood/fastfood-form.html.twig", [
        "form_title" => "Modifier un fastfood",
        "form_fastfood" => $form->createView(),
    ]);
}

/**
 * @Route("/delete-fastfood/{id}", name="delete_fastfood")
 */
public function deleteFastfood(int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $fastfood = $entityManager->getRepository(Fastfood::class)->find($id);
    $entityManager->remove($fastfood);
    $entityManager->flush();

    return $this->redirectToRoute("fastfoods");
}


  /**
   * @Route("/fastfood/{id}", name="fastfood_item")
   */
  public function fastfood(Fastfood $fastfood, Product $product): Response
  { 
    //$product = $repo->findBy($fastfood);
    return $this->render('fastfood/fastfood.html.twig', [
      'fastfood' => $fastfood,
      'product' => $product
    ]);
  }
}
