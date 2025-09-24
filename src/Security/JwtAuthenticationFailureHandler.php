<?php 
    namespace App\Security;
    use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
    use Symfony\Component\HttpFoundation\JsonResponse;

    class JwtAuthenticationFailureHandler {

        // Méthode qui renvoie une erreur si l'utilisateur ne s'est pas authentifié.
        
        public function jetonNonTrouve(JWTNotFoundEvent $event)
        {
            $response = new JsonResponse([
                'error' => 'Jeton JWT introuvable. Veuillez vous authentifier',
            ], JsonResponse::HTTP_UNAUTHORIZED);

            $event->setResponse($response);
        }

    } 

?>