<?php 
    namespace App\Controller\API;
    use App\Entity\Sinistre;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    class SinistreController extends AbstractController
    {
        #[Route('/api/v1/sinistres', name: 'createsinistre', methods: ['POST'])]
        public function create(Request $request, EntityManagerInterface $em): Response
        {
            $data = json_decode($request->getContent(), true);
            try {
                $sinistre = new Sinistre();
                $sinistre->setIdVehicule($data['idVehicule']);
                $sinistre->setDateSinistre(new \DateTime($data['dateSinistre']));
                $sinistre->setLieu($data['lieu']);
                $sinistre->setDescription($data['description']);
                $sinistre->setEvaluationDegat($data['evaluationDegat']);
                $sinistre->setMontantEstime($data['montantEstime']);
                $sinistre->setStatut($data['statut']);
                $sinistre->setObservations($data['observations']);

                $em->persist($sinistre);
                $em->flush();
                return new Response('Sinistre créé avec succès', Response::HTTP_CREATED);

            } catch (UniqueConstraintViolationException $e) {
                return $this->json(
                    ['error' => 'Vous ne pouvez pas enregistrer ce sinistre, car ce sinistre existe déjà dans la base de données'], 
                         Response::HTTP_BAD_REQUEST
                    );
            }
        }
        #[Route('/api/v1/sinistres', name: 'listsinistres', methods: ['GET'])]
        public function list(EntityManagerInterface $em): Response
        {
            $sinistres = $em->getRepository(Sinistre::class)->findAll();
            $data = [];
            foreach ($sinistres as $sinistre) {
                $data[] = [
                    'id' => $sinistre->getId(),
                    'idVehicule' => $sinistre->getIdVehicule(),
                    'dateSinistre' => $sinistre->getDateSinistre()->format('Y-m-d'),
                    'lieu' => $sinistre->getLieu(),
                    'description' => $sinistre->getDescription(),
                    'evaluationDegat' => $sinistre->getEvaluationDegat(),
                    'montantEstime' => $sinistre->getMontantEstime(),
                    'statut' => $sinistre->getStatut(),
                    'observations' => $sinistre->getObservations(),
                ];
            }

            return $this->json($data);
        }

        #[Route('/api/v1/sinistres/{id}', name: 'detailsinistre', methods: ['GET'])]
        public function detail(int $id, EntityManagerInterface $em): Response
        {
            $sinistre = $em->getRepository(Sinistre::class)->find($id);
            if (!$sinistre) {
                return new JsonResponse(['message' => 'Ce sinistre n\'existe pas dans la base de données'], Response::HTTP_NOT_FOUND);
            }
            return $this->json($sinistre);
        }
        #[Route('/api/v1/sinistres/{id}', name: 'updatesinistre', methods: ['PUT'])]
        public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
        {
            $sinistre = $em->getRepository(Sinistre::class)->find($id);
            if (!$sinistre) {
                return $this->json(['message' => 'sinistre inexistant dans la base de données'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);

            $sinistre->setIdVehicule($data['idVehicule'] ?? $sinistre->getIdVehicule());
            $sinistre->setDateSinistre(isset($data['dateSinistre']) ? new \DateTime($data['dateSinistre']) : $sinistre->getDateSinistre());
            $sinistre->setLieu($data['lieu'] ?? $sinistre->getLieu());
            $sinistre->setDescription($data['description'] ?? $sinistre->getDescription());
            $sinistre->setEvaluationDegat($data['evaluationDegat'] ?? $sinistre->getEvaluationDegat());
            $sinistre->setMontantEstime($data['montantEstime'] ?? $sinistre->getMontantEstime());
            $sinistre->setStatut($data['statut'] ?? $sinistre->getStatut());
            $sinistre->setObservations($data['observations'] ?? $sinistre->getObservations());

            try {
                $em->persist($sinistre);
                $em->flush();
                return $this->json(['message' => 'Sinistre mis à jour avec succès'], Response::HTTP_OK);
            } catch (UniqueConstraintViolationException $e) {
                return $this->json(['error' => 'Vous ne pouvez pas mettre à jour ce sinistre, car ce sinistre existe déjà dans la base de données'], Response::HTTP_BAD_REQUEST);
            }
        }
        #[Route('/api/v1/sinistres/{id}', name: 'deletesinistre', methods: ['DELETE'])]
        public function delete(int $id, EntityManagerInterface $em): JsonResponse
        {
            $sinistre = $em->getRepository(Sinistre::class)->find($id);
            if (!$sinistre) {
                return $this->json(['message' => 'Ce sinistre n\'existe pas dans la base de données'], Response::HTTP_NOT_FOUND);
            }

            $em->remove($sinistre);
            $em->flush();

            return $this->json(['message' => 'Sinistre supprimé avec succès'], Response::HTTP_OK);
        }
        // Mise à jour partielle
        #[Route('/api/v1/sinistres/{id}', name: 'patchsinistre', methods: ['PATCH'])]
        public function patch(int $id, Request $request, EntityManagerInterface $em): JsonResponse
        {
            $sinistre = $em->getRepository(Sinistre::class)->find($id);
            if (!$sinistre) {
                return $this->json(['message' => 'Ce sinistre n\'existe pas dans la base de données'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);
            if (isset($data['idVehicule'])) {
               $sinistre->setIdVehicule($data['idVehicule'] ?? $sinistre->getIdVehicule());
            }
            if (isset($data['dateSinistre'])) {
               $sinistre->setDateSinistre(new \DateTime($data['dateSinistre']));
            }
            if (isset($data['lieu'])) {
               $sinistre->setLieu($data['lieu'] ?? $sinistre->getLieu());
            }   
            if (isset($data['description'])) {
               $sinistre->setDescription($data['description'] ?? $sinistre->getDescription());
            }
            if(isset($data['evaluationDegat'])) {
               $sinistre->setEvaluationDegat($data['evaluationDegat'] ?? $sinistre->getEvaluationDegat());
            }
            if(isset($data['montantEstime'])) {
               $sinistre->setMontantEstime($data['montantEstime'] ?? $sinistre->getMontantEstime());
            }
            if(isset($data['statut'])) {
               $sinistre->setStatut($data['statut'] ?? $sinistre->getStatut());
            }
            if(isset($data['observations'])) {
               $sinistre->setObservations($data['observations'] ?? $sinistre->getObservations());
            }

            try {
                $em->persist($sinistre);
                $em->flush();
                return $this->json(['message' => 'Sinistre mis à jour avec succès'], Response::HTTP_OK);
            } catch (UniqueConstraintViolationException $e) {
                return $this->json(['error' => 'Vous ne pouvez pas mettre à jour ce sinistre, car ce sinistre existe déjà dans la base de données'], Response::HTTP_BAD_REQUEST);
            }
        }
    }    
?>