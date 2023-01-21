<?php

namespace Core\Container;

use Psr\Container\ContainerInterface;
use Core\Exceptions\NotFoundException;

class Container implements ContainerInterface
{
    private $services = [];

    public function register(string $key, $value)
    {
        if (is_callable($value) || is_object($value)) {
            $this->services[$key] = $value;

            return $this;
        }
        $this->services[$key] = $this->resolveDependency($value);

        return $this;
    }

    public function get(string $id)
    {
        try {
            if (isset($this->services[$id])) {
                return $this->services[$id];
            }
            $this->services[$id] = $this->resolveDependency($id);

            return $this->services[$id];
        } catch (\ReflectionException $ex) {
            throw new NotFoundException($ex->getMessage());
        } catch (\Exception $ex) {
            throw new NotFoundException($ex->getMessage());
        }
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    public function getServices()
    {
        return $this->services;
    }

    private function resolveDependency($item)
    {
        if (is_callable($item)) {
            return $item();
        }

        $reflectionItem = new \ReflectionClass($item);

        return $this->getInstance($reflectionItem);
    }

    private function getInstance(\ReflectionClass $item)
    {
        $constructor = $item->getConstructor();
        if (is_null($constructor) || 0 == $constructor->getNumberOfRequiredParameters()) {
            return $item->newInstance();
        }

        $params = [];

        foreach ($constructor->getParameters() as $param) {
            if ($type = $param->getType()) {
                $params[] = $this->get($type->getName());
            }
        }

        return $item->newInstanceArgs($params);
    }
}
