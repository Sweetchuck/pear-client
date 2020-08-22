<?php

declare(strict_types = 1);

namespace Sweetchuck\PearClient\Test;

use Sweetchuck\PearClient\DataType\Base;
use Codeception\Actor;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends Actor
{
    use _generated\AcceptanceTesterActions;

    public function doNotSeeAnyAdditionalProperties(Base $data, string $path)
    {
        $class = get_class($data);
        $this->assertSame(
            [],
            $data->additionalProperties,
            "The {$class} - {$path}->additionalProperties field is empty"
        );

        foreach ($data->getPropertyInfo() as $property => $info) {
            $propertyInternal = $info['name'] ?? $property;

            if ($data->$propertyInternal === null) {
                continue;
            }

            if ($info['type'] === 'dataType') {
                $this->doNotSeeAnyAdditionalProperties($data->$propertyInternal, "{$path}->{$propertyInternal}");

                continue;
            }

            if ($info['type'] === 'dataType[]') {
                foreach ($data->$propertyInternal as $key => $subData) {
                    $this->doNotSeeAnyAdditionalProperties($subData, "{$path}->{$propertyInternal}[{$key}]");
                }
            }
        }
    }
}
