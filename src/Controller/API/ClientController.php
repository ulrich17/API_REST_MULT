<?php 
    namespace App\Controller\API;
    use App\Entity\Client;
    use App\Entity\Location;
    use App\Repository\ClientRepository;
    use App\Repository\LocationRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Validator\Validator\ValidatorInterface;
    use Symfony\Component\Validator\Constraints\DateTime;
    
    class ClientController extends AbstractController
    {
        #[Route('/api/v1/clients', name: 'createClient', methods: ['POST'])]
        public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): Response
        {
            $data = json_decode($request->getContent(), true);
            if (!$data) {
                return $this->json(['message' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
            }
            $client = new Client();
            $client->setNom($data['nom']);
            $client->setPrenom($data['prenom']);
            $client->setDatenaissance(new \DateTime($data['datenaissance']));
            $client->setLieunaissance($data['lieunaissance']);
            $client->setAdressemail($data['adressemail']);
            $client->setTelephone($data['telephone']);
            $client->setAdresse($data['adresse']);
            $client->setCodepostal($data['codepostal']);
            $client->setVille($data['ville']);
            // Validation de l'entité
            $errors = $validator->validate($client);
            if (count($errors) > 0) {
                return $this->json(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
            }
            $em->persist($client);
            $em->flush();
            return $this->Json($client, Response::HTTP_CREATED);
        }
        // Methode qui sert à afficher la liste de tous les clients
        #[Route('/api/v1/clients', name: 'listClients', methods: ['GET'])]
        public function list(EntityManagerInterface $em): JsonResponse
        {
            $clients = $em->getRepository(Client::class)->findAll();

            $listclients = [];

            foreach($clients as $value){
                $listclients[] = [
                    'Id Client' => $value->getId(),
                    'Nom' => $value->getNom(),
                    'Prenom' => $value->getPrenom(),
                    'Date_naissance' => $value->getDateNaissance(),
                    'Lieu_naissance' => $value->getLieuNaissance(),
                    'Adresse_email' => $value->getAdresseMail(),
                    'Telephone' =>  $value->getTelephone(),
                    'Adresse' => $value->getAdresse(),
                    'Code_Postal' => $value->getCodePostal(),
                    'Ville'=> $value->getVille()

                ]; 
            }
            return $this->json($listclients);
        }
        #[Route('/api/v1/clients/{id}', name: 'detailClient', methods: ['GET'])]
        public function detail(int $id, Request $request, EntityManagerInterface $em): Response
        {
            $data = json_decode($request->getContent(), true);
            $client = $em->getRepository(Client::class)->find($id);
            if (!$client) {
                return $this->json(['message' => 'Client non trouvé'], Response::HTTP_NOT_FOUND);
            }
            return $this->json($client);
        }
        #[Route('/api/v1/clients/{id}', name: 'updateClient', methods: ['PUT'])]
        public function update(int $id, Request $request, EntityManagerInterface $em, ClientRepository $clientRepository): JsonResponse
        {
            $client = $clientRepository->find($id);
            if (!$client) {
                return new JsonResponse(['message' => 'Client non trouvé'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);

            $client->setNom($data['nom'] ?? $client->getNom());
            $client->setPrenom($data['prenom'] ?? $client->getPrenom());
            $client->setDatenaissance(isset($data['datenaissance']) ? new \DateTime($data['datenaissance']) : $client->getDatenaissance());
            $client->setLieunaissance($data['lieunaissance'] ?? $client->getLieunaissance());
            $client->setAdressemail($data['adressemail'] ?? $client->getAdressemail());
            $client->setTelephone($data['telephone'] ?? $client->getTelephone());
            $client->setAdresse($data['adresse'] ?? $client->getAdresse());
            $client->setCodepostal($data['codepostal'] ?? $client->getCodepostal());
            $client->setVille($data['ville'] ?? $client->getVille());
            $em->persist($client);
            $em->flush();
            return $this->json($client);

        }
        #[Route('/api/v1/clients/{id}', name: 'deleteclient', methods: ['DELETE'])]
        public function delete(int $id, EntityManagerInterface $em, ClientRepository $clientRepository): JsonResponse
        {
            $client = $clientRepository->find($id);
            if (!$client) {
                return new JsonResponse(['message' => 'Client non trouvé'], Response::HTTP_NOT_FOUND);
            }
            $em->remove($client);
            $em->flush();
            return new JsonResponse(['message' => 'Client supprimé avec succès'], Response::HTTP_OK);
        }
        #[Route('/api/v1/clients/{id}', name: 'patchClient', methods: ['PATCH'])]
        public function patch(int $id, Request $request, EntityManagerInterface $em, ClientRepository $clientRepository): JsonResponse
        {
            $client = $clientRepository->find($id);
            if (!$client) {
                return new JsonResponse(['message' => 'Client non trouvé'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);

            $client->setNom($data['nom'] ?? $client->getNom());
            $client->setPrenom($data['prenom'] ?? $client->getPrenom());
            $client->setDatenaissance(isset($data['datenaissance']) ? new \DateTime($data['datenaissance']) : $client->getDatenaissance());
            $client->setLieunaissance($data['lieunaissance'] ?? $client->getLieunaissance());
            $client->setAdressemail($data['adressemail'] ?? $client->getAdressemail());
            $client->setTelephone($data['telephone'] ?? $client->getTelephone());
            $client->setAdresse($data['adresse'] ?? $client->getAdresse());
            $client->setCodepostal($data['codepostal'] ?? $client->getCodepostal());
            $client->setVille($data['ville'] ?? $client->getVille());
            $em->persist($client);
            $em->flush();
            return $this->Json($client);
        }

        // Historique de locations d'un client
        #[Route('/api/v1/clients/{id}/locations', name:'historiqueLocation', methods:['GET'])]

        public function clientLocation(int $id, ClientRepository $clientsRepository): JsonResponse
        {
            $client = $clientsRepository->find($id);
            if (!$client) {
                return new JsonResponse(['message' => 'Client non trouvé'], Response::HTTP_NOT_FOUND);
            }
            $locations = $client->getLocations(); 
            // initialisation d'un tableau
            $resultat = [];
            foreach($locations as $value){
                $resultat[] =[
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
            return $this->json($resultat);
        }
        

    }