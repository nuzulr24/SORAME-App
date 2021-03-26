<?php
defined('BASEPATH') or exit('No direct script access allowed');
// header('Content-Type: application/json');

class Instagram
{

    protected $CI;
    protected $hastag;
    protected $username;
    protected $base_path = "http://best-hashtags.com/";
    protected $insta_path = "https://www.instagram.com/";

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    // function decode encode array

    public function decode($args)
    {
        return json_decode($args, true);
    }

    public function encode($args)
    {
        return json_encode($args, true);
    }

    public function splitArray($args)
    {
        $encode = json_encode($args);
        return json_decode($encode, true);
    }

    // function equation instagram engagement

    public function roundNum($val)
    {
        return number_format((float)$val, 2, '.', '');
    }

    public function sum_perPost($args)
    {
        $follow = $args[0];
        $like = $args[1];
        $comment = $args[2];

        return $this->roundNum(($like + $comment) / $follow * 100);
    }

    public function sumAllPost($val) 
    {
        return array_sum($val);
    }


    // function instagram

    public function getInfoAccount($username)
    {
        if (empty($username)) {
            return ['msg' => 'Tidak ditemukan username!'];
        } else {
            $insta = file_get_contents($this->insta_path . "$username");
            $pecahkan = explode('window._sharedData = ', $insta);
            $data_json = explode(';</script>', $pecahkan[1]);
            return json_decode($data_json[0], true);
        }
    }

    public function getAllMediaByHastag($hastag)
    {
        $insta = file_get_contents($this->insta_path . "explore/tags/" . $hastag); // instagrame tag url
        $pecahkan = explode('window._sharedData = ', $insta);
        $data_json = explode(';</script>', $pecahkan[1]);
        return json_decode($data_json[0], true);
    }

    public function getNewHastag()
    {
        $html = file_get_html($this->base_path . "new-hashtags.php");
        $list = array();
        for ($i = 0; $i < 18; $i++) {
            $data = $html->find('tbody', 0)->find('tr', $i);
            $list[] = [
                'no_urut' => $data->find('td', 0)->plaintext,
                'tag' => $data->find('td a', 0)->plaintext,
                'frequently' => $data->find('td[width=15%]', 0)->plaintext,
            ];
        }
        return $list;
        $html->clear();
    }

    public function getBestHastag()
    {
        $html = file_get_html($this->base_path . "best-hashtags.php");
        $list = array();
        for ($i = 0; $i < 18; $i++) {
            $data = $html->find('tbody', 0)->find('tr', $i);
            $list[] = [
                'no_urut' => $data->find('td', 0)->plaintext,
                'tag' => $data->find('td a', 0)->plaintext,
                'frequently' => $data->find('td[width=15%]', 0)->plaintext,
            ];
        }
        return $list;
        $html->clear();
    }

    public function getDetailByHastag($hastag)
    {
        $data = [$hastag];
        $result = array_map(function ($getData) {
            $tag = $getData;
            $html = file_get_html($this->base_path . "hashtag/" . $tag . "/");
            $hastag1 = $html->getElementsByTagName('p1', 0)->plaintext;
            $hastag2 = $html->getElementsByTagName('p2', 0)->plaintext;

            // hastag top 10
            $topHastag = array();
            for ($i = 0; $i < 8; $i++) {
                $countTag = $html->find('h3[class=heading-xs list-unstyled save-job]', $i)->plaintext;
                $pecah = explode('-', $countTag);
                $nama = $pecah[0];
                $topHastag[] = [
                    'nama' => $nama,
                    'total' => $pecah[1],
                ];
            }

            // recommended hastag 18
            $listRecommended = array();
            for ($x = 0; $x < 8; $x++) {
                $listRecommended[] = $html->find('ul[class=list-unstyled save-job] li a', $x)->plaintext;
            }

            return [
                'most_popular' => $hastag1,
                'most_like' => $hastag2,
                'report' => [
                    'total_post_hastag' => $html->find('div[class=overflow-h] small', 1)->plaintext,
                    'total_post_hour' => $html->find('div[class=overflow-h] small', 3)->plaintext,
                ],
                'top_hastag' => $topHastag,
                'recommended_hastag' => $listRecommended,
                'updated_date' => date('j F Y h:i:s'),
            ];

            $html->clear();

        }, $data);
        return $result;
    }

}
