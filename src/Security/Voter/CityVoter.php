<?php

namespace App\Security\Voter;

use App\Classes\Person;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CityVoter extends Voter
{
    public const CITY_LILLE = "Lille";
    public const CITY_PARIS = "Paris";
    public const CITY_RENNES = "Rennes";

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CITY_LILLE, self::CITY_PARIS, self::CITY_RENNES])
            && $subject instanceof Person;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if( !$this->security->isGranted('ROLE_SUPER_ADMIN') ) {
            return false;
        }

        $userCityAddress = "Lille"; // une valeur récupéré dans le subject

        if( $attribute == $userCityAddress ) {
            return true;
        }

        return false;
    }
}
