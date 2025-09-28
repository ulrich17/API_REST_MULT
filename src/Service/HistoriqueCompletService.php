<?php
    namespace App\Service;    
    use App\Entity\Vehicule;
  
    class HistoriqueCompletService
    {
        public function getHistoriqueComplet(Vehicule $vehicule, array $location, array $maintenance): array
        {

            $historiqueMaintenance = []; // Initialisation des tableaux pour stocker les historiques de maintenance            
            $historiqueLocation = []; // Initialisation des tableaux pour stocker les historiques de location
            foreach ($maintenance as $maintenances) { // On parcours la liste des maintenances d'un véhicule spécifique connaissant son id
                $historiqueMaintenance[] = [
                    'id' => $maintenances->getId(),
                    'type_Maintenance' => $maintenances->getTypeMaintenance(),
                    'description' => $maintenances->getDescription(),
                    'date_Planifiee' => $maintenances->getDateplanifie() ? $maintenances->getDateplanifie()->format('d/m/Y') : null,
                    'date_Effectuee' => $maintenances->getDateeffectuee() ? $maintenances->getDateeffectuee()->format('d/m/Y') : null,
                    'cout(en_euros)' => $maintenances->getCout(). ' '.'Euros',
                    'kilometrage' => $maintenances->getKilometrage(),
                    'type_Alerte' => $maintenances->getTypeAlerte(),
                    'observations' => $maintenances->getObservations(),
                ];
            }
            foreach($location as $locations){ // on parcours la liste des locations d'un vehicule spécifique connaissant son id
            $datedebut = $locations->getDatedebut();
            $datefin = $locations->getDatefin();    
            $interval = $datefin->diff($datedebut);
            $nbreJours = $interval->days;    
            $historiqueLocation[] =[
                    'idlocation' => $locations->getId(),
                    'date_debut_location' => $locations->getDateDebut()->format('d-m-Y'),
                    'date_fin_location' => $locations->getDateFin()->format('d-m-Y'),
                    'Nombre_jours' => $nbreJours,
                    'Nom_Prenom_client' => $locations->getClient()->getPrenom().' '.$locations->getClient()->getNom(),
            ];
         }
         return [
                'vehicule' => [ 
                    'id' => $vehicule->getId(),
                    'immatriculation' => $vehicule->getImmatriculation(),
                    'marque' => $vehicule->getMarque(),
                    'modele' => $vehicule->getModele(),
                    'annee' => $vehicule->getAnnee(),
                    'couleur' => $vehicule->getCouleur(),
                    'etat' => $vehicule->getEtat(),
                    'dateEntree' => $vehicule->getDateEntree()->format('d/m/Y'),
                    'dateSortie' => $vehicule->getDateSortie() ? $vehicule->getDateSortie()->format('d/m/Y') : null,
                    'observations' => $vehicule->getObservations(),
                    'categorieVehicule' => $vehicule->getCategorieVehicule()?->getLibelleCategorie(),
                ],
                'historique_Maintenace' => $historiqueMaintenance,
                'historique_Location' => $historiqueLocation
            ];
        }
        
    }

?>