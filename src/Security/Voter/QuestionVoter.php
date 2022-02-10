<?php

namespace App\Security\Voter;

use App\Entity\Question;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class QuestionVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['EDIT'])
            && $subject instanceof Question;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /**@var User $user */
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }
        if (!$subject instanceof Question){
            throw new \Exception("Invalid argument");
        }

        if (in_array('ROLE_ADMIN',$user->getRoles())) return true;
        switch ($attribute) {
            case 'EDIT':
                return $user === $subject->getOwner();
        }

        return false;
    }
}
