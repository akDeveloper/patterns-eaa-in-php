<?php

declare(strict_types = 1);

namespace Behavioral;

use BasePatterns\LayerSupertype\DomainObject;

class IdentityMap
{
    private static $data = [];

    public static function contains(int $id, string $className): bool
    {
        if (!array_key_exists($className, self::$data)) {
            return false;
        }

        if (!array_key_exists($id, self::$data[$className])) {
            return false;
        }

        return true;
    }

    public static function get(int $id, string $className): ?DomainObject
    {
        if (self::contains($id, $className)) {
            return self::$data[$className][$id];
        }

        return null;
    }

    public function set(DomainObject $object): void
    {
        self::$data[get_class($object)][$object->getId()] = $object;
    }
}
