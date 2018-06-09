<?php

declare(strict_types = 1);

namespace MetadataMapping\Query;

use ArrayIterator;
use Behavioral\UnitOfWork;
use PHPUnit\Framework\TestCase;
use DataSource\ConnectionHelper;
use DataSource\DataMapper\Person;
use DataSource\DataMapper\PersonMapper;

class QueryObjectTest extends TestCase
{
    use ConnectionHelper;

    public function testShouldExecuteSQL(): void
    {
        $connection = $this->getMockConnection($this->getLoadAllRecordSet());
        $uow = new UnitOfWork($connection);
        $uow->registerDataMapper(Person::class, PersonMapper::class);
        $qo = new QueryObject(Person::class, $uow);
        $qo->addCriteria(
            Criteria::equals('firstName', 'John')
        );

        $rs = $qo->execute($uow);

        $this->assertInstanceOf(ArrayIterator::class, $rs);
        $this->assertInstanceOf(Person::class, $rs->current());
    }

    protected function getData(): array
    {
        return [
            ['id' => 1, 'firstname' => 'John', 'lastname' => 'Doe', 'number_of_dependents' => 2],
            ['id' => 3, 'firstname' => 'John', 'lastname' => 'Smith', 'number_of_dependents' => 3],
        ];
    }
}
