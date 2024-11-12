<?php

namespace App\Security\Voter;

use App\Entity\Sortie;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class DroitsBoutonsVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const VIEW = 'POST_VIEW';
    public const DELETE = 'POST_DELETE';
    public const PUBLISHED = 'POST_PUBLISHED';
    public const REGISTER = 'POST_REGISTER';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE, self::PUBLISHED, self::REGISTER])
            && $subject instanceof Sortie;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // On s'assure que l'utilisateur est connecté
        if (!$user instanceof UserInterface) {
            return false;
        }

        $sortie = $subject;

        return match ($attribute) {
            // Seul le créateur peut modifier ou publier ses sorties
            self::EDIT => $this->canEdit($sortie, $user),
            self::PUBLISHED => $this->canPublish($sortie, $user),
            self::VIEW => $this->canView($sortie, $user),
            default => throw new \LogicException('Vous ne pouvez pas faire cette action.')
        };
    }

    private function canEdit(Sortie $sortie, UserInterface $user): bool
    {
        if ($sortie->getParticipant() === $user && $sortie->isPublished()) {
            return true;
        }
        return false;
    }

    private function canPublish(Sortie $sortie, UserInterface $user): bool
    {
        if ($sortie->getParticipant() === $user && $sortie->isPublished(false)) {
            return true;
        }
        return false;
    }

    private function canView(Sortie $sortie, UserInterface $user): bool
    {
        if ($sortie->isPublished() === false) {
            return true;
        }
        return false;
    }

}
