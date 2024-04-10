<?php

namespace App\Command;

use App\Entity\Rate;
use Doctrine\ORM\EntityManagerInterface;
//use Shapecode\Bundle\CronBundle\Attribute\AsCronJob;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

//#[AsCronJob('*/5 * * * *', null, 100)]
class RateFillCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct('app:rate-fill');
    }

    public function configure()
    {
        $this->setName('app:rate-fill');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $curl = curl_init('http://www.cbr.ru/scripts/XML_daily.asp');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $xml = curl_exec($curl);
        $html = simplexml_load_string($xml);
        $json = json_encode($html);
        $json = json_decode($json, true);


        $rates = $this->em->getRepository(Rate::class)->findAll();
        if (empty($rates)) {
            foreach ($json['Valute'] as $domElement) {
                $rate = new Rate();
                $rate->setNumCode($domElement['NumCode']);
                $rate->setCharCode($domElement['CharCode']);
                $rate->setNominal($domElement['Nominal']);
                $rate->setName($domElement['Name']);
                $rate->setValue(number_format(str_replace(',', '.', $domElement['Value']), 2));
                $rate->setVunitRate(number_format(str_replace(',', '.', $domElement['VunitRate']), 2));
                $this->em->persist($rate);
                $this->em->flush();
            }
        } else {
            foreach ($json['Valute'] as $domElement) {
                $rate = $this->em->getRepository(Rate::class)->findOneBy(['charCode' => $domElement['CharCode']]);
                $rate->setNumCode($domElement['NumCode']);
                $rate->setCharCode($domElement['CharCode']);
                $rate->setNominal($domElement['Nominal']);
                $rate->setName($domElement['Name']);
                $rate->setValue(number_format(str_replace(',', '.', $domElement['Value']), 2));
                $rate->setVunitRate(number_format(str_replace(',', '.', $domElement['VunitRate']), 2));
                $this->em->persist($rate);
                $this->em->flush();
            }
        }

        return Command::SUCCESS;
    }

}