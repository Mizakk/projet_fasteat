<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RestoController extends AbstractController
{
    /**
     * @Route("/resto/panel", name="panel_restaurateur")
     */
    public function index()
    {
        return $this->render('resto/resto.html.twig');
    }
}
