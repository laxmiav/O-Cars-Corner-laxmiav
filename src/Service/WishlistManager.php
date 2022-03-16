<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;



class WishlistManager
{
    // @link https://symfony.com/doc/current/session.html#basic-usage
    private $session;
   
    private $logger;
   
    private $emptyEnabled;

    public function __construct(RequestStack $requestStack, LoggerInterface $logger, bool $emptyEnabled)
    {
        $this->session = $requestStack->getSession();
        $this->logger = $logger;
        $this->emptyEnabled = $emptyEnabled;
    }

    /**
     * Add or Remove a car from the list
     */
    public function toggle($car)
    {
        // 1. On récupère les favoris en session, ou un tableau vide sinon
        $mywishlist = $this->session->get('mywishlist', []);

        // 2. On met à jour le tableau récupéré

        // Si l'index du film est déjà dans les favoris
        if (array_key_exists($car->getId(), $mywishlist)) {
            // On le retire via PHP unset(élément)
            unset($mywishlist[$car->getId()]);
            // Action à retourner au contrôleur
            $action = 'remove';
            // Log
            $this->logger->info('wishlist, car removed', ['car' => $car->getModel()]);
        } else {
            // Sinon on l'ajoute
            $mywishlist[$car->getId()] = $car;
            // Action à retourner au contrôleur
            $action = 'add';
            // Log
            $this->logger->info('wishlist, car added', ['car' => $car->getModel()]);
        }

        // 3. On écrase les favoris en session
        $this->session->set('mywishlist', $mywishlist);

        return $action;
    }

    /**
     * Empty list
     */
    public function empty()
    {
        // Le service est-il configuré pour autorisé le vidage ?
        if (!$this->emptyEnabled) {
            return false;
        }

        $this->session->remove('mywishlist');

        return true;
    }
}