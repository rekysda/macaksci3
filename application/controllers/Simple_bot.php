<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Simple_bot extends CI_Controller
{

    public function index()
    {
        $token = '1186559289:AAH1OHBybUiba_KvmOLGPxm79TaIu105BcE';
        $method = 'sendMessage?';
        $data = [
            'chat_id' => 821856771,
            'text' => 'hellooooooooooooooooooo'
        ];
        // print_r($data);
        // die;
        $url = 'https://api.telegram.org/' . $token . '/' . $method;
        $ch = curl_init();

        if ($ch) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $return =  curl_exec($ch);
            curl_close($ch);
            return $return;
            // print_r($return);
        } else {
            exit;
        }
        // $this->_sendMessage($);
    }
}

/* End of file Simple_bot.php */
