<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\DataType;

use ArrayAccess;
use InvalidArgumentException;
use JsonSerializable;

class Base implements JsonSerializable, ArrayAccess
{

    //<editor-fold desc="ArrayAccess" defaultState="collapsed">
    /**
     * {@inheritdoc}
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return property_exists($this, $offset) || array_key_exists($offset, $this->additionalProperties);
    }

    /**
     * {@inheritdoc}
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        if (property_exists($this, $offset)) {
            return $this->$offset;
        }

        return $this->additionalProperties[$offset];
    }

    /**
     * {@inheritdoc}
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        if (property_exists($this, $offset)) {
            $this->$offset = $value;

            return;
        }

        $this->additionalProperties[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->additionalProperties[$offset]);
    }
    //</editor-fold>

    //<editor-fold desc="JsonSerializable" defaultState="collapsed">
    public function jsonSerialize(): array
    {
        $data = [];
        foreach (static::getPropertyInfo() as $dstName => $info) {
            if (!empty($info['excludeFromSerialize'])) {
                continue;
            }

            $srcName = $info['name'] ?? $dstName;


            $info += ['type' => null];

            $value = $this->{$srcName};
            if ($value === null) {
                continue;
            }

            switch ($info['type']) {
                case 'dataType':
                    $data[$dstName] = $value->jsonSerialize();
                    break;

                case 'string || dataType':
                    $data[$dstName] = is_string($value) ? $value : $value->jsonSerialize();

                    break;

                case 'dataType[]':
                    $data[$dstName] = [];
                    foreach ($value as $k => $v) {
                        $data[$dstName][$k] = $v->jsonSerialize();
                    }
                    break;

                default:
                    $data[$dstName] = $value;
                    break;
            }
        }

        $data += $this->additionalProperties;

        return $data;
    }
    //</editor-fold>

    //<editor-fold desc="Magic methods">
    public function __get($name)
    {
        if (!array_key_exists($name, $this->additionalProperties)) {
            throw new InvalidArgumentException(sprintf(
                "property %s::%s doesn't exists",
                get_called_class(),
                $name
            ));
        }

        return $this->additionalProperties[$name];
    }

    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->additionalProperties)) {
            $this->additionalProperties[$name] = $value;
        }
    }

    public function __isset($name)
    {
        return isset($this->additionalProperties[$name]);
    }

    public static function __set_state($values)
    {
        $instance = new static();
        $properties = static::getPropertyInfo();
        foreach ($values as $srcName => $value) {
            if (!array_key_exists($srcName, $properties)) {
                $instance->additionalProperties[$srcName] = $value;

                continue;
            }


            $property = $properties[$srcName] ?? [];
            $property += [
                'type' => null,
            ];

            $dstName = $property['name'] ?? $srcName;

            switch ($property['type']) {
                case 'boolean':
                case 'integer':
                case 'double':
                    if ($value === null || $value === '') {
                        break;
                    }
                    settype($value, strtolower($property['type']));
                    $instance->{$dstName} = $value;
                    break;

                case 'string':
                case 'array':
                    if ($value === null) {
                        break;
                    }

                    settype($value, strtolower($property['type']));
                    $instance->{$dstName} = $value;
                    break;

                case 'dataType':
                    $setState = [$property['class'], '__set_state'];
                    $instance->{$dstName} = $setState($value);
                    break;

                case 'string || dataType':
                    if ($value === null) {
                        break;
                    }

                    if (is_string($value)) {
                        $instance->{$dstName} = $value;
                        break;
                    }

                    if (is_array($value)) {
                        $setState = [$property['class'], '__set_state'];
                        $instance->{$dstName} = $setState($value);
                    }

                    break;

                case 'dataType[]':
                    $setState = [$property['class'], '__set_state'];
                    $primaryKey = array_key_exists('primaryKey', $property) ?
                        $property['primaryKey']
                        : 'id';

                    $instance->{$dstName} = [];
                    foreach ($value as $item) {
                        $item = $setState($item);
                        if ($primaryKey) {
                            $instance->{$dstName}[$item->{$primaryKey}] = $item;
                        } else {
                            $instance->{$dstName}[] = $item;
                        }
                    }
                    break;

                default:
                    // @todo Something wrong.
                    $instance->{$dstName} = $value;
                    break;
            }
        }

        return $instance;
    }
    //</editor-fold>

    /**
     * Returns with an array of property definitions.
     *
     * A property definition keys:
     * - type: The value is a common PHP data type identifier or "dataType"
     * - class: Required if the "type" is "dataType" or "dataType[]".
     *          FQN of the PHP class.
     * - primaryKey: Optional if the "type" is "dataType[]".
     */
    public static function getPropertyInfo(): array
    {
        return [];
    }

    /**
     * The `additionalProperties` name can cause a conflict.
     */
    public array $additionalProperties = [];
}
