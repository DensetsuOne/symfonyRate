<?php

namespace App\Controller;

use App\Entity\Rate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class ConversionController extends AbstractController
{
    public function rate(Request $request, Security $security, KernelInterface $kernel, EntityManagerInterface $em): Response
    {
        if ($security->getUser()) {
            $code = $request->get('code');
            $val = $request->get('val');
            $application = new Application($kernel);
            $application->setAutoExit(false);
            $input = new StringInput('shapecode:cron:scan');
            $output = new BufferedOutput();
            $application->run($input, $output);
            $input = new StringInput('shapecode:cron:run');
            $application->run($input, $output);
            $rate = $em->getRepository(Rate::class)->findAll();
            if (empty($rate)) {
                $input = new StringInput('app:rate-fill');
                $application->run($input, $output);
                $rate = $em->getRepository(Rate::class)->findAll();
            }

            if ($code) {
                foreach ($rate as $domElement) {
                    if ($domElement->getCharCode() == $code) {
                        $val = str_replace(',', '.', $val);
                        $value = round($domElement->getVunitRate(), 2);
                        $sum = $val * $value;
                    }
                }
                return new JsonResponse([
                    'sum' => number_format($sum, 2) . ' руб.' ?? null,
                ]);
            }
        }

       return $this->render('conversion.html.twig', [
           'json' => $rate ?? null,
           'sum'  => $sum ?? null,
       ]);
    }
}