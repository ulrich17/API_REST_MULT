<?php 
    namespace Tests\TestUnitaire;
    use PHPUnit\Framework\TestCase;
    use App\Entity\Vehicule;
    use App\Entity\Location;
    use App\Entity\Client;
    use App\Service\LocationsEncoursService;

    class LocationsVehiculeEncoursTest extends TestCase
    {
        public function testGetLocationsEncours()
        {
            // Arrange : création d'un mock de véhicule
            $vehiculeMock1 = $this->createMock(Vehicule::class);
            $vehiculeMock1->method('getId')->willReturn(1);
            $vehiculeMock1->method('getImmatriculation')->willReturn('AB-123-CD');
            $vehiculeMock1->method('getMarque')->willReturn('Toyota');
            $vehiculeMock1->method('getModele')->willReturn('Corolla');

            $vehiculeMock2 = $this->createMock(Vehicule::class);
            $vehiculeMock2->method('getId')->willReturn(2);
            $vehiculeMock2->method('getImmatriculation')->willReturn('EF-456-GH');
            $vehiculeMock2->method('getMarque')->willReturn('Honda');
            $vehiculeMock2->method('getModele')->willReturn('Civic');

            $clientMock1 = $this->createMock(Client::class); 
            $clientMock1->method('getId')->willReturn(1);
            $clientMock1->method('getNom')->willReturn('BOUMBA');
            $clientMock1->method('getPrenom')->willReturn('Ulrich');

            $clientMock2 = $this->createMock(Client::class); 
            $clientMock2->method('getId')->willReturn(2);
            $clientMock2->method('getNom')->willReturn('DOE');
            $clientMock2->method('getPrenom')->willReturn('John');

            $locationMock1 = $this->createMock(Location::class);
            $locationMock1->method('getId')->willReturn(1);
            $locationMock1->method('getVehicule')->willReturn($vehiculeMock1);
            $locationMock1->method('getDatedebut')->willReturn(new \DateTime('-5 days'));
            $locationMock1->method('getDatefin')->willReturn(new \DateTime('+5 days'));
            $locationMock1->method('getClient')->willReturn($clientMock1);

            $locationMock2 = $this->createMock(Location::class);
            $locationMock2->method('getId')->willReturn(2);
            $locationMock2->method('getVehicule')->willReturn($vehiculeMock2);
            $locationMock2->method('getDatedebut')->willReturn(new \DateTime('-3 days'));
            $locationMock2->method('getDatefin')->willReturn(new \DateTime('+7 days'));
            $locationMock2->method('getClient')->willReturn($clientMock2);

            $locations = [$locationMock1, $locationMock2];
            $service = new LocationsEncoursService();

            // Act : appel de la méthode à tester
            $resultat = $service->getLocationsEncours($locations);

            // Assert : vérification des résultats
            $this->assertCount(2, $resultat);
            $this->assertEquals('AB-123-CD', $resultat[0]['Immatriculation du véhicule']);
            $this->assertEquals('EF-456-GH', $resultat[1]['Immatriculation du véhicule']);
        
        }
    }
?>