<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

use App\Entity\Car;
use App\Entity\User;

class CarsVoter extends Voter
{
    public const EDIT = 'CAR_EDIT';
    public const VIEW = 'CAR_VIEW';
    public const DELETE = 'CAR_DELETE';
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])) {
            return false;
        }
            // only vote on `Question` objects
            if (!$subject instanceof Car) {
                return false;
            }
    
            return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
       
             
            // utilisateur connecté
            /** @var User */ 
            $user = $token->getUser();
             $car = $subject;
            // if the user is anonymous, do not grant access
            // if ($car->getUser() == $user) {
            //    return true;
            // }
            switch ($attribute) {
                case self::VIEW:
                    return $this->canView($car, $user);
                case self::EDIT:
                    return $this->canEdit($car, $user);
                case self ::DELETE:   
                    return $this->canDelete($car, $user);
            }
           
            throw new \LogicException('Sorry the author can only Modify!');
           // return false;
           
        }
        private function canView(Car $car, User $user): bool
        {
            // if they can edit, they can view
            if ($this->canEdit($car, $user) ) {
                return true;
            }
            if ($this->canDelete($car, $user) ) {
                return true;
            }
    
            
        }
    
        private function canEdit(Car $car, User $user): bool
        {
            if ($car->getUser() == $user) {
                return true;
             }
              // ROLE_SUPER_ADMIN can do anything! The power!
            if ($this->security->isGranted('ROLE_MODERATOR')) {
                return true;
            }
            // ROLE_SUPER_ADMIN can do anything! The power!
            if ($this->security->isGranted('ROLE_ADMIN') ){
                return true;
            }
            return false;
        }
        private function canDelete(Car $car, User $user): bool
        {
            if ($car->getUser() == $user) {
                return true;
             }
              // ROLE_SUPER_ADMIN can do anything! The power!
            if ($this->security->isGranted('ROLE_MODERATOR')) {
                return true;
            }
            // ROLE_SUPER_ADMIN can do anything! The power!
            if ($this->security->isGranted('ROLE_ADMIN') ){
                return true;
            }
            return false;
        }





    }

