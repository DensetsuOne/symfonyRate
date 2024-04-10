<?php

namespace App\Controller;

use App\Entity\Rate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConversionController extends AbstractController
{
    public function rate(Request $request, Security $security, EntityManagerInterface $em): Response
    {
        if ($security->getUser()) {
            $code = $request->get('code');
            $val = $request->get('val');
            $rate = $em->getRepository(Rate::class)->findAll();
            if (empty($rate)) {
                $xml = file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp');
                $html = simplexml_load_string($xml);
                $json = json_encode($html);
                $rate = json_decode($json, true);
                $rate = $rate['Valute'];
            }

            if ($code) {
                $rates = $em->getRepository(Rate::class)->findAll();
                if (empty($rates)) {

                    foreach ($rate as $domElement) {
                        if ($domElement['CharCode'] == $code) {
                            $value = str_replace(',', '.', $domElement['VunitRate']);
                            $val = str_replace(',', '.', $val);
                            $value = round($value, 2);
                            $sum = $val * $value;
                        }
                    }
                } else {
                    foreach ($rate as $domElement) {
                        if ($domElement->getCharCode() == $code) {
                            $value = str_replace(',', '.', $domElement->getVunitRate());
                            $val = str_replace(',', '.', $val);
                            $value = round($domElement->getVunitRate(), 2);
                            $sum = $val * $value;
                        }
                    }
                }
                return new JsonResponse([
                    'sum' => number_format($sum, 2) ?? null,
                ]);
            }
        }

       return $this->render('conversion.html.twig', [
           'json' => $rate,
           'sum'  => $sum ?? null,
       ]);
    }
}