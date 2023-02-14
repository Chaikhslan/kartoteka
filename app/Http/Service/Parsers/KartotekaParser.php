<?php

namespace App\Http\Service\Parsers;

use App\Models\EtpKartoteka;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class KartotekaParser
{
    public function getData()
    {
        $client = new Client();
        $crawler = new Crawler();

        // Ссылки на все страницы
        $pages = ['https://etp.kartoteka.ru/index.html'];
        $currentPage = 1;

        // Получаем ссылки на все страницы
        do {
            $res = $client->request('GET', $pages[$currentPage - 1]);

            $html = (string) $res->getBody();
            $crawler->clear();
            $crawler->addHtmlContent($html);

            // Находим ссылки на следующие страницы
            $links = $crawler->filter('td[colspan="5"] a')->each(function ($node) {
                return 'https://etp.kartoteka.ru' . $node->attr('href');
            });

            $pages = array_merge($pages, $links);

            $currentPage++;
        } while (isset($links[$currentPage - 2]));

        // Получаем данные со всех страниц
        $data = [];

        foreach ($pages as $page) {
            $res = $client->request('GET', $page);

            $html = (string) $res->getBody();
            $crawler = new Crawler($html);

            $crawler->filter('tr')->each(function ($tr) use (&$data) {
                $tds = $tr->filter('td')->extract(['_text']);
                if (count($tds) === 5) {
                    $etp = EtpKartoteka::find($tds[0]);
                    if (!$etp) {
                        $etp = new EtpKartoteka();
                        $etp->id_number = trim($tds[0]);
                    }
                    $etp->person_name = trim($tds[1]);
                    $etp->company_name = trim($tds[2]);
                    $etp->status = mb_convert_encoding(trim($tds[3]), 'UTF-8', 'auto');
                    $etp->date = trim($tds[4]);
                    $etp->save();
                }
            });
        }
    }
}
