<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Form\FormEvents;

class UserSubscriber implements EventSubscriberInterface
{
    public function onPreSetData($event)
    {
        $form = $event->getForm();
        $user = $event->getData();
        // dd($event);
        if ($user->getId())
        {
            $form->remove('password');
        }
        //  ->remove
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
        ];
    }
}
