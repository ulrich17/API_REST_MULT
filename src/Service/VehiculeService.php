<?php
namespace App\Service;
use App\Entity\Vehicule;

class VehiculeService
{
    // Retourne les véhicules disponibles à partir d'un tableau d'objets Vehicule
    public function getVehiculesDisponibles(array $vehicules): array
    {
        $response = ['items' => []];

        foreach ($vehicules as $v) {
            if ($v->getStatut() === 'Disponible') {
                $response['items'][] = [
                    'id' => $v->getId(),
                    'marque' => $v->getMarque(),
                    'modele' => $v->getModele(),
                    'annee' => $v->getAnnee(),
                    'couleur' => $v->getCouleur(),
                    'immatriculation' => $v->getImmatriculation(),
                    'dateEntree' => $v->getDateEntree()->format('Y-m-d'),
                    'dateSortie' => $v->getDateSortie()->format('Y-m-d'),
                    'Statut' => $v->getStatut(),
                ];
            }
        }
        return $response;
    }
}
?>