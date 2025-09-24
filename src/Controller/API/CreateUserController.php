<?php   
    
    namespace App\Controller\API;
    use App\Entity\User;
    use App\Repository\UserRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\ORM\EntityManagerInterface;

    class CreateUserController extends AbstractController
    {
        // Méthode permettant de créer un utilisateur
        #[Route('/api/v1/users', name:'createUser', methods:['POST'])]
        public function __invoke(
            UserRepository $userRepository,Request $request, EntityManagerInterface $em
            ): JsonResponse
        {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['username']) || !isset($data['password'])) {
                return new JsonResponse(['erreur' => 'Paramètres manquants'], 400);
            }

            $roles = $data['roles']?? ['ROLE_USER'];
            if(!is_array($roles)){
                $roles = [$roles];
            }  
            $user = new User();
            $user->setUsername($data['username']);
            $user->setRoles($roles);
            $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Utilisateur créé avec succès.'], 201);
        }
    }


?>