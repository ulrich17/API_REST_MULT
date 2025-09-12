<?php
    namespace App\Controller\API;   
    use App\Entity\Vehicule;
    use App\Entity\CategorieVehicule;
    use Doctrine\ORM\Mapping as ORM;
    use App\Entity\CategoriVehicule;
    use App\Repository\VehiculeRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;   
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Validator\Validator\ValidatorInterface;
    use Symfony\Component\Validator\Constraints as Assert;
    use Knp\Component\Pager\PaginatorInterface;
    use Symfony\Component\Serializer\SerializerInterface;   
    
   // #[Route('/api/vehicules', name: 'api_vehicules_')]
    class VehiculeController extends AbstractController
    {
        #[Route('/api/v1/vehicules', name: 'createvehicule', methods: ['POST'])]
        public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): Response
        {
            $data = json_decode($request->getContent(), true);
            //dd($data);
            if(!$data) {
                return $this->json(['message' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
            }
            // Recupérer la catégorie de véhicule à partir de l'ID fourni
            $categorieVehicule = $em->getRepository(CategorieVehicule::class)->find($data['categorie_vehicule_id']);
            
            if (!$categorieVehicule) {
                return $this->json(['message' => 'Catégorie de véhicule non trouvée'], Response::HTTP_NOT_FOUND);
            }

            $vehicule = new Vehicule();
            $vehicule->setCategorieVehicule($categorieVehicule);
            $vehicule->setImmatriculation($data['immatriculation']);
            $vehicule->setMarque($data['marque']);
            $vehicule->setModele($data['modele']);
            $vehicule->setAnnee($data['annee']);
            $vehicule->setCouleur($data['couleur']);
            $vehicule->setEtat($data['etat']);
            $vehicule->setDateEntree(new \DateTime($data['dateEntree']));
            $vehicule->setDateSortie(new \DateTime($data['dateSortie']));
            $vehicule->setObservations($data['observations']);
            // Validation de l'entité
            $errors = $validator->validate($vehicule);
            if (count($errors) > 0) {
                return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }
            $em->persist($vehicule);
            $em->flush();

            return $this->json($vehicule, Response::HTTP_CREATED);
        }
          #[Route('/api/v1/vehicules/search', name: 'searchVehicules', methods: ['GET'])]
        public function search(Request $request, VehiculeRepository $vehiculeRepository): JsonResponse
        {
            // Récupération des paramètres de recherche
            $immatriculation = $request->query->get('immatriculation');
            $marque = $request->query->get('marque');
            $modele = $request->query->get('modele');

            // Vérification qu'au moins un paramètre est fourni
            if (!$immatriculation && !$marque && !$modele) {
                return $this->json(
                    ['message' => 'Au moins un paramètre de recherche doit être fourni'], 
                    Response::HTTP_BAD_REQUEST
                );
            }

            // Appel à la méthode du repository
            $vehicules = $vehiculeRepository->searchVehicules($immatriculation, $marque, $modele);

            // Mapping des résultats pour retourner un JSON contrôlable
            $data = array_map(function(array $v) {
                return [
                    'id' => $v['id'],
                    'immatriculation' => $v['immatriculation'],
                    'marque' => $v['marque'],
                    'modele' => $v['modele'],
                    'annee' => $v['annee'],
                    'couleur' => $v['couleur'],
                    'etat' => $v['etat'],
                    'dateEntree' => $v['date_entree'],
                    'dateSortie' => $v['date_sortie'],
                    'observations' => $v['observations'],
                    'categorieVehicule_id' => $v['categorie_vehicule_id'],
                ];
                }, $vehicules
            );
            // retourne un le resulat sous format JSON
            return $this->json($data);
        }

       #[Route('/api/v1/vehicules', name: 'list', methods: ['GET'])]
        public function list(VehiculeRepository $repository,Request $request,EntityManagerInterface $em): JsonResponse
        {
            $vehicules = $em->getRepository(Vehicule::class)->findAll();
            $page = $request->query->getInt('page', 1); // Numéro de page, par défaut 1
            $limit = 1;
            $paginateV= $repository->paginateVehicules($page);
            foreach ($paginateV as $vehicule) {
                $response['items'][] = [

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
                ];
            }
            // serialization avec des groupes pour ne renvoyer que le libellé de la catégorie      
            return $this->json(
                $response,
                Response::HTTP_OK,
                [],
                ['groups' => 'vehicule:list']);
        }
        
        // Route permettant d'afficher tous les véhicules disponibles
        #[Route('/api/v1/vehicules/disponibles', name: 'vehiculesdisponibles', methods: ['GET'])]

        // Méthode permettant d'afficher tous les véhicules disponibles
        public function disponibles(VehiculeRepository $vehiculeRepository): JsonResponse
        {
            $vehicule = $vehiculeRepository->findAll();
                
            // déclaration d'un tableau vide
            $response = ['items' => []];

            foreach ($vehicule as $value){
                $statut = $value->getStatut();

                if($statut ==='Disponible'){
                   $response['items'][] = [

                    'id' => $value->getId(),
                    'categorieVehicule' => $value->getCategorieVehicule()?->getLibelleCategorie(), 
                    'immatriculation' => $value->getImmatriculation(),
                    'marque' => $value->getMarque(),
                    'modele' => $value->getModele(),
                    'annee' => $value->getAnnee(),
                    'couleur' => $value->getCouleur(),
                    'etat' => $value->getEtat(),
                    'dateEntree' => $value->getDateEntree()->format('d/m/Y'),
                    'dateSortie' => $value->getDateSortie() ? $value->getDateSortie()->format('d/m/Y') : null, 
                     'Observations' => $value->getObservations(),   
                    ];
                }
                
                

            }
            return $this->json($response, 200, [], ['groups' => 'vehicules:list']);
        }

        #[Route('/api/v1/vehicules/{immatriculation}', name: 'detail', methods: ['GET'])]
        public function detail(string $immatriculation, Request $request, EntityManagerInterface $em): Response
        {
            $data = json_decode($request->getContent(), true);
            $vehicule = $em->getRepository(Vehicule::class)->findOneBy(['immatriculation' => $immatriculation]);
            if (!$vehicule) {
                return $this->json(['message' => 'Véhicule non trouvé'], Response::HTTP_NOT_FOUND);
            }
            return $this->json($vehicule);
        }

        #[Route('/api/v1/vehicules/{id}', name: 'update', methods: ['PUT'])]
        public function update(string $immatriculation, Request $request, EntityManagerInterface $em, VehiculeRepository $vehiculeRepository): JsonResponse
        {
            $vehicule = $vehiculeRepository->find($immatriculation);
            if (!$vehicule) {
                return new JsonResponse(['message' => 'Véhicule non trouvé'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);
            $categorieVehicule = $em->getRepository(CategorieVehicule::class)->find($data['categorie_vehicule_id']);

            $vehicule->setCategorieVehicule($categorieVehicule);
            $vehicule->setImmatriculation($data['immatriculation']);
            $vehicule->setMarque($data['marque'] ?? $vehicule->getMarque());
            $vehicule->setModele($data['modele'] ?? $vehicule->getModele());
            $vehicule->setAnnee($data['annee'] ?? $vehicule->getAnnee());
            $vehicule->setCouleur($data['couleur'] ?? $vehicule->getCouleur());
            $vehicule->setEtat($data['etat'] ?? $vehicule->getEtat());
            if (isset($data['date_entree'])) {
                $vehicule->setDateEntree(new \DateTime($data['date_entree']));
            }
            if (isset($data['date_sortie'])) {
                $vehicule->setDateSortie(new \DateTime($data['date_sortie']));
            }
            $vehicule->setObservations($data['observations'] ?? $vehicule->getObservations());
            $vehicule->setEtat($data['statut'] ?? $vehicule->getStatut());
            $em->persist($vehicule);
            $em->flush();

            return new JsonResponse($vehicule, Response::HTTP_OK);
        }
        #[Route('/api/v1/vehicules/{id}', name: 'delete', methods: ['DELETE'])]
        public function delete(string $immatriculation, EntityManagerInterface $em, VehiculeRepository $vehiculeRepository): JsonResponse
        {
            $vehicule = $vehiculeRepository->find($immatriculation);
            if (!$vehicule) {
                return new JsonResponse(['message' => 'Véhicule non trouvé'], Response::HTTP_NOT_FOUND);
            }

            $em->remove($vehicule);
            $em->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        
        #[Route('/api/v1/vehicules/{id}', name: 'patchVehicule', methods: ['PATCH'])]
        public function patch(int $id, Request $request, EntityManagerInterface $em, VehiculeRepository $vehiculeRepository): JsonResponse
        {
            $vehicule = $vehiculeRepository->find($id);
            if (!$vehicule) {
                return new JsonResponse(['message' => 'Véhicule non trouvé'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);
            
            if(isset($data['categorie_vehicule_id']) && $data['categorie_vehicule_id'] !== null) {
                // Recupérer la catégorie de véhicule à partir de l'ID fourni
                $categorieVehicule = $em->getRepository(CategorieVehicule::class)->find($data['categorie_vehicule_id']);
                $vehicule->setCategorieVehicule($categorieVehicule);
            }   
            
            if (isset($data['immatriculation']) && $data['immatriculation'] !== null) {
                $vehicule->setImmatriculation($data['immatriculation']);
            }
            if (isset($data['marque'])) {
                $vehicule->setMarque($data['marque']);
            }
            if (isset($data['modele'])) {
                $vehicule->setModele($data['modele']);
            }

            if (isset($data['annee'])) {
                $vehicule->setAnnee($data['annee']);
            }

            if (isset($data['couleur'])) {
                $vehicule->setCouleur($data['couleur']);
            }
            if (isset($data['etat'])) {
                $vehicule->setEtat($data['etat']);
            }
           
            if (isset($data['date_entree'])) {
                $vehicule->setDateEntree(new \DateTime($data['date_entree']));
            }
            if (isset($data['date_sortie'])) {
                $vehicule->setDateSortie(new \DateTime($data['date_sortie']));
            }
            if (isset($data['observations'])) {
                $vehicule->setObservations($data['observations']);
            }
            if(isset($data['statut'])){
                $vehicule->setStatut($data['statut']);
            }
            $em->persist($vehicule);
            $em->flush();
            if ($vehicule){
                return new JsonResponse(['message' => 'Mise à jour effectuée !']);
            }
            return new JsonResponse($vehicule, Response::HTTP_OK);
        
        }
    }
?>