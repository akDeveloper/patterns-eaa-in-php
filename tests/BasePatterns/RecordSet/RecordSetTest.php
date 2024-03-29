<?php

declare(strict_types = 1);

namespace BasePatterns\RecordSet;

use Traversable;
use ArrayIterator;
use PHPUnit\Framework\TestCase;

class RecordSetTest extends TestCase
{
    public function testShouldCreateDataRows()
    {
        $recordSet = new RecordSet();

        $recordSet->load($this->getTraversable(), 'persons');
        $rows = $recordSet->getTable('persons')->getRows();

        $this->assertEquals(3, count($rows));
        $this->assertInstanceOf(Row::class, $rows[0]);
    }

    public function getTraversable(): Traversable
    {
        $data = [
            ['firstName' => 'John', 'lastName' => 'Doe', 'numberOfDependents' => 2],
            ['firstName' => 'Jane', 'lastName' => 'Doe', 'numberOfDependents' => 1],
            ['firstName' => 'John', 'lastName' => 'Smith', 'numberOfDependents' => 3],
        ];

        return new ArrayIterator($data);
    }
}
