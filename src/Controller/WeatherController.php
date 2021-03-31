<?php

namespace App\Controller;

use App\Entity\Weather;
use App\Form\Model\WeatherFormModel;
use App\Form\WeatherType;
use App\Repository\WeatherRepository;
use App\Service\CalculateTempTrait;
use App\Service\Client;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    use CalculateTempTrait;

    /**
     * @Route("/", name="weather")
     */
    public function index(Request $request, Client $client, LoggerInterface $logger): Response
    {
        $form = $this->createForm(WeatherType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var WeatherFormModel $weatherModel */
            $weatherModel = $form->getData();

            $city = $weatherModel->city;
            $country = $weatherModel->country;

            $weather = new Weather();
            $weather->setCity($city);
            $weather->setCountry($country);

            $error = false;
            $exception = '';

            try {
                $response = $client->call($city, $country);
            } catch (\Exception $e) {
                $error = true;
                $exception = $e->getCode();
                $logger->error($e->getMessage());
            }

            if (!$error) {
                $temp = $this->calculateAverageTemp($response);
                $weather->setTemperature($temp);

                $em = $this->getDoctrine()->getManager();
                $em->persist($weather);
                $em->flush();

                $this->addFlash('success', 'Temperature fetched');

                return $this->redirectToRoute('weather_result', [
                    'city' => $weather->getCity()
                ]);
            } else {
                $this->addFlash('error', 'Error code #'.$exception);
            }
        }

        return $this->render('weather/index.html.twig', [
            'weatherForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/weather/{city}", name="weather_result")
     */
    public function showWeather(string $city, WeatherRepository $weatherRepository)
    {
        $weather = $weatherRepository->findOneBy(['city' => $city], ['id' => 'DESC']);

        return $this->render('weather/show.html.twig', [
            'weather' => $weather
        ]);
    }

}
