<?php   
    
    namespace App\Controller\API;
    use App\Entity\User;
    use App\Repository\UserRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Doctrine\ORM\EntityManagerInterface;
    
    class CreateUserController extends AbstractController
    {
        // Méthode qui permet de créer un utilisateur
        #[Route('/api/v1/users', name:'createUser', methods:['POST'])]
        public function __invoke(
            Request $request, EntityManagerInterface $em, 
            UserPasswordHasherInterface $userPasswordHasher
            ): JsonResponse
        {
            $data = json_decode($request->getContent(), true);
            // On vérifie si l'utilisateur a renseigné son nom d'utilisateur et son mot de passe
            if (!isset($data['username']) || !isset($data['password'])) {
                return new JsonResponse(['erreur' => 'Paramètres manquants'], 400);
            }
            $roles = $data['roles']?? ['ROLE_USER'];
            if(!is_array($roles)){
                $roles = [$roles];
            }  
            // On crée un utilisateur
            $user = new User();
            $user->setUsername($data['username']);
            $user->setRoles($roles);
            $user->setPassword($userPasswordHasher->hashPassword($user,$data['password']));
            // On prépare les données à enregistrer dans la base de données
            $em->persist($user);
            // on enregistre dans la base de données.
            $em->flush();
            // On renvoie un message à l'utilisateur
            return new JsonResponse(['message' => 'Utilisateur créé avec succès.'], 201);
        }
    }


?>