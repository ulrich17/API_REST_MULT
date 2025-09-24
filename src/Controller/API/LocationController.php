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
                    'id_location' => $location->getId(),
                    'Nom et Prenon' => $client->getNom().' '.$client->getPrenom(),
                    'immatriculation' => $vehicule->getImmatriculation(),
                    'Categorie' => $categorie->getLibelleCategorie(), 
                    'Marque' => $vehicule->getMarque(),
                    'Modele' => $vehicule->getModele(),
                    'Couleur' => $vehicule->getCouleur(),
                    'Date debut' => $datedebutAffichage,
                    'Date fin' => $datefinAffichage,
                    'Nbre_jours' =>$nombre_jour.' '.'Jours'
                    
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
             return $this->json($resultat);
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
        // Modification partielle d'une location
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
            
            if(isset($data['idvehicule'])){
                 $location->setIdvehicule($data['idvehicule'] ?? $location->getIdvehicule());
            }
            if(isset($data['idclient'])){
            $location->setIdclient($data['idclient'] ?? $location->getIdclient());
            }
            if(isset($data['datedebut'])){
            $location->setDatedebut(isset($data['datedebut']) ? new \DateTime($data['datedebut']) : $location->getDatedebut());
            }
            if(isset($data['datefin'])){
            $location->setDatefin(isset($data['datefin']) ? new \DateTime($data['datefin']) : $location->getDatefin());
            }    
            $em->flush();

            return $this->Json($location);
        }   

        // Met à jour le statut d'un véhicule en "disponible" lorsque la période de location est terminée
        #[Route('/api/v1/locations/{id}/mise-jour-statut-vehciule', name:'updateStatutVehicule', methods:['PATCH'])]
        public function updateStatutVehicu(int $id, Request $request, EntityManagerInterface $em):Response
        {
            // On recupère la location correspondante en fonction de son id
            $location = $em->getRepository(Location::class)->find($id);
            $data = json_decode($request->getContent(), true);
            
            // on récupère la date de début et la date de fin
            $datedebut = $location->getDateDebut();
            $datefin = $location->getDateFin();
            $interval = $datefin->diff($datedebut);
            // nombre de jour    
            $nbreJours = $interval->days;
            // on recupère la date du jour
            $dateJour = new \DateTime();
            // On détermine le nombre de jours écoulés
            $intervalDatedebut_dateJour = $dateJour->diff($datedebut); 
            $nombre_jour_Ecoule = $intervalDatedebut_dateJour->days;
            // difference entre nombre de jour de location - nombre de jours écoulé
            $difnbre =  $nbreJours - $nombre_jour_Ecoule;
            // on recupère l'id du véhicule
            $idvehicule = $location->getVehicule()->getId();
            // On recupère les informations du véhicule en fonction de son id 
            $vehicule = $em->getRepository(Vehicule::class)->find($idvehicule);

            if( $difnbre > 0 ){
                return new JsonResponse(
                    [
                        'message' => 'Vous ne pouvez pas mettre à jour le statut du véhicule car la location est en cours'
                    ]
                    );
            }
            $vehicule->setStatut($data['statut']?? $vehicule->getStatut());
            $em->persist($vehicule);
            $em->flush();
            // On renvoie un message à l'utilisateur
            return new JsonResponse(['message'=> 'Mise à jour du statut du vehicule immatriculé'.' '.$vehicule->getImmatriculation().' '.'effectuée avec succès']);
            return $this->Json($vehicule);
        }

    }
    
?>