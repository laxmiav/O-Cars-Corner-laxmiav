<?php

namespace App\Controller\Backoffice;

use App\Entity\Car;
use App\Form\Car1Type;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/car")
 */
class CarController extends AbstractController
{
    /**
     * @Route("/", name="app_backoffice_car_index", methods={"GET"})
     */
    public function index(CarRepository $carRepository): Response
    {
        return $this->render('backoffice/car/index.html.twig', [
            'cars' => $carRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_backoffice_car_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CarRepository $carRepository): Response
    {
        $car = new Car();
        $form = $this->createForm(Car1Type::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carRepository->add($car);
            return $this->redirectToRoute('app_backoffice_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/car/new.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_car_show", methods={"GET"})
     */
    public function show(Car $car): Response
    {
        return $this->render('backoffice/car/show.html.twig', [
            'car' => $car,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_backoffice_car_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Car $car, CarRepository $carRepository): Response
    {
        $form = $this->createForm(Car1Type::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carRepository->add($car);
            return $this->redirectToRoute('app_backoffice_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/car/edit.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_backoffice_car_delete", methods={"POST"})
     */
    public function delete(Request $request, Car $car, CarRepository $carRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$car->getId(), $request->request->get('_token'))) {
            $carRepository->remove($car);
        }

        return $this->redirectToRoute('app_backoffice_car_index', [], Response::HTTP_SEE_OTHER);
    }
}
