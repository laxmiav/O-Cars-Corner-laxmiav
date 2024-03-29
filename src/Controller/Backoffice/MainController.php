<?php

namespace App\Controller\Backoffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CarRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Myslugger;
use App\Entity\Car;
use App\Entity\User;

class MainController extends AbstractController
{
     /**
     * @Route("/backoffice_home", name="backoffice_home")
     */
  
    public function list(CarRepository $carRepository): Response
    {
        $cars = $carRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('backoffice/main/home.html.twig', [
            'cars' => $cars
        ]);
    }
     /**
     * @return Response
     * @Route("backoffice/car/{id}", name="backoffice_car_detail", methods={"GET"})
     */
    public function detail( Car $car, CarRepository $carRepository): Response
    {
      
        $cars = $carRepository->find($car);

        if ( is_null( $cars ))
        {
            
            throw $this->createNotFoundException('The car does not exist');
        }

        return $this->render('backoffice/main/detail.html.twig', [
            'cars' => $cars
        ]);
    }
     

}
