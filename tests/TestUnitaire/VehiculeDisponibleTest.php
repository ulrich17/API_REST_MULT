<?php
namespace Tests\TestUnitaire;

use PHPUnit\Framework\TestCase;
use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use App\Service\VehiculeService;

class VehiculeDisponibleTest extends TestCase
{
    public function testVehiculesDisponibles()
    {
        // Token généré  depuis Postman)
        $jwtToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3NTg4ODkyNDQsImV4cCI6MTc1ODg5Mjg0NCwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiVWxyaWNoIn0.kc-JW3ywXDZ5jX75VoEnDQYk69sA94n7hcBXcV4uoZ_unXWv8uEg0TFGntQoQYfr0O_totmI4ITaU0_f-InwnjyTHAuLTl2m4qcikW6zyI22EnHiy89-SwAR2CDUPb2R16SEYLToRtAFUwvQURUyIzWBN1C7FcSADJBNUZGO7DZtXtc2kaKA9yNq-SGbP0kCUglFheHobP3VCzaY5cw29_Pr_M2L1j6virLE1hZ4KPxUUu-4f6uAUlW2X-hP2UIJc26NmIySZF_WITv4un3q9mFAZIcDIfJ7szON_LRdpxZYzI4MHSDKSZMhUyuwpu9bPhMo51zSfF5twpumKglHEKyqYOsZDeBeI1UVQ_3MlP8EGNkWsN4TQ4_VK59dsThuoQqqEBdJ7y2zGDnIt9iAzvbtJnvKfQJpQMOUusckUbltlpvo5mJq66O1VB1USK-6SI-V3MccXyN7BN6av4M1pnR4fp9SVaAlA7EgzeuIFezem4B_abZOSSsT-aagelBI2zhLEK7cb-9BDLaxvtCysFUA62FCgeA9pj3aYjhEa8NQy-eHBwUDdg3q9YMmSZMflM7I72CzepGz7ZCPNy71DJ8AEoyajwQMi3okrKJHf0VscJ9C1fnb4XhvX-on7rr4vTphTe-S8tkg2_otUUHV-YOUV5-WKKXS6crwXxCHlgU'; 
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . $jwtToken;

        // Arrange : création d'un mock de véhicule
        $vehiculeMock = $this->createMock(Vehicule::class);
        $vehiculeMock->method('getId')->willReturn(1);
        $vehiculeMock->method('getStatut')->willReturn('Disponible');
        $vehiculeMock->method('getMarque')->willReturn('Toyota');
        $vehiculeMock->method('getModele')->willReturn('Corolla');
        $vehiculeMock->method('getAnnee')->willReturn(2020);
        $vehiculeMock->method('getCouleur')->willReturn('Rouge');
        $vehiculeMock->method('getImmatriculation')->willReturn('ABC-123');
        $vehiculeMock->method('getDateEntree')->willReturn(new \DateTime('2022-01-01'));
        $vehiculeMock->method('getDateSortie')->willReturn(new \DateTime('2023-01-10'));


        // Act : appel du service pour filtrer les véhicules disponibles
        $service = new VehiculeService();
        $result = $service->getVehiculesDisponibles([$vehiculeMock]);

        // Assert : vérification des résultats

        $this->assertIsArray($result);
        $this->assertArrayHasKey('items', $result);
        $this->assertCount(1, $result['items']);
        $this->assertEquals('Disponible', $result['items'][0]['Statut']);
        $this->assertEquals('Corolla', $result['items'][0]['modele']);
        $this->assertEquals('Toyota', $result['items'][0]['marque']);
        $this->assertEquals(2020, $result['items'][0]['annee']);
        $this->assertEquals('Rouge', $result['items'][0]['couleur']);
        $this->assertEquals('ABC-123', $result['items'][0]['immatriculation']);
        $this->assertEquals('2022-01-01', $result['items'][0]['dateEntree']);
        $this->assertEquals('2023-01-10', $result['items'][0]['dateSortie']);
    }
}
