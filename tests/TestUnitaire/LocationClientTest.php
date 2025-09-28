<?php 
    namespace Tests\TestUnitaire;
    use PHPUnit\Framework\TestCase;
    use App\Entity\Vehicule;
    use App\Entity\client;
    use App\Entity\Location;
    use App\Entity\CategorieVehicule;
    use App\Service\LocationClientService;  

    class LocationClientTest extends TestCase
    {
        public function testGetLocationClient()
        {
            // Arrange : création d'un mock de véhicule
            $vehiculeMock1 = $this->createMock(Vehicule::class);
            $vehiculeMock1->method('getId')->willReturn(1);
            $vehiculeMock1->method('getImmatriculation')->willReturn('AB-123-CD');
            $vehiculeMock1->method('getMarque')->willReturn('Toyota');
            $vehiculeMock1->method('getModele')->willReturn('Corolla');
            $vehiculeMock1->method('getCouleur')->willReturn('Rouge');
            $vehiculeMock1->method('getAnnee')->willReturn(2020);
            // Mock de catégorie de véhicule
            $categorieVehiculeMock = $this->createMock(CategorieVehicule::class);
            $categorieVehiculeMock->method('getLibelleCategorie')->willReturn('Berline');
            $vehiculeMock1->method('getCategorieVehicule')->willReturn($categorieVehiculeMock);
            // Mock de client
            $clientMock = $this->createMock(Client::class); 
            $clientMock->method('getId')->willReturn(1);
            $clientMock->method('getNom')->willReturn('NATHAN');
            $clientMock->method('getPrenom')->willReturn('Benjamin');
            // Mock de location
            $locationMock1 = $this->createMock(Location::class);
            $locationMock1->method('getId')->willReturn(1);
            $locationMock1->method('getVehicule')->willReturn($vehiculeMock1);
            $locationMock1->method('getDatedebut')->willReturn(new \DateTime('2023-07-01'));
            $locationMock1->method('getDatefin')->willReturn(new \DateTime('2023-07-10'));
            $locationMock1->method('getClient')->willReturn($clientMock);

            $locations = [$locationMock1];
            // Act : appel de la méthode à tester
            $service = new LocationClientService();
            $resultat = $service->getLocationClient($locations);
            // Assert : vérification des résultats
            $this->assertCount(1, $resultat);
            $this->assertEquals('Berline', $resultat[0]['Catégorie']);
            $this->assertEquals('AB-123-CD', $resultat[0]['immatriculation']);
            $this->assertEquals('Toyota', $resultat[0]['Marque']);          
            $this->assertEquals('Corolla', $resultat[0]['Modèle']);
            $this->assertEquals('Rouge', $resultat[0]['Couleur']);
            $this->assertEquals(2020, $resultat[0]['Année']);
            $this->assertEquals('01-07-2023', $resultat[0]['Date debut']);
            $this->assertEquals('10-07-2023', $resultat[0]['Date fin']);
        }
    }
?>