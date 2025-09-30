<?php 
    namespace App\Service;
    use App\Entity\Maintenance;
    use App\Entity\Vehicule;    

    class ProgrammerMaintenanceService
    {
        public function getProgrammerMaintenance(array $maintenance): array
        {
            $maintenancesProgrammes = []; // initialisation du tableau pour stocker les maintenances programmées 
            // Parcourir la liste des maintenances
            foreach ($maintenance as $maintenances) {                
                // Si le type d'alerte est "Normale", ajouter à la liste des maintenances programmées
                    $maintenancesProgrammes[] = [
                        'id' => $maintenances->getIdVehicule(),
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
            return $maintenancesProgrammes;  // Retourner la liste des maintenances programmées
        }  
    }   
?>