<?php

namespace App\Controller\Backoffice;

use App\Entity\Car;
use App\Form\CarType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Repository\CarRepository;
use App\Repository\BrandRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\brand;

use App\Service\Myslugger;
use DateTime;
use Ferrandini\Urlizer;
use Symfony\Component\Form\Extension\Core\Type\FileType; 
/**
 * @Route("/backoffice/car")
 */
class CarController extends AbstractController
{
    /**
     * @Route("/", name="backoffice_app_car_index", methods={"GET"})
     */
    public function index(CarRepository $carRepository): Response
    {
        return $this->render('backoffice/car/index.html.twig', [
            'cars' => $carRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="backoffice_app_car_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CarRepository $carRepository, ManagerRegistry $doctrine, Myslugger $slugger): Response
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
            




            return $this->redirectToRoute('backoffice_app_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/car/new.html.twig', [
            'car' => $cars,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="backoffice_app_car_show", methods={"GET"})
     */
    public function show(Car $car): Response
    {
        return $this->render('backoffice/car/show.html.twig', [
            'car' => $car,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backoffice_app_car_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Car $cars, CarRepository $carRepository,ManagerRegistry $doctrine, Myslugger $slugger): Response
    {
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



            return $this->redirectToRoute('backoffice_app_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/car/edit.html.twig', [
            'car' => $cars,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="backoffice_app_car_delete", methods={"POST"})
     */
    public function delete(Request $request, Car $car, CarRepository $carRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$car->getId(), $request->request->get('_token'))) {
            $carRepository->remove($car);
        }

        return $this->redirectToRoute('backoffice_app_car_index', [], Response::HTTP_SEE_OTHER);
    }
}
