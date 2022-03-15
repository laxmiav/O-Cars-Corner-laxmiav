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
     /**
     * @Route("/new", name="backoffice_app_car_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CarRepository $brandRepository,ManagerRegistry $doctrine,Myslugger $slugger): Response
    {
        $cars = new Car();
        $form = $this->createForm(CarType::class, $cars);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            // On associe le user connecté à la question
            $cars->setUser($this->getUser());

            $entityManager = $doctrine->getManager();
            $cars->setSlug($slugger->slugify($cars->getModel()));
            $entityManager->persist($cars);
           
            $this->addFlash('success', 'Thanks for registering your car with us');
            // dire à doctrine d'exécuter les requêtes
            $entityManager->flush();
            
   
           
            return $this->redirectToRoute('backoffice_home');
        }

        return $this->renderForm('backoffice/brand/new.html.twig', [
            'brand' =>  $cars,
            'form' => $form,
        ]);
    }

}
