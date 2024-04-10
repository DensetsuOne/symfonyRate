<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:conversion')]
class ConversionCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('app:conversion');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $curl = curl_init('http://www.cbr.ru/scripts/XML_daily.asp');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $xml = curl_exec($curl);
        $html = simplexml_load_string($xml);
        $json = json_encode($html);
        $json = json_decode($json, true);
        foreach ($json['Valute'] as $domElement) {
            $output->write($domElement['CharCode'] . ' - ');
            $output->writeln($domElement['Name']);
        }

        $code = $io->ask('Выберите код страны');

        foreach ($json['Valute'] as $domElement) {
            if ($domElement['CharCode'] == $code) {
                $io->info('Вы успешно выбрали ' . $code);
                $val = $domElement['VunitRate'];
            }
        }
        if (!isset($val)) {
            $io->error('Веден неверный код');
            return Command::FAILURE;
        }
        $value = $io->ask('Введите сколько хотите конвертировать валюты из ' . $code . ' в рубли');
        if (empty($value)) {
            $io->error('Не была введена валюта');
            return Command::FAILURE;
        } elseif (!is_numeric($value)) {
            $io->error('Был выбран не верный тип данных');
            return Command::FAILURE;
        }
        $value = str_replace(',', '.', $value);
        $val = str_replace(',', '.', $val);
        $value = number_format($value, 2);
        $io->success(number_format($val * $value, 2) . ' руб.');
        return Command::SUCCESS;
    }

}