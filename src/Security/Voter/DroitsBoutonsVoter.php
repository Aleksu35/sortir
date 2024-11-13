<?php

namespace App\Security\Voter;

use App\Entity\Sortie;
use App\Repository\EtatRepository; // Assure-toi que ce repository existe
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

    private EtatRepository $etatRepository;

    // Injection du repository Etat dans le constructeur
    public function __construct(EtatRepository $etatRepository)
    {
        $this->etatRepository = $etatRepository;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
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
            self::EDIT => $this->canEdit($sortie, $user),
            self::PUBLISHED => $this->canPublish($sortie, $user),
            self::VIEW => $this->canView($sortie, $user),
            self::DELETE => $this->canDelete($sortie, $user),
            default => throw new \LogicException('Vous ne pouvez pas faire cette action.'),
        };
    }

    private function canEdit(Sortie $sortie, UserInterface $user): bool
    {
        // Ici, on va vérifier si l'état de la sortie n'est pas encore publiée !
        if ($sortie->getOrganisateur() === $user && $this->getEtatLibelle($sortie) !== 'Ouverte') {
            return true;
        }
        return false;
    }

    private function canPublish(Sortie $sortie, UserInterface $user): bool
    {
        // Vérifier si l'utilisateur est le participant et que l'état est créé(=enregistré)
        if ($sortie->getOrganisateur() === $user && $this->getEtatLibelle($sortie) === 'Créée') {
            return true;
        }
        return false;
    }

    private function canView(Sortie $sortie, UserInterface $user): bool
    {
        // L'utilisateur peut voir la sortie si l'état est publié(=ouverte)
        return $this->getEtatLibelle($sortie) === 'Ouverte';
    }

    // Méthode utilitaire pour récupérer le libellé de l'état de la sortie
    private function getEtatLibelle(Sortie $sortie): string
    {
        // Là on récupère l'état qui est forcément lié à la sortie
        $etat = $sortie->getEtat();
        // On retourne le libellé de l'état
        return $etat ? $etat->getLibelle() : '';
    }

    private function canDelete(Sortie $sortie, UserInterface $user): bool
    {
        if($sortie->getOrganisateur() === $user && $this->getEtatLibelle($sortie) === 'Ouverte'){
            return true;
        }
        return false;
    }
}
