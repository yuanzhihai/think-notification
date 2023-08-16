<?php

namespace yzh52521\notification\model;

use ReflectionClass;
use think\Model;

trait SerializesModel
{
    public function __serialize(): array
    {
        $values = [];

        $properties = (new ReflectionClass($this))->getProperties();

        $class = get_class($this);

        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $property->setAccessible(true);

            if (!$property->isInitialized($this)) {
                continue;
            }

            $name = $property->getName();

            if ($property->isPrivate()) {
                $name = "\0{$class}\0{$name}";
            } elseif ($property->isProtected()) {
                $name = "\0*\0{$name}";
            }

            $value = $property->getValue($this);

            if ($value instanceof Model) {
                $value = new ModelIdentifier(get_class($value), $value->{$value->getPk()});
            }

            $values[$name] = $value;
        }

        return $values;
    }

    public function __unserialize(array $values): void
    {
        $properties = (new ReflectionClass($this))->getProperties();

        $class = get_class($this);

        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $name = $property->getName();

            if ($property->isPrivate()) {
                $name = "\0{$class}\0{$name}";
            } elseif ($property->isProtected()) {
                $name = "\0*\0{$name}";
            }

            if (!array_key_exists($name, $values)) {
                continue;
            }

            $property->setAccessible(true);

            $value = $values[$name];

            if ($value instanceof ModelIdentifier) {
                /** @var Model|\think\model\concern\SoftDelete $model */
                $model = $value->class;
                if (method_exists($model, 'withTrashed')) {
                    $value = $model::withTrashed()->findOrEmpty($value->id);
                } else {
                    $value = $model::findOrEmpty($value->id);
                }
            }

            $property->setValue($this, $value);
        }
    }
}