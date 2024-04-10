<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConversionController extends AbstractController
{
    public function rate(Request $request, Security $security): Response
    {
        if ($security->getUser()) {
            $code = $request->get('code');
            $val = $request->get('val');
            $xml = file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp');
            $html = simplexml_load_string($xml);
            $json = json_encode($html);
            $json = json_decode($json, true);

            if ($code) {

                foreach ($json['Valute'] as $domElement) {
                    if ($domElement['CharCode'] == $code) {
                        $value = str_replace(',', '.', $domElement['VunitRate']);
                        $val = str_replace(',', '.', $val);
                        $value = number_format($value, 2);
                        $sum = $val * $value;
                    }
                }
                return new JsonResponse([
                    'sum' => number_format($sum, 2) ?? null,
                ]);
            }
        }

       return $this->render('conversion.html.twig', [
           'json' => $json['Valute'] ?? null,
           'sum'  => $sum ?? null,
       ]);
    }
}