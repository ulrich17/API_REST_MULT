<?php
    namespace App\Controller\API;
    use App\Entity\CategorieVehicule;
    use App\Repository\CategorieVehiculeRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    class CategorieVehiculeController extends AbstractController
    {
        #[Route('/api/v1/categorievehicules', name: 'createCategorieVehicule', methods: ['POST'])]
        public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): Response
        {
            $data = json_decode($request->getContent(), true);
            if (!$data) {
                return $this->json(['message' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
            }
            $categorieVehicule = new CategorieVehicule();
            $categorieVehicule->setLibelleCategorie($data['libelleCategorie']);
            // Validation de l'entité

            $errors = $validator->validate($categorieVehicule);
            if (count($errors) > 0) {
                return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }
            if(!$errors) {
                return $this->json(['message' => 'Catégorie de véhicule créée avec succès'], Response::HTTP_CREATED);
            }
            $em->persist($categorieVehicule);
            $em->flush();

            return $this->json($categorieVehicule, Response::HTTP_CREATED);
        }
        #[Route('/api/v1/categorievehicules', name: 'listCategorieVehicules', methods: ['GET'])]
        public function list(EntityManagerInterface $em): JsonResponse
        {
            $categorieVehicules = $em->getRepository(CategorieVehicule::class)->findAll();
            return $this->json(
                $categorieVehicules, 
                Response::HTTP_OK, 
                [], 
                ['groups' => 'categorieVehicule:list']
            );
        }
        #[Route('/api/v1/categorievehicules/{id}', name: 'detailCategorieVehicule', methods: ['GET'])]
        public function detail(int $id, Request $request, EntityManagerInterface $em): Response
        {
            $data = json_decode($request->getContent(), true);
            $categorieVehicule = $em->getRepository(CategorieVehicule::class)->find($id);
            if (!$categorieVehicule) {
                return $this->json(['message' => 'Catégorie de véhicule non trouvée'], Response::HTTP_NOT_FOUND);
            }
            return $this->json(
                $categorieVehicule,
                Response::HTTP_OK,
                [],
                ['groups' => 'categorieVehicule:detail']
            );
        }
        #[Route('/api/v1/categorievehicules/{id}', name: 'updateCategorieVehicule', methods: ['PUT'])]
        public function update(int $id, Request $request, EntityManagerInterface $em, CategorieVehiculeRepository $categorieVehiculeRepository): JsonResponse
        {
            $categorieVehicule = $categorieVehiculeRepository->find($id);
            if (!$categorieVehicule) {
                return new JsonResponse(['message' => 'Catégorie de véhicule non trouvée'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);

            $categorieVehicule->setLibelle($data['libelle'] ?? $categorieVehicule->getLibelle());
            $categorieVehicule->setDescription($data['description'] ?? $categorieVehicule->getDescription());
            $em->flush();
            return $this->json($categorieVehicule);

        }
        #[Route('/api/v1/categorievehicules/{id}', name: 'deleteCategorieVehicule', methods: ['DELETE'])]
        public function delete(int $id, EntityManagerInterface $em, CategorieVehiculeRepository $categorieVehiculeRepository): JsonResponse
        {
            $categorieVehicule = $categorieVehiculeRepository->find($id);
            if (!$categorieVehicule) {
                return new JsonResponse(['message' => 'Catégorie de véhicule non trouvée'], Response::HTTP_NOT_FOUND);
            }
            $em->remove($categorieVehicule);
            $em->flush();
            return new JsonResponse(['message' => 'Catégorie de véhicule supprimée avec succès'], Response::HTTP_OK);
        }
    }