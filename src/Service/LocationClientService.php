<?php 
    namespace App\Service;
    use App\Entity\Location;
    use App\Entity\Vehicule;
    use App\Entity\Client;

    class LocationClientService
    {
        public function getLocationClient(array $locations): array
        {
            // Initialisation des tableaux pour stocker les véhicules loué par un client
            $resultat = [];
            foreach($locations as $value){  // On parcours la liste des vehicules loués par un client
                // on remplit le tableau resultat avec les informations du véhicule et de la location
                $resultat[] =[
                    'Client ID' => $value->getClient()->getId(),
                    'Catégorie' => $value->getVehicule()->getCategorieVehicule()->getLibelleCategorie(),
                    'immatriculation' => $value->getVehicule()->getImmatriculation(),
                    'Marque' => $value->getVehicule()->getMarque(),
                    'Modèle'=> $value->getVehicule()->getModele(),
                    'Couleur' => $value->getVehicule()->getCouleur(),
                    'Année' => $value->getVehicule()->getAnnee(),
                    'Date debut' => $value->getDateDebut()->format('d-m-Y'),
                    'Date fin' => $value->getDateFin()->format('d-m-Y')
                ];
            }
            return $resultat; // Retourner la liste des véhicules loués par un client
        }
    }
?>