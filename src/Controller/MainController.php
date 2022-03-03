<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Car;
use App\Repository\CarRepository;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
  
    public function list(CarRepository $carRepository): Response
    {
       
$cars = $carRepository->findAll();

        return $this->render('main/home.html.twig', [
            'cars' => $cars
        ]);
    }
    /**
     * @Route("/car/add", name="car_add")
     */
  
    public function add(Car $cars): Response
    {
       


        return $this->render('main/home.html.twig', [
            'cars' => $cars
        ]);
    }
     /**
     * @Route("/car/edit", name="cars_edit")
     */
  
    public function edit(Car $cars): Response
    {
       


        return $this->render('main/home.html.twig', [
            'cars' => $cars
        ]);
    }
       /**
     * @Route("/car/delete", name="cars_delete")
     */
  
    public function delete(Car $cars): Response
    {
       


        return $this->redirectToRoute('homepage');
    }
    /**
     * @Route("/brand", name="brand_list")
     */
  
    public function brandlist(): Response
    {
       


        return $this->render('main/brands.html.twig');
    }



}
