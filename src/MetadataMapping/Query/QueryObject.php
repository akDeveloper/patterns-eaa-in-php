<?php

declare(strict_types = 1);

namespace MetadataMapping\Query;

use ArrayIterator;

class QueryObject
{
    private $className;

    private $criteria;

    private $uow;

    public function __construct(string $className, UnitOfWork $uow)
    {
        $this->className = $className;
        $this->criteria = new ArrayIterator();
        $this->uow = $uow;
    }

    public function addCriteria(Criteria $criteria): void
    {
        $this->criteria->append($criteria);
    }

    private function generateWhereClause(): string
    {
        $result = "";
        foreach ($this->criteria as $criterion) {
            if (strlen($result) != 0) {
                $result .= " AND ";
            }
            $result .= $criterion->generateSql($this->uow->getMapper($this->className)->getDataMap());
        }

        return $result;
    }
}
