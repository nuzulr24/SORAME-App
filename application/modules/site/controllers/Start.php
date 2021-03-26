<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Start extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->load->library('frontend');
        // $this->load->helper('template');
    }

    public function bulatkan($val)
    {
        return number_format((float)$val, 2, '.', '');
    }

    public function increment($n){
        $return = $n + $n;
        return $return;
    }

    public function index()
    {
        $this->load->library('curl');
        $username = "zeldaababil19";
        $html = file_get_contents("https://www.instagram.com/$username");
        $html = strstr($html,'window._sharedData = ');
        $html = explode("</script>", $html);
        $html = $html[0];
        $html = str_replace("window._sharedData = ","",$html);
        $html = strstr($html,'"edge_owner_to_timeline_media');
        $html = explode(',"edge_saved_media"',$html);
        $html = '{'.$html[0].'}';
        $html = json_decode($html, true);
        // $htmls = json_encode($html, JSON_PRETTY_PRINT);
        // $parse = json_encode($html, true);
        // echo $parse;
        // echo count($html['edge_owner_to_timeline_media']['edges']);
        echo "Analisa data dari username $username<br>===========================</br>";
        $combine = array();
        for ($i=0; $i < count($html['edge_owner_to_timeline_media']['edges']); $i++) { 
            $like = $html['edge_owner_to_timeline_media']['edges'][$i]['node']['edge_liked_by']['count'];
            // print_r($like);
            $comment = $html['edge_owner_to_timeline_media']['edges'][$i]['node']['edge_media_to_comment']['count'];
            $result = $this->bulatkan(($like + $comment) / 704 * 100);
            
            array_push($combine, $result);

            echo "Postingan $i dengan like $like dan comment $comment<br>
            ". $result . "%<br>========================================</br>";
        }
        print ''.print_r($combine,1).'<br>';

        echo array_sum($combine)."%";

        echo 17.76 + 17.05 + 12.64 + 17.05 + 19.32 + 16.34 + 12.07 + 12.64 + 14.35 + 12.78 + 12.22 + 13.35;

    }

}
