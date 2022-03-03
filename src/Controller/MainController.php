<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Car;
use App\Repository\CarRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\brand;
use App\Form\CarType;

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
     *  @return Response
     * @Route("/car/add", name="car_add",methods={"GET", "POST"})
     */
  
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $cars = new Car();
        $form = $this->createForm(CarType::class, $cars);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $doctrine->getManager();
            $entityManager->persist($cars);
           
            // dire à doctrine d'exécuter les requêtes
            $entityManager->flush();
            
   
           
            return $this->redirectToRoute('homepage');
        }



        return $this->render('main/add.html.twig', [
            'form' => $form->createView(),
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
     * @Route("/car/delete/{id}", name="cars_delete", requirements={"id"="\d+"}, methods={"GET"})
     */
  
    public function delete(Car $cars, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove( $cars);
        
        // dire à doctrine d'exécuter la requête de suppression
        $entityManager->flush();

        // redirection
       
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
