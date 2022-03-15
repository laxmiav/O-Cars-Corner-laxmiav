<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Form\FormEvents;
use App\Service\Myslugger;
use Doctrine\ORM\Events;
use App\Entity\Car;

class CarSubscriber implements EventSubscriberInterface
{
    private $slugger;

    public function __construct(Myslugger $slugger)
    {
        $this->slugger = $slugger;

    }

    public function preUpdate(PreUpdateEventArgs $event)
    {
        $car = $event->getObject();

        if ($car instanceof car)
        {
            $slug = $this->slugger->slugify($car->getModel());
            $car->setSlug($slug);
        }
    }

    public static function getSubscribedEvents() 
    {
        return [
            Events::preUpdate,
        ];
    }
}
