<?php   
    
    namespace App\Controller\API;
    use App\Entity\User;
    use App\Repository\UserRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Doctrine\ORM\EntityManagerInterface;

    class CreateUserController extends AbstractController
    {
        private $entityManager;
        private $userRepository;

        public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
        {
            $this->entityManager = $entityManager;
            $this->userRepository = $userRepository;
        }
        
        // Méthode permettant de créer un utilisateur
        #[Route('/api/v1/user', name:'createuser', methods:['POST'])]
        public function __invoke(Request $request): JsonResponse
        {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['username']) || !isset($data['password'])) {
                return new JsonResponse(['erreur' => 'Paramètres manquants'], 400);
            }

            $user = new User();
            $user->setUsername($data['username']);
            $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Utilisateur créé avec succès.'], 201);
        }
    }


?>