<?php
    namespace App\Controller\API;
    use App\Entity\Location;
    use App\Entity\Client;
    use App\Entity\Vehicule;
    use App\Repository\ClientRepository;
    use App\Repository\LocationRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    
    
    class LocationController extends AbstractController{
        
        #[Route('/api/v1/locations', name: 'createLocation', methods: ['POST'])]
        // Methode pour créer une nouvelle location
        public function createLocation(Request $request, EntityManagerInterface $em): Response
        {
            $data = json_decode($request->getContent(), true);

            if (!$data) {
                return new JsonResponse(['message' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
            }
            // recupère l'objet Client à partir de l'id

            $client = $em->getRepository(Client::Class)->find($data['client_id']);
            if(!$client){
                return new JsonResponse(['message' => 'Client non trouvé dans la base de données']);
            }    
            // On recupère l'objet Vehicule à partir de l'id

            $vehicule = $em->getRepository(Vehicule::class)->find($data['idvehicule']);
            $statutVehicule = $vehicule->getStatut();

            if($statutVehicule !=='Disponible'){
                return new JsonResponse(['message' => 'Véhicule non disponible']);
            }else{
            // Si le véhicule est disponible en créer une nouvelle location

                $location = new Location();
                $location->setVehicule($vehicule);
                // On passe l'objet client
                $location->setClient($client); 
                $location->setDatedebut(new \DateTime($data['datedebut']));
                $location->setDatefin(new \DateTime($data['datefin']));
                //$location->setMontant($data['montant']);

                $em->persist($location);
                // On met ajour le statut de vehicule dans la BDD
                $vehicule->setStatut('Non disponible');
                $em->persist($vehicule);
                $em->flush();
            }    
            return $this->json($location, Response::HTTP_CREATED);
        }

        #[Route('/api/v1/locations', name: 'listLocations', methods: ['GET'])]
        public function listLocations(EntityManagerInterface $em): JsonResponse
        {
            $locations = $em->getRepository(Location::class)->findAll();
            $resultat = [];

            foreach ($locations as $location) {
                $vehicule  = $location->getVehicule();
                $categorie = $vehicule?->getCategorieVehicule();
                $client = $location->getClient();
                // Determination du nombre de jour 
                $datedebut = $location->getDateFin();
                $datefin = $location->getDateDebut();
                if($datedebut && $datefin){
                    $intervaltemps = $datedebut->diff($datefin);
                    $nombre_jour = $intervaltemps->days; 
                }else{
                    $nombre_jour = null;
                }
                $datedebutAffichage = $location->getDateFin()->format('d-m-Y');
                $datefinAffichage = $location->getDateDebut()->format('d-m-Y');
                $resultat[] = [
                    'id location' => $location->getId(),
                    'Nom' => $client->getNom(),
                    'Prenom' => $client->getPrenom(),
                    'immatriculation' => $vehicule->getImmatriculation(),
                    'Categorie' => $categorie->getLibelleCategorie(), 
                    'Marque' => $vehicule->getMarque(),
                    'Modele' => $vehicule->getModele(),
                    'Couleur' => $vehicule->getCouleur(),
                    'Date debut' => $datedebutAffichage,
                    'Date fin' => $datefinAffichage,
                    'Nombre de jours restant' =>$nombre_jour.' '.'Jours'
                    
                ];
            }

            return $this->json($resultat);
        }


        #[Route('/api/v1/locations/en-cours', name:'locationsEncours', methods:['GET'])]
        // Méthode qui affiche des locations en cours
        public function locationEncours(EntityManagerInterface $em): JsonResponse
        {
            $resultat = [];

            $location = $em->getRepository(Location::Class)->findAll();
            $datedebut = $location->$location->getDatedebut();
            $datefin = $location->$location->getDatefin();

            // on cherche à determiner la date de fin de location (on fait la soustraction de la datefin-datedebut )
            
            $datelimite = $datefin - $datedebut;
            
            if($datelimite >0){
                
                $resultat = [
                'id' => $location->getId(),
                'vehicule' => $location->getIdvehicule(),
                'idclient' => $location->getIdclient(),
                'datadebut' => $location->getDatedebut(),
                'datefin' => $location->$location->getDatefin()
                ];
            }
            
        }
       
        #[Route('/api/v1/locations/{id}', name: 'updateLocation', methods: ['PUT'])]
        // Methode pour mettre à jour une location existante
        public function updateLocation(int $id, Request $request, EntityManagerInterface $em): Response
        {
            $location = $em->getRepository(Location::class)->find($id);
            if (!$location) {
                return new JsonResponse(['message' => 'Location non trouvée'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);
            if (!$data) {
                return new JsonResponse(['message' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
            }

            $location->setIdvehicule($data['idvehicule'] ?? $location->getIdvehicule());
            $location->setIdclient($data['idclient'] ?? $location->getIdclient());
            $location->setDatedebut(isset($data['datedebut']) ? new \DateTime($data['datedebut']) : $location->getDatedebut());
            $location->setDatefin(isset($data['datefin']) ? new \DateTime($data['datefin']) : $location->getDatefin());
           // $location->setMontant($data['montant'] ?? $location->getMontant());

            $em->flush();

            return $this->json($location);
        }    
        #[Route('/api/v1/locations/{id}', name: 'deleteLocation', methods: ['DELETE'])]
        // Methode pour supprimer une location
        public function deleteLocation(int $id, EntityManagerInterface $em): Response
        {
            $location = $em->getRepository(Location::class)->find($id);
            if (!$location) {
                return new JsonResponse(['message' => 'Location non trouvée'], Response::HTTP_NOT_FOUND);
            }

            $em->remove($location);
            $em->flush();

            return new JsonResponse(['message' => 'Location supprimée avec succès'], Response::HTTP_NO_CONTENT);
        }
        #[Route('/api/v1/locations/{id}', name: 'detailLocation', methods: ['GET'])]
        // Methode pour obtenir les détails d'une location spécifique
        public function detailLocation(int $id, EntityManagerInterface $em): JsonResponse
        {
            $location = $em->getRepository(Location::class)->find($id);
            if (!$location) {
                return new JsonResponse(['message' => 'Location non trouvée'], Response::HTTP_NOT_FOUND);
            }
            return $this->Json($location);
        }
        #[Route('/api/v1/locations/{id}', name: 'locationpartiel', methods: ['PATCH'])]
        // Methode pour mettre à jour partiellement une location
        public function locationPartiel(int $id, Request $request, EntityManagerInterface $em): Response
        {
            $location = $em->getRepository(Location::class)->find($id);
            if (!$location) {
                return new JsonResponse(['message' => 'Location non trouvée'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);
            if (!$data) {
                return new JsonResponse(['message' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
            }

            $location->setIdvehicule($data['idvehicule'] ?? $location->getIdvehicule());
            $location->setIdclient($data['idclient'] ?? $location->getIdclient());
            $location->setDatedebut(isset($data['datedebut']) ? new \DateTime($data['datedebut']) : $location->getDatedebut());
            $location->setDatefin(isset($data['datefin']) ? new \DateTime($data['datefin']) : $location->getDatefin());
           
            $em->flush();

            return $this->Json($location);
        }   
    }
    
?>