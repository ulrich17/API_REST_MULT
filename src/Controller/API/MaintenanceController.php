<?php 
    namespace App\Controller\API;
    use App\Entity\Maintenance;
    use App\Repository\MaintenanceRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Validator\Validator\ValidatorInterface;
    
    class MaintenanceController extends AbstractController
    {
        // Methode permettant de créer une maintenance
        #[Route('/api/v1/maintenances', name: 'createMaintenance', methods: ['POST'])]
        public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): Response
        {
            $data = json_decode($request->getContent(), true);
            if (!$data) {
                return $this->json(['message' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
            }
            $maintenance = new Maintenance(); // On crée une maintenance
            $maintenance->setIdVehicule($data['idVehicule']);
            $maintenance->setTypeMaintenance($data['typemaintenance']);
            $maintenance->setDescription($data['description']);
            $maintenance->setDateplanifie(new \DateTime($data['dateplanifie']));
            $maintenance->setDateeffectuee(new \DateTime($data['dateeffectuee']));
            $maintenance->setCout($data['cout']);
            $maintenance->setKilometrage($data['kilometrage']);
            $maintenance->setTypeAlerte($data['typealerte']); 
            $maintenance->setObservations($data['observations']);
            $errors = $validator->validate($maintenance); // Validation de l'entité 
            if (count($errors) > 0) {
                return $this->json(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
            }
            $em->persist($maintenance);
            $em->flush();
            return $this->json($maintenance, Response::HTTP_CREATED);
        }
        #[Route('/api/v1/maintenances', name: 'listMaintenances', methods: ['GET'])]
        public function list(EntityManagerInterface $em): JsonResponse
        {
            $maintenances = $em->getRepository(Maintenance::class)->findAll();
            return $this->json($maintenances);
        }
        
        // Méthode qui sert à afficher les maintenances urgentes
        #[Route('/api/v1/maintenances/alertes', name: 'listMaintenanceUrgentes', methods: ['GET'])]
        public function listMaintenanceUrgentes(EntityManagerInterface $em): JsonResponse
        {
            $maintenancesUrgentes = $em->getRepository(Maintenance::class)->findAll();
            // On initialise un tableau vide
            $listemaintenance = [];
            foreach($maintenancesUrgentes as $value){
                $alerteUrgentes = $value->getTypeAlerte();
                if($alerteUrgentes =='Critique'){
                    $listemaintenance[] = [
                       'idvehicule'=> $value->getIdVehicule(), 
                       'Type'=> $value->getTypeMaintenance(), 
                       'Description'=> $value->getDescription(), 
                       'Date planifiée'=> $value->getDatePlanifie()->format('d-m-Y'), 
                       'Date effectué'=> $value->getDateEffectuee()->format('d-m-Y'), 
                       'Coût'=> $value->getCout(), 
                       'Kilometrage'=> $value->getKilometrage(), 
                       'Type d\'alerte'=> $value->getTypeAlerte(), 
                    ]; 
                }
            }
            if(empty($listemaintenance)){
                return new JsonResponse(
                    ['message' => 'Aucune alarte critique n\'est présente dans la BDD'], 
                    Response::HTTP_NOT_FOUND
                );
            }
            return $this->json($listemaintenance);
        }
        //Route de la méthode permettant d'afficher les details d'une maintenance 
        #[Route('/api/v1/maintenances/{id}', name: 'detailMaintenance', methods: ['GET'])]

        // permettant d'afficher les details d'une maintenance 
        public function detail(int $id, Request $request, EntityManagerInterface $em): Response
        {
            $data = json_decode($request->getContent(), true);
            $maintenance = $em->getRepository(Maintenance::class)->find($id);
            if (!$maintenance) {
                return $this->json(['message' => 'Maintenance non trouvée'], Response::HTTP_NOT_FOUND);
            }
            return $this->json($maintenance);
        }

        // Mise à jour total d'une maintenance
        #[Route('/api/v1/maintenances/{id}', name: 'updateMaintenance', methods: ['PUT'])]
        public function update(int $id, Request $request, EntityManagerInterface $em, MaintenanceRepository $maintenanceRepository): JsonResponse
        {
            $maintenance = $maintenanceRepository->find($id);
            if (!$maintenance) {
                return $this->json(['message' => 'Maintenance non trouvée'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);
            $maintenance->setIdVehicule($data['idVehicule'] ?? $maintenance->getIdVehicule());
            $maintenance->setDateMaintenance(isset($data['dateMaintenance']) ? new \DateTime($data['dateMaintenance']):$maintenance->getDateMaintenance());
            $maintenance->setTypeMaintenance($data['typeMaintenance'] ?? $maintenance->getTypeMaintenance());
            $maintenance->setDescription($data['description'] ?? $maintenance->getDescription());
            $maintenance->setCout($data['cout'] ?? $maintenance->getCout());
            $maintenance->setStatut($data['statut'] ?? $maintenance->getStatut());
            $maintenance->setObservations($data['observations'] ?? $maintenance->getObservations());
            $maintenance->setTypeAlerte($data['typealerte'] ?? $maintenance->getTypeAlerte());
            $em->persist($maintenance);
            $em->flush();
            return $this->json($maintenance);
        }

        // Suppression d'une maintenance
        #[Route('/api/v1/maintenances/{id}', name: 'deleteMaintenance', methods: ['DELETE'])]
        public function delete(int $id, EntityManagerInterface $em, MaintenanceRepository $maintenanceRepository): JsonResponse
        {
            $maintenance = $maintenanceRepository->find($id);
            if (!$maintenance) {
                return new JsonResponse(['message' => 'Maintenance non trouvée'], Response::HTTP_NOT_FOUND);
            }
            $em->remove($maintenance);
            $em->flush();
            return new JsonResponse(['message' => 'Maintenance supprimée avec succès'], Response::HTTP_OK);
        }
        // Mise à jour partiel d'une maintenance 
        #[Route('/api/v1/maintenances/{idmaintenance}', name: 'maintenancesPartielle', methods: ['PATCH'])]
        public function updatePartial(int $idmaintenance,Request $request,EntityManagerInterface $em,MaintenanceRepository $maintenanceRepository): JsonResponse
        {
            $maintenance = $maintenanceRepository->find($idmaintenance);
            if (!$maintenance) {
                return new JsonResponse(['message' => 'Maintenance non trouvée'], Response::HTTP_NOT_FOUND);
            }
            $data = json_decode($request->getContent(), true);
            if (isset($data['idVehicule'])) {
               $maintenance->setIdVehicule($data['idVehicule'] ?? $maintenance->getIdVehicule());
            }
            if (isset($data['dateMaintenance'])) {
                $maintenance->setDateMaintenance(new \DateTime($data['dateMaintenance']));
            }
            if (isset($data['typemaintenance'])) {
                $maintenance->setTypeMaintenance($data['typemaintenance'] ?? $maintenance->getTypemaintenance());
            }
            if(isset($data['description'])) {
                $maintenance->setDescription($data['description'] ?? $maintenance->getDescription());
            }       
            if(isset($data['cout'])) {
                $maintenance->setCout($data['cout'] ?? $maintenance->getCout());
            }
            if(isset($data['statut'])) {
                $maintenance->setStatut($data['statut'] ?? $maintenance->getStatut());
            }
            if(isset($data['observations'])) {
                $maintenance->setObservations($data['observations'] ?? $maintenance->getObservations());
            }
            if(isset($data['typealerte'])){
                $maintenance->setTypeAlerte($data['typealerte'] ?? $maintenance->getTypeAlerte());
            }
            $em->persist($maintenance);
            $em->flush();
            return $this->json($maintenance);
        }
    }

?>