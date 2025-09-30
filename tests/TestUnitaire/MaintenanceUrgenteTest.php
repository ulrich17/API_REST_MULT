<?php 
    namespace Tests\TestUnitaire;
    use PHPUnit\Framework\TestCase;
    use App\Entity\Vehicule;
    use App\Entity\Maintenance;
    use App\Service\MaintenanceUrgenteService;

    class MaintenanceUrgenteTest extends TestCase
    {
        public function testGetMaintenanceUrgente()
        {
            // Arrange : création d'un mock de maintenance
            $maintenanceMock1 = $this->createMock(Maintenance::class);
            $maintenanceMock1->method('getId')->willReturn(1);
            $maintenanceMock1->method('getTypeMaintenance')->willReturn('Vidange');
            $maintenanceMock1->method('getDescription')->willReturn('Changement d\'huile moteur');
            $maintenanceMock1->method('getDateplanifie')->willReturn(new \DateTime('2023-05-01'));
            $maintenanceMock1->method('getDateeffectuee')->willReturn(new \DateTime('2023-05-02'));
            $maintenanceMock1->method('getCout')->willReturn(100.08);
            $maintenanceMock1->method('getKilometrage')->willReturn(15000);
            $maintenanceMock1->method('getTypeAlerte')->willReturn('Critique');
            $maintenanceMock1->method('getObservations')->willReturn('Aucun problème');

            $maintenanceMock2 = $this->createMock(Maintenance::class);
            $maintenanceMock2->method('getId')->willReturn(2);
            $maintenanceMock2->method('getTypeMaintenance')->willReturn('Révision');
            $maintenanceMock2->method('getDescription')->willReturn('Révision annuelle');
            $maintenanceMock2->method('getDateplanifie')->willReturn(new \DateTime('2023-06-01'));
            $maintenanceMock2->method('getDateeffectuee')->willReturn(null);
            $maintenanceMock2->method('getCout')->willReturn(200.50);
            $maintenanceMock2->method('getKilometrage')->willReturn(20000);
            $maintenanceMock2->method('getTypeAlerte')->willReturn('Normale');
            $maintenanceMock2->method('getObservations')->willReturn('À faire bientôt');
            $maintenanceList = [$maintenanceMock1, $maintenanceMock2];
            // Act : appel du service pour obtenir les maintenances urgentes
            $maintenanceService = new MaintenanceUrgenteService();
            $result = $maintenanceService->getMaintenanceUrgente($maintenanceList);
            // Assert : vérification du résultat
            $this->assertCount(1, $result);
            $this->assertEquals(1, $result[0]['id']);   
            $this->assertEquals('Vidange', $result[0]['type_Maintenance']);
            $this->assertEquals('Changement d\'huile moteur', $result[0]['description']);
            $this->assertEquals('01/05/2023', $result[0]['date_Planifiee']);
            $this->assertEquals('02/05/2023', $result[0]['date_Effectuee']);
            $this->assertEquals('100.08 Euros', $result[0]['cout(en_euros)']);
            $this->assertEquals(15000, $result[0]['kilometrage']);
            $this->assertEquals('Critique', $result[0]['type_Alerte']);
            $this->assertEquals('Aucun problème', $result[0]['observations']);
        }
    }
?>