<?php

namespace App\Controller\Backoffice;

use App\Entity\Car;
use App\Form\Car1Type;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

use App\Service\Myslugger;

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
        $form = $this->createForm(Car1Type::class, $cars);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $carRepository->add($car);
            // On associe le user connecté à la question
            $cars->setUser($this->getUser());

            $entityManager = $doctrine->getManager();
            $cars->setSlug($slugger->slugify($cars->getModel()));
            $entityManager->persist($cars);

            $this->addFlash('success', 'New car registered well!');
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
    public function edit(Request $request, Car $car, CarRepository $carRepository): Response
    {
        $form = $this->createForm(Car1Type::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carRepository->add($car);
            return $this->redirectToRoute('backoffice_app_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/car/edit.html.twig', [
            'car' => $car,
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
