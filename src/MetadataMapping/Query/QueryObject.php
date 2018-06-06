<?php

declare(strict_types = 1);

namespace MetadataMapping\Query;

use ArrayIterator;

class QueryObject
{
    private $className;

    private $criteria;

    public function __construct(string $className)
    {
        $this->className = $className;
        $this->criteria = new ArrayIterator();
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
            $result .= $criterion->generateSql();
        }

        return $result;
    }
}
