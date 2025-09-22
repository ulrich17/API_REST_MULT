<?php

namespace App\Repository;

use App\Entity\Vehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\DBAL\Connection;
/**
 * @extends ServiceEntityRepository<Vehicule>
 */
class VehiculeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Vehicule::class);
    }

    public function findDisponibles(\DateTime $dateDebut, \DateTime $dateFin, string $categorie)
    {
        return $this->createQueryBuilder('v')
            ->join('v.categorieVehicule', 'c')
            ->where('c.nom = :categorie')
            ->andWhere('v.dateEntree <= :dateDebut')
            ->andWhere('(v.dateSortie IS NULL OR v.dateSortie >= :dateFin)')
            ->setParameter('categorie', $categorie)
            ->setParameter('dateDebut', $dateDebut)
            ->setParameter('dateFin', $dateFin)
            ->getQuery()
            ->getResult();
    }

    // gestion de pagoination avec knp paginator
    public function paginateVehicules(int $page)
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder('v')->getQuery(),
            $page,
            10, // Limite par page
            [
                'distinct' => true,
                'sortFieldWhitelist' => ['v.id', 'v.immatriculation', 'v.marque']
            ]
        );
    }

    //    /**
    //     * @return Vehicule[] Returns an array of Vehicule objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Vehicule
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Recherche flexible : normalise l'immatriculation (supprime espaces/tirets, majuscule)
     * et fait des LIKE sur immatriculation / marque / modele.
     *
     * @return Vehicule[]
     */
    public function searchVehicules(?string $immatriculation, ?string $marque, ?string $modele): array
{
    $conn = $this->getEntityManager()->getConnection();

    $sql = "SELECT * FROM vehicule v WHERE 1=1"; // base query

    $params = [];

    // Filtre immatriculation
    if ($immatriculation) {
        $sql .= " AND REPLACE(REPLACE(UPPER(v.immatriculation), ' ', ''), '-', '') LIKE :immat";
        $params['immat'] = '%' . mb_strtoupper(str_replace([' ', '-'], '', $immatriculation)) . '%';
    }

    // Filtre marque
    if ($marque) {
        $sql .= " AND LOWER(v.marque) LIKE :marque";
        $params['marque'] = '%' . mb_strtolower($marque) . '%';
    }

    // Filtre modele
    if ($modele) {
        $sql .= " AND LOWER(v.modele) LIKE :modele";
        $params['modele'] = '%' . mb_strtolower($modele) . '%';
    }

    $sql .= " ORDER BY v.id ASC";

    $stmt = $conn->prepare($sql);
    $result = $stmt->executeQuery($params);

    return $result->fetchAllAssociative();
}


}
