<?php 
    namespace Tests\TestUnitaire;
    use PHPUnit\Framework\TestCase;
    use App\Entity\Location;
    use App\Entity\Vehicule;
    use App\Entity\Client;
    use App\Service\LocationsEncoursService;

    class LocationsVehiculeEncoursTest extends TestCase
    {
        public function testGetLocationsEncours()
        {
            // Arrange : création d'un mock de véhicule
            $vehiculeMock1 = $this->createMock(Vehicule::class);
            $vehiculeMock1->method('getId')->willReturn(1);
            $vehiculeMock1->method('getImmatriculation')->willReturn('ABC-123');
            $vehiculeMock1->method('getMarque')->willReturn('Toyota');
            $vehiculeMock1->method('getModele')->willReturn('Corolla');

            $vehiculeMock2 = $this->createMock(Vehicule::class);
            $vehiculeMock2->method('getId')->willReturn(2);
            $vehiculeMock2->method('getImmatriculation')->willReturn('XYZ-789');
            $vehiculeMock2->method('getMarque')->willReturn('Honda');
            $vehiculeMock2->method('getModele')->willReturn('Civic');

            // Arrange : création d'un mock de client
            $clientMock1 = $this->createMock(Client::class);
            $clientMock1->method('getId')->willReturn(1);
            $clientMock1->method('getNom')->willReturn('Doe');
            $clientMock1->method('getPrenom')->willReturn('John');

            // Arrange : création d'un mock de location
            $locationMock1 = $this->createMock(Location::class);
            $locationMock1->method('getId')->willReturn(1);
            $locationMock1->method('getDateDebut')->willReturn(new \DateTime('-2 days'));
            $locationMock1->method('getDateFin')->willReturn(new \DateTime('+3 days'));
            $locationMock1->method('getVehicule')->willReturn($vehiculeMock1);
            $locationMock1->method('getClient')->willReturn($clientMock1);
            // Location en cours
           /* $locationMock2 = $this->createMock(Location::class);    
            $locationMock2->method('getId')->willReturn(2);
            $locationMock2->method('getDateDebut')->willReturn(new \DateTime('-5 days'));   
            $locationMock2->method('getDateFin')->willReturn(new \DateTime('-1 days')); // Location terminée
            $locationMock2->method('getVehicule')->willReturn($vehiculeMock2);
            $locationMock2->method('getClient')->willReturn($clientMock2);  */

            // Liste des locations à tester
            $locationList = [$locationMock1/* $locationMock2*/];
            // Act : appel du service pour obtenir les locations en cours
            $locationsService = new LocationsEncoursService();
            $result = $locationsService->getLocationsEncours($locationList);
            // Assert : vérification du résultat
            $this->assertCount(1, $result);
            $this->assertEquals(1, $result[0]['id']);
            $this->assertEquals(1, $result[0]['id vehicule']);
            $this->assertEquals(1, $result[0]['id client']);
            $this->assertEquals('ABC-123', $result[0]['Immatriculation du véhicule']);
            $this->assertEquals('Toyota', $result[0]['Marque']);    
            $this->assertEquals('Corolla', $result[0]['Modèle']);
            $this->assertEquals('Doe John', $result[0]['Nom & Prenom']);
            $this->assertEquals((new \DateTime('-2 days'))->format('d-m-Y'), $result[0]['Data debut']);
            $this->assertEquals((new \DateTime('+3 days'))->format('d-m-Y'), $result[0]['Date fin']);
            $this->assertEquals(5, $result[0]['Nombre de jours']);      
            $this->assertEquals(2, $result[0]['Nombre de jours écoulés']);

        }
    
    }
?>