<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('assets'))
{
    function assets($order = '')
    {
        switch ($order) {
            case 'admin':
                $html = <<<"EOT"
                    <div class="card">
                        <img src="simg" alt="">
                        <h2>ss</h2>
                        <p>ss</p>
                    </div>
                EOT;

                echo $html;
                break;
            
            default:
                # code...
                break;
        }
    }   
}