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
        return array_sum($val) / 12;
    }

    public function cleanNumber($val)
    {
        $num = str_replace(',' , '', $val);
        if(is_numeric($num)) {
            return $num;
        }
    }

    public function checkText($word){
        return strlen( $word ) > 3;
    }

    function get_decorated_diff($old, $new){
        $originalOld = $old;
        $originalNew = $new;
        $old = strtolower($old); //Add this line
        $new = strtolower($new); //Add this line
    
        $from_start = strspn($old ^ $new, "\0");        
        $from_end = strspn(strrev($old) ^ strrev($new), "\0");
    
        $old_end = strlen($old) - $from_end;
        $new_end = strlen($new) - $from_end;
    
        $start = substr($new, 0, $from_start);
        $end = substr($new, $new_end);
        $new_diff = substr($originalNew, $from_start, $new_end - $from_start);  
        $old_diff = substr($originalOld, $from_start, $old_end - $from_start);
    
        $new = "$start<ins style='background-color:#ccffcc'>$new_diff</ins>$end";
        $old = "$start<del style='background-color:#ffcccc'>$old_diff</del>$end";
        return array("old"=>$old, "new"=>$new);
    }

    public function number_shorten($number, $precision = 3, $divisors = null) {

        if (!isset($divisors)) {
            $divisors = array(
                pow(1000, 0) => '', // 1000^0 == 1
                pow(1000, 1) => 'K', // Thousand
                pow(1000, 2) => 'M', // Million
                pow(1000, 3) => 'B', // Billion
                pow(1000, 4) => 'T', // Trillion
                pow(1000, 5) => 'Qa', // Quadrillion
                pow(1000, 6) => 'Qi', // Quintillion
            );    
        }
        foreach ($divisors as $divisor => $shorthand) {
            if (abs($number) < ($divisor * 1000)) {
                break;
            }
        }
        return number_format($number / $divisor, $precision) . $shorthand;
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
        $insta = file_get_contents($this->insta_path . "explore/tags/" . $hastag);
        $pecahkan = explode('window._sharedData = ', $insta);
        $data_json = explode(';</script>', $pecahkan[1]);
        return json_decode($data_json[0], true);
    }

    public function getNewHastag()
    {
        $html = file_get_html($this->base_path . "new-hashtags.php");
        $list = array();
        for ($i = 0; $i < 20; $i++) {
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
        for ($i = 0; $i < 20; $i++) {
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

    public function getRareHastag()
    {
        $html = file_get_html("http://best-hashtags.com/best-hashtags.php");
        $list = array();
        for ($i = 0; $i < 5; $i++) {
            $data = $html->find('tbody', 0)->find('tr', $i);
            $list[] = [
                'no_urut' => $data->find('td', 0)->plaintext,
                'tag' => $data->find('td a', 0)->plaintext,
                'frequently' => $data->find('td[width=15%]', 0)->plaintext,
            ];
        }
        $rareTag = array();
        foreach($list as $datasets)
        {
            $freq = $this->cleanNumber($datasets['frequently']);
            $maxValue = max($datasets);
            $maxIndex = array_search(max($datasets), $datasets);
            $rareTag[] = [
                'max_value' => $maxValue,
                'max_index' => $maxIndex,
                'tag' => $datasets['tag']
            ];
        }

        return $rareTag;
        $html->clear();
    }

    public function getFrequentlyHastag()
    {
        $html = file_get_html("http://best-hashtags.com/best-hashtags.php");
        $list = array();
        for ($i = 0; $i < 5; $i++) {
            $data = $html->find('tbody', 0)->find('tr', $i);
            $list[] = [
                'no_urut' => $data->find('td', 0)->plaintext,
                'tag' => $data->find('td a', 0)->plaintext,
                'frequently' => $data->find('td[width=15%]', 0)->plaintext,
            ];
        }
        $rareTag = array();
        foreach($list as $datasets)
        {
            $freq = $this->cleanNumber($datasets['frequently']);
            $maxValue = max($datasets);
            $maxIndex = array_search(max($datasets), $datasets);
            $rareTag[] = [
                'max_value' => $maxValue,
                'max_index' => $maxIndex,
                'tag' => $datasets['tag']
            ];
        }

        return $rareTag;
        $html->clear();
    }

    public function getAverageHastag()
    {
        $html = file_get_html($this->base_path . "best-hashtags.php");
        $list = array();
        for ($i = 0; $i < 10; $i++) {
            $data = $html->find('tbody', 0)->find('tr', $i);
            $list[] = [
                'no_urut' => $data->find('td', 0)->plaintext,
                'tag' => $data->find('td a', 0)->plaintext,
                'frequently' => $data->find('td[width=15%]', 0)->plaintext,
            ];
        }

        $datasets = array();
        foreach($list as $row) {
            $name = $row['frequently'];
            if (!isset($datasets[$name])) {
                $datasets[$name] = array('count' => 1, 'frequently' => $this->cleanNumber($row['frequently']), 'tag' => $row['tag']);
            }
            else {
                $datasets[$name]['count']++;
                $datasets[$name]['sum'] += $this->cleanNumber($row['frequently']);
            }
        }

        // $frequently = $instagram->number_shorten(round($r['frequently'] / $r['count'], 1));
        return $datasets;
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

            // related hastag 18
            $relatedHastag = array();
            for($z = 1; $z < 10; $z++) {
                $relatedHastag[] = [
                    'no_urut' => $html->find('tr', $z)->find('td', 0)->plaintext,
                    'nama' => $html->find('tr', $z)->find('td', 1)->plaintext,
                    'frequently' => $html->find('tr', $z)->find('td', 2)->plaintext
                ];
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
                'related_hastag' => $relatedHastag,
                'updated_date' => date('j F Y h:i:s'),
            ];

            $html->clear();

        }, $data);
        return $result;
    }

}
