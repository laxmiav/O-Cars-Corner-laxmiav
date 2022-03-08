<?php

namespace App\Controller\Backoffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CarRepository;

class carControlleurController extends AbstractController
{
     /**
     * @Route("/backoffice_home", name="backoffice_home")
     */
  
    public function list(CarRepository $carRepository): Response
    {
        $cars = $carRepository->findBy([], ['releaseYear' => 'DESC', 'model' => 'ASC']);

        return $this->render('backoffice/main/home.html.twig', [
            'cars' => $cars
        ]);
    }
}
