<?php 
    namespace App\Service;
    use App\Entity\Maintenance;
    use App\Entity\Vehicule;    

    class MaintenanceUrgenteService
    {
        public function getMaintenanceUrgente(array $maintenance): array
        {
            $maintenancesUrgentes = []; // Filtrer les maintenances urgentes (type d'alerte "Critique")
            // Parcourir la liste des maintenances
            foreach ($maintenance as $maintenances) {
                // Si le type d'alerte est "Critique", ajouter à la liste des maintenances urgentes
                if ($maintenances->getTypeAlerte() === 'Critique') {
                    $maintenancesUrgentes[] = [
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
            }
            return $maintenancesUrgentes;  // Retourner la liste des maintenances urgentes
        }
    }
?>