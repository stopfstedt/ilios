<?php

declare(strict_types=1);

namespace App\Repository;

use App\Traits\ManagerRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use App\Entity\AamcPcrs;
use App\Entity\DTO\AamcPcrsDTO;
use Doctrine\Persistence\ManagerRegistry;

class AamcPcrsRepository extends ServiceEntityRepository implements DTORepositoryInterface, RepositoryInterface
{
    use ManagerRepository;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AamcPcrs::class);
    }

    /**
     * @inheritdoc
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('DISTINCT x')->from('App\Entity\AamcPcrs', 'x');

        $this->attachCriteriaToQueryBuilder($qb, $criteria, $orderBy, $limit, $offset);

        return $qb->getQuery()->getResult();
    }

    /**
     * Find and hydrate as DTOs
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param null $limit
     * @param null $offset
     *
     * @return array
     */
    public function findDTOsBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        $qb = $this->_em->createQueryBuilder()->select('x')->distinct()->from('App\Entity\AamcPcrs', 'x');
        $this->attachCriteriaToQueryBuilder($qb, $criteria, $orderBy, $limit, $offset);
        $aamcPcrsDTOs = [];
        foreach ($qb->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY) as $arr) {
            $aamcPcrsDTOs[$arr['id']] = new AamcPcrsDTO(
                $arr['id'],
                $arr['description']
            );
        }
        $aamcPcrsIds = array_keys($aamcPcrsDTOs);
        $related = [
            'competencies'
        ];
        foreach ($related as $rel) {
            $qb = $this->_em->createQueryBuilder()
                ->select('r.id AS relId, x.id AS aamcPcrsId')->from('App\Entity\AamcPcrs', 'x')
                ->join("x.{$rel}", 'r')
                ->where($qb->expr()->in('x.id', ':aamcPcrsIds'))
                ->orderBy('relId')
                ->setParameter('aamcPcrsIds', $aamcPcrsIds);
            foreach ($qb->getQuery()->getResult() as $arr) {
                $aamcPcrsDTOs[$arr['aamcPcrsId']]->{$rel}[] = $arr['relId'];
            }
        }
        return array_values($aamcPcrsDTOs);
    }


    /**
     * @param QueryBuilder $qb
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     *
     * @return QueryBuilder
     */
    protected function attachCriteriaToQueryBuilder(QueryBuilder $qb, $criteria, $orderBy, $limit, $offset)
    {
        if (array_key_exists('competencies', $criteria)) {
            $ids = is_array($criteria['competencies']) ? $criteria['competencies'] : [$criteria['competencies']];
            $qb->join('x.competencies', 'st');
            $qb->andWhere($qb->expr()->in('st.id', ':competencies'));
            $qb->setParameter(':competencies', $ids);
        }

        //cleanup all the possible relationship filters
        unset($criteria['competencies']);

        if (count($criteria)) {
            foreach ($criteria as $key => $value) {
                $values = is_array($value) ? $value : [$value];
                $qb->andWhere($qb->expr()->in("x.{$key}", ":{$key}"));
                $qb->setParameter(":{$key}", $values);
            }
        }

        if (empty($orderBy)) {
            $orderBy = ['id' => 'ASC'];
        }

        if (is_array($orderBy)) {
            foreach ($orderBy as $sort => $order) {
                $qb->addOrderBy('x.' . $sort, $order);
            }
        }

        if ($offset) {
            $qb->setFirstResult($offset);
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb;
    }
}
