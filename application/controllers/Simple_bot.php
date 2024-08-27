<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Simple_bot extends CI_Controller
{

    public function index()
    {
        $token = '7525171945:AAFjf3Q2dpbBeGOCjmfUC_V7pp7wXdx3r6c';
        $method = 'sendMessage?';
        $data = [
            'chat_id' => 7307834218,
            'text' => 'hellooooooooooooooooooo'
        ];
        // print_r($data);
        // die;
        $url = 'https://api.telegram.org/bot' . $token . '/' . $method;
        $ch = curl_init();

        if ($ch) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $return =  curl_exec($ch);
            curl_close($ch);
            //return $return;
             print_r($return);
        } else {
            exit;
        }
        // $this->_sendMessage($);
    }
}

/* End of file Simple_bot.php */
