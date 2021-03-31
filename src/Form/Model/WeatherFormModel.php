<?php


namespace App\Form\Model;


use Symfony\Component\Validator\Constraints as Assert;

class WeatherFormModel
{
    /**
     * @Assert\NotBlank(message="Please enter a city")
     */
    public $city;

    /**
     * @Assert\NotBlank(message="Please enter a country")
     */
    public $country;
}