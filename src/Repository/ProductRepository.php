<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }


public function MyfindId($id){
    //createQueryBuilder('p') => SELECT p FROM APP\ENTITY\PRODUCT
    $queryBuilder = $this->createQueryBuilder('p')
        ->where("p.id = :id")
        ->setParameter('id',$id);

// On recupere la requete 
$query = $queryBuilder->getQuery();

// On recupere les resultats , ces resultats sont sous forme d'un tableau d'objet
$result = $query->getOneOrNullResult();

return $result;

}

public function FindPrice($minPrice,$maxPrice){
    $queryBuilder = $this->createQueryBuilder('p')
        ->where("p.price >= :minPrice")
        ->setParameter('minPrice',$minPrice*100)
        ->andwhere("p.price <= :maxPrice")
        ->setParameter('maxPrice',$maxPrice*100)
        ->orderBy('p.price','desc');


// On recupere la requete 
$query = $queryBuilder->getQuery();

// On recupere les resultats , ces resultats sont sous forme d'un tableau d'objet
$result = $query->getResult();

return $result;
}

public function FindSearch($search){
    
    
    $queryBuilder = $this->createQueryBuilder('p')
    ->join('p.category','cat')
    ->addSelect('cat');

if (count($search->getCategories())>0) {
    $queryBuilder
    ->where('cat.id IN (:categories)')

    ->setParameter('categories',$search->getCategories());
}


if(!empty($search->getString()))
{

 $mots = explode(' ',$search->getString());

 foreach ($mots as $cle => $mot) {
     

 $queryBuilder
     ->andWhere('p.name LIKE :name'.$cle.' OR p.description LIKE :name'.$cle)
     ->setParameter('name'.$cle, '%'.$mot.'%');

 }
$query = $queryBuilder->getQuery();
$result = $query->getResult();
return $result;



}
    //    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
}