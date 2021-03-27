<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Start extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('curl');
    }

    public function getNewHastag()
    {
        $instagram = new Instagram();
        print_r($instagram->getNewHastag());
    }

    public function getUsername()
    {
        $username = $this->uri->segment(4);
        $instagram = new Instagram();
        print_r($instagram->getInfoAccount($username));
    }

    public function getAllMediaByHastag()
    {
        $hastag = $this->uri->segment(4);
        $instagram = new Instagram();
        print_r($instagram->getAllMediaByHastag($hastag));
    }

    public function getProductShopeeByKeyword()
    {
        // $str = "Hi dear, welcome to buy this Laptop keyboard cover pls contact us before you place the order to  show me  a picture  of your keyboard; so that we will ship it for you immediately.Thank you so much!!🍀\n\n*Applicable models： FOR HP 15.6 inch laptop HP Pavilion Series 15-cc707TX BF\n \n*​Features: \n 1. Every key is individually molded to fit your Keyboard. \n 2. Design to provide the full protection for your keyboard against dust spills, key wear and more.\n 3. Flexible, washable, easy to apply and remove for cleaning or disinfecting. Extra slim silicone skin, made it easier for typing.\n\nPackage Contents:\n\n1* Laptop keyboard cover\n\nNote:\n\n1. The real color of the item may be slightly different from the pictures shown on website caused by many factors such as brightness of your monitor and light brightness.\n\n2. Please allow slight manual measurement deviation for the data. \n #keyboard #keyboardCOVER #dustcover #protection  #keyboardmembrane#keyboard Film#Computer Accesso";
        // $st = explode("#", $str);
        // unset($st[0]);
        // print_r($st);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://shopee.co.id/api/v2/search_items/?by=relevancy&keyword=laptop&limit=100&newest=0&order=desc&page_type=search&version=2");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        echo $output;

        // if ($curl->error) {
        //     echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        // } else {
        //     //echo 'Response:' . "\n";
        //     return $curl->response;
        // }

    }

    public function getUniqueContentByHastag()
    {
        $hastag = $this->uri->segment(4);
        $instagram = new Instagram();
        $source = $instagram->getAllMediaByHastag($hastag);
        $datasets = $source['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
        $firstData = array();
        for ($i = 0; $i < count($datasets); $i++) {
            $children = $datasets[$i]['node'];
            $firstData[] = $children['edge_media_to_caption']['edges'][0]['node']['text'];
        }

        // print_r($firstData);

        // echo count($secondData);

        // echo count($firstData);

        for ($y = 0; $y < count($firstData); $y++) {
            $first_string = $firstData[$y];
            $sec_string = $firstData[mt_rand(8, 60)];

            // echo "
            // <b>1</b> $first_string<br>
            // <b>2</b> $sec_string<br>
            // <hr>
            // ";

            // $comment = $secondData[$y];
            $first = explode(' ', $first_string);
            $second = explode(' ', $sec_string);

            // echo $second;

            $first = array_filter($first, function ($text) {
                return strlen($text) > 3;
            });
            $second = array_filter($second, function ($text) {
                return strlen($text) > 3;
            });

            $common = array_intersect($first, $second);

            foreach ($common as $word) {
                $sec_string = preg_replace("/($word)/i", '<span style="color:red">$1</span>', $sec_string);
            }

            echo "
            // <b>Text Utama</b> : $first_string<br>
            <b>Text Kedua</b> : $sec_string<br>
            <hr>
            ";

        }

        // echo $description . "<br>";
    }

    public function getRareHastag()
    {
        $instagram = new Instagram();
        print_r($instagram->getRareHastag());
    }

    public function getFrequently()
    {
        $instagram = new Instagram();
        print_r($instagram->getFrequentlyHastag());
    }

    public function getAverageHastag()
    {
        $instagram = new Instagram();
        print_r($instagram->getAverageHastag());
    }

    public function getDetailHastag()
    {
        $hastag = $this->uri->segment(4);
        $instagram = new Instagram();
        print_r($instagram->getDetailByHastag($hastag));
    }

    public function findAll()
    {
        $instagram = new Instagram();
        print_r($instagram->getNewHastag());
    }

    public function getBestHastag()
    {
        $instagram = new Instagram();
        print_r($instagram->getBestHastag());
    }

    public function index()
    {
        $username = $this->uri->segment(4);
        $instagram = new Instagram();
        $userData = $instagram->getInfoAccount($username);
        $userDetail = $userData['entry_data']['ProfilePage'][0]['graphql']['user'];

        // starting to analyze
        if (empty($username)) {
            echo 'Tidak ada akun yang dianalisa';
        } else {
            if ($userDetail['is_private'] == 1) {
                echo 'Akun anda private! Layanan ini khusus penggunan non private';
            } else {

                if (count($userDetail['edge_owner_to_timeline_media']['edges']) === 0) {
                    echo 'Anda tidak memiliki postingan pada timeline anda';
                } else {

                    echo "Memulai analisa data pada akun $username<br>
                ============================================<br>";

                    $groupingData = array();
                    $groupingComment = array();
                    $hasFollowers = $userDetail['edge_followed_by']['count'];
                    for ($i = 0; $i < count($userDetail['edge_owner_to_timeline_media']['edges']); $i++) {
                        $haveLikes = $userDetail['edge_owner_to_timeline_media']['edges'][$i]['node']['edge_liked_by']['count'];
                        $haveComments = $userDetail['edge_owner_to_timeline_media']['edges'][$i]['node']['edge_media_to_comment']['count'];
                        $mediaImage = $userDetail['edge_owner_to_timeline_media']['edges'][$i]['node']['thumbnail_src'];
                        $calculateEngagement = $instagram->sum_perPost([$hasFollowers, $haveLikes, $haveComments]);
                        $calculateComment = array_sum([$haveComments]) / 12;
                        array_push($groupingData, $calculateEngagement);
                        array_push($groupingComment, $calculateComment);

                        echo "
                    <b>Post $i</b><br>
                    Jumlah Like = $haveLikes<br>
                    Jumlah Komentar = $haveComments<br>
                    Link Media = <a href='$mediaImage'>Klik disini</a><br>
                    Total Engagement Post $i = $calculateEngagement %<br>
                    <br>========================================</br>";
                    }

                    echo "Total Engagement 12 Post terakhir sebesar " .
                    $instagram->sumAllPost($groupingData) . "%" . "<br>" . 
                    "Total Comment 12 Post sebesar ";
                    echo $instagram->roundNum(array_sum($groupingComment));

                }
            }
        }
    }

}
