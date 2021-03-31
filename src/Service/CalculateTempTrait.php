<?php


namespace App\Service;


trait CalculateTempTrait
{
    /**
     * @param array $temps
     * @return float|int
     */
    public function calculateAverageTemp(array $temps)
    {
        $result = [];

        foreach ($temps as $temp) {
            /** @var TemperatureInterface $temp */
            $result[] = $temp->getTemp();
        }

        $filter = array_filter($result);

        return array_sum($filter)/count($filter);
    }
}