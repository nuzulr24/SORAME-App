<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Start extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
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

    public function getShopee()
    {
        $str = "Hi dear, welcome to buy this Laptop keyboard cover pls contact us before you place the order to  show me  a picture  of your keyboard; so that we will ship it for you immediately.Thank you so much!!🍀\n\n*Applicable models： FOR HP 15.6 inch laptop HP Pavilion Series 15-cc707TX BF\n \n*​Features: \n 1. Every key is individually molded to fit your Keyboard. \n 2. Design to provide the full protection for your keyboard against dust spills, key wear and more.\n 3. Flexible, washable, easy to apply and remove for cleaning or disinfecting. Extra slim silicone skin, made it easier for typing.\n\nPackage Contents:\n\n1* Laptop keyboard cover\n\nNote:\n\n1. The real color of the item may be slightly different from the pictures shown on website caused by many factors such as brightness of your monitor and light brightness.\n\n2. Please allow slight manual measurement deviation for the data. \n #keyboard #keyboardCOVER #dustcover #protection  #keyboardmembrane#keyboard Film#Computer Accesso";
        $st = explode("#", $str);
        unset($st[0]);
        print_r($st);
    }

    public function getDetailHastag()
    {
        $instagram = new Instagram();
        print_r($instagram->getDetailByHastag('selebgramindo'));
    }

    public function findAll()
    {
        $instagram = new Instagram();
        print_r($instagram->getNewHastag());
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
                    $hasFollowers = $userDetail['edge_followed_by']['count'];
                    for ($i = 0; $i < count($userDetail['edge_owner_to_timeline_media']['edges']); $i++) {
                        $haveLikes = $userDetail['edge_owner_to_timeline_media']['edges'][$i]['node']['edge_media_to_comment']['count'];
                        $haveComments = $userDetail['edge_owner_to_timeline_media']['edges'][$i]['node']['edge_media_to_comment']['count'];
                        $mediaImage = $userDetail['edge_owner_to_timeline_media']['edges'][$i]['node']['thumbnail_src'];
                        $calculateEngagement = $instagram->sum_perPost([$hasFollowers, $haveLikes, $haveComments]);
                        array_push($groupingData, $calculateEngagement);

                        echo "
                    =============================================</br>
                    <b>Post $i</b><br>
                    Jumlah Like = $haveLikes<br>
                    Jumlah Komentar = $haveComments<br>
                    Link Media = <a href='$mediaImage'>Klik disini</a><br>
                    Total Engagement Post $i = $calculateEngagement %<br>
                    <br>========================================</br>";
                    }

                    echo "Total Engagement 12 Post terakhir sebesar " .
                    $instagram->sumAllPost($groupingData) . "%";

                }
            }
        }
    }

}
