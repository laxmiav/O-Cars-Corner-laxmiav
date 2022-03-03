<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Car;
use App\Repository\CarRepository;
use App\Repository\BrandRepository;
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
           
            $this->addFlash('success', 'Thanks for registering your car with us');
            // dire à doctrine d'exécuter les requêtes
            $entityManager->flush();
            
   
           
            return $this->redirectToRoute('homepage');
        }



        return $this->render('main/add.html.twig', [
            'form' => $form->createView(),
       ]);
    }
    /**
    * @Route("/car/edit/{id}", name="cars_edit")
    */
  
    public function edit(Car $cars,Request $request, ManagerRegistry $doctrine): Response
    {

        $form = $this->createForm(CarType::class, $cars);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $doctrine->getManager();

            $this->addFlash('success', 'Your modification are well registered');
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('main/edit.html.twig', [
            'form' => $form->createView(),
        ]);




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
  
    public function brandlist(BrandRepository $brandRepository): Response

    {
        $brands = $brandRepository->findall();

        return $this->render('main/brands.html.twig',[
            'brands' => $brands,
            
        ]);
    }
     /**
     * @Route("/brand/{id}", name="brand_detail")
     */
  
    public function showbrand(int $id, CarRepository $carRepository, BrandRepository $brandRepository ): Response

    {
        $brandname = $brandRepository->find($id);
        $brand = $carRepository->findOneByIdWithCarBrands($id);
       
        //dd($brand);
        if ( is_null($brand))
        {
            
            throw $this->createNotFoundException('The brand does not exist');
        }
       
        return $this->render('main/brand.html.twig',[
            'brand' => $brand,
            'brandname' =>  $brandname,
            
        ]);
    }
    

}
