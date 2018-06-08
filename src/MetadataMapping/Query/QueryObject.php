<?php

declare(strict_types = 1);

namespace MetadataMapping\Query;

use ArrayIterator;
use Behavioral\UnitOfWork;
use MetadataMapping\Metadata\DataMap;

class QueryObject
{
    private $className;

    private $criteria;

    private $bindValues = [];

    public function __construct(string $className)
    {
        $this->className = $className;
        $this->criteria = new ArrayIterator();
    }

    public function addCriteria(Criteria $criteria): void
    {
        $this->criteria->append($criteria);
    }

    public function execute(UnitOfWork $uow): ArrayIterator
    {
        $dataMapper = $uow->getDataMapper($this->className);

        return $dataMapper->findObjectsWhere(
            $this->generateWhereClause($dataMapper->getDataMap()),
            $this->bindValues
        );
    }

    private function generateWhereClause(DataMap $dataMap): string
    {
        $result = "";
        foreach ($this->criteria as $criterion) {
            if (strlen($result) != 0) {
                $result .= " AND ";
            }
            $result .= $criterion->generateSql($dataMap);
            $this->bindValues[] = $criterion->getValue();
        }

        return $result;
    }
}
