<?php 
    namespace App\Service;
    use App\Entity\Location;
    use App\Entity\Vehicule;

    class LocationsEncoursService
    {
        public function getLocationsEncours(array $locations): array
        
        {
            $locationsEncours = []; // Filtrer les locations en cours
            $resultat = [];
            $location = $locations;
            // On parcours la liste des vehicules disponible à location
            foreach($location as $value){
            $datedebut = $value->getDateDebut();
            $datefin = $value->getDatefin();
            $vehicule = $value->getVehicule();
            $client = $value->getClient();
            // on determine la duréée de location (on fait la soustraction de la datefin-datedebut )
            $interval = $datefin->diff($datedebut);
            $nbreJours = $interval->days;
            // on recupère la date du jour
            $dateJour = new \DateTime();
            // On détermine le nombre de jours écoulés
            $intervalDatedebut_dateJour = $dateJour->diff($datedebut); 
            $intervalJour_DateDebut_DateJour = $intervalDatedebut_dateJour->days;
                if($nbreJours > $intervalJour_DateDebut_DateJour){
                    
                    $resultat []= [
                    'id' => $value->getId(),
                    'Immatriculation du véhicule' => $vehicule->getImmatriculation(),
                    'Marque' => $vehicule->getMarque(),
                    'Modèle' => $vehicule->getModele(),
                    'Nom & Prenom' => $client->getNom().' '.$client->getPrenom(),
                    'Data debut' => $value->getDateDebut()->format('d-m-Y'),
                    'Date fin' => $value->getDateFin()->format('d-m-Y'),
                    'Nombre de jours' => $nbreJours,
                    'Nombre de jours écoulés' =>$intervalJour_DateDebut_DateJour,
                    ];
                }
               
            }
             return $resultat;  // Retourner la liste des locations en cours
        }
    }
?>