<?php
namespace Tests\TestUnitaire;
use PHPUnit\Framework\TestCase;
use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use App\Service\VehiculeService;
use App\Service\HistoriqueCompletService;
use App\Entity\Maintenance;
use App\Entity\Location;
use App\Entity\Client;

class HistoriqueCompletVehiculeTest extends TestCase
{

    public function testGetHistoriqueComplet()
    {
       
        // Token généré  depuis Postman)
        $jwtToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3NTg4ODkyNDQsImV4cCI6MTc1ODg5Mjg0NCwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiVWxyaWNoIn0.kc-JW3ywXDZ5jX75VoEnDQYk69sA94n7hcBXcV4uoZ_unXWv8uEg0TFGntQoQYfr0O_totmI4ITaU0_f-InwnjyTHAuLTl2m4qcikW6zyI22EnHiy89-SwAR2CDUPb2R16SEYLToRtAFUwvQURUyIzWBN1C7FcSADJBNUZGO7DZtXtc2kaKA9yNq-SGbP0kCUglFheHobP3VCzaY5cw29_Pr_M2L1j6virLE1hZ4KPxUUu-4f6uAUlW2X-hP2UIJc26NmIySZF_WITv4un3q9mFAZIcDIfJ7szON_LRdpxZYzI4MHSDKSZMhUyuwpu9bPhMo51zSfF5twpumKglHEKyqYOsZDeBeI1UVQ_3MlP8EGNkWsN4TQ4_VK59dsThuoQqqEBdJ7y2zGDnIt9iAzvbtJnvKfQJpQMOUusckUbltlpvo5mJq66O1VB1USK-6SI-V3MccXyN7BN6av4M1pnR4fp9SVaAlA7EgzeuIFezem4B_abZOSSsT-aagelBI2zhLEK7cb-9BDLaxvtCysFUA62FCgeA9pj3aYjhEa8NQy-eHBwUDdg3q9YMmSZMflM7I72CzepGz7ZCPNy71DJ8AEoyajwQMi3okrKJHf0VscJ9C1fnb4XhvX-on7rr4vTphTe-S8tkg2_otUUHV-YOUV5-WKKXS6crwXxCHlgU'; 
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $jwtToken;
        
        // Arrange : création d'un mock de véhicule
        $vehiculeMock = $this->createMock(Vehicule::class);
        $vehiculeMock->method('getId')->willReturn(1);
        $vehiculeMock->method('getImmatriculation')->willReturn('AB-123-CD');
        $vehiculeMock->method('getMarque')->willReturn('Toyota');
        $vehiculeMock->method('getModele')->willReturn('Corolla');
        $vehiculeMock->method('getAnnee')->willReturn(2020);
        $vehiculeMock->method('getCouleur')->willReturn('Rouge');
        $vehiculeMock->method('getEtat')->willReturn('Disponible');
        $vehiculeMock->method('getDateEntree')->willReturn(new \DateTime('2022-01-01'));
        $vehiculeMock->method('getDateSortie')->willReturn(null);
        $vehiculeMock->method('getObservations')->willReturn('Bon état');

        $maintenanceMock = $this->createMock(Maintenance::class);
        $maintenanceMock->method('getId')->willReturn(1);
        $maintenanceMock->method('getTypeMaintenance')->willReturn('Vidange');
        $maintenanceMock->method('getDescription')->willReturn('Changement d\'huile moteur');
        $maintenanceMock->method('getDateplanifie')->willReturn(new \DateTime('2023-05-01'));
        $maintenanceMock->method('getDateeffectuee')->willReturn(new \DateTime('2023-05-02'));
        $maintenanceMock->method('getCout')->willReturn(100.08);
        $maintenanceMock->method('getKilometrage')->willReturn(15000);
        $maintenanceMock->method('getTypeAlerte')->willReturn('Critique');
        $maintenanceMock->method('getObservations')->willReturn('Aucun problème');

        $clientMock = $this->createMock(Client::class); 
        $clientMock->method('getId')->willReturn(1);
        $clientMock->method('getNom')->willReturn('BOUMBA');
        $clientMock->method('getPrenom')->willReturn('Ulrich');

        $locationMock = $this->createMock(Location::class);
        $locationMock->method('getId')->willReturn(1);
        $locationMock->method('getVehicule')->willReturn($vehiculeMock);
        $locationMock->method('getDatedebut')->willReturn(new \DateTime('2023-06-01'));
        $locationMock->method('getDatefin')->willReturn(new \DateTime('2023-06-10'));
        $locationMock->method('getClient')->willReturn($clientMock);
        
        // Act : appel du service pour obtenir l'historique complet
        $vehiculeService = new HistoriqueCompletService();
        $result = $vehiculeService->getHistoriqueComplet($vehiculeMock, [$locationMock], [$maintenanceMock]);

        // Assert : vérification des résultats
        $this->assertIsArray($result);
        $this->assertArrayHasKey('vehicule', $result);
        $this->assertArrayHasKey('historique_Maintenace', $result);
        $this->assertArrayHasKey('historique_Location', $result);
        $this->assertEquals('AB-123-CD', $result['vehicule']['immatriculation']);   
        $this->assertEquals('Toyota', $result['vehicule']['marque']);
        $this->assertEquals('Corolla', $result['vehicule']['modele']);
        $this->assertCount(1, $result['historique_Maintenace']);
        $this->assertCount(1, $result['historique_Location']);
        $this->assertEquals('Ulrich BOUMBA', $result['historique_Location'][0]['Nom_Prenom_client']);
        $this->assertEquals('Bon état', $result['vehicule']['observations']);   
       
    }
}