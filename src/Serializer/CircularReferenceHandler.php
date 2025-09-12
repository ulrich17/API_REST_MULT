<?php 
    namespace App\Serializer;
    
    class CircularReferenceHandler{
        public static function handle($object)
        {
            // Retourne un identifiant unique(id) au lieu d'un objet
            return $object->getId();
        }
    }
    
?>