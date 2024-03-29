<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Car;
use App\Entity\User;
use App\Repository\CarRepository;
use App\Repository\BrandRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\brand;
use App\Form\CarType;
use App\Service\Myslugger;
use DateTime;
use Ferrandini\Urlizer;
use Symfony\Component\Form\Extension\Core\Type\FileType; 

class MainController extends AbstractController
{

/**
     * @Route("/", name="homepage")
     */
  
    public function home(CarRepository $carRepository): Response
    {
      

        return $this->render('main/home.html.twig');
    }



    /**
     * @Route("/cars", name="cars_list")
     */
  
    public function list(CarRepository $carRepository): Response
    {
        $cars = $carRepository->findBy([], ['releaseYear' => 'DESC', 'model' => 'ASC']);

        return $this->render('main/list.html.twig', [
            'cars' => $cars
        ]);
    }

   
    /**
     * @return Response
     * @Route("/car/add", name="car_add", methods={"GET", "POST"})
     */
  
    public function add(Request $request, ManagerRegistry $doctrine,Myslugger $slugger): Response
    {
        $cars = new Car();
        $form = $this->createForm(CarType::class, $cars);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
         $createdat = new DateTime();
         $cars->setCreatedAt($createdat);
            // On associe le user connecté à la question
            $cars->setUser($this->getUser());

            $entityManager = $doctrine->getManager();
            $cars->setSlug($slugger->slugify($cars->getModel()));

            $uploadedFile = $form['imageFile']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $cars->setImage($newFilename);
            }


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
  
    public function edit(Car $cars,Request $request, ManagerRegistry $doctrine,Myslugger $slugger): Response
    {
        $this->denyAccessUnlessGranted('CAR_EDIT', $cars);
        $form = $this->createForm(CarType::class, $cars);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {   
            $createdat = new DateTime();
            $cars->setUpdatedAt($createdat);

                // On associe le user connecté à la question
                $cars->setUser($this->getUser());
                $entityManager = $doctrine->getManager();
                $cars->setSlug($slugger->slugify($cars->getModel()));


                $uploadedFile = $form['imageFile']->getData();
                if ($uploadedFile) {
                    $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
                    $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
                    $uploadedFile->move(
                        $destination,
                        $newFilename
                    );
                    $cars->setImage($newFilename);
                }

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
        $this->denyAccessUnlessGranted('CAR_DELETE', $cars);
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


     /**
     * @return Response
     * @Route("/car/{id}", name="car_detail", methods={"GET"})
     */
    public function detail( Car $car, CarRepository $carRepository): Response
    {
      
        $cars = $carRepository->find($car);

        if ( is_null( $cars ))
        {
            
            throw $this->createNotFoundException('The car does not exist');
        }

        return $this->render('main/cardetail.html.twig', [
            'cars' => $cars
        ]);
    }
      /**
     * @return Response
     * @Route("/profile", name="profile", methods={"GET"})
     */
    public function profile( ): Response
    {
      
        // $cars = $carRepository->find($car);

        // if ( is_null( $cars ))
        // {
            
        //     throw $this->createNotFoundException('The car does not exist');
        // }

        return $this->render('profile.html.twig', [
           
        ]);
    }
     /**
     * @return Response
     * @Route("/lessons", name="lessons", methods={"GET"})
     */
    public function lessons( ): Response
    {
      
        

        return $this->render('lessons.html.twig', [
           
        ]);
    }

    
    

}
