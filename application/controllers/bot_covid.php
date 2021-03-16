<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bot_covid extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // token api telegram
        $token = '1186559289:AAHYRAoLQG0HddMP6XSlyz7dcsGb-w60nGE';

        // membaca file json dari api telegram
        $update = file_get_contents('php://input');

        // parsing biar jadi object
        $update = json_decode($update);
        $id = $update->message->from->id;
        $hear = $update->message->text;
        $username = $update->message->chat->username;
        // $cekhear = $this->db->where(['hear' => $hear])->get('bot')->row();

        // covid
        $covid = file_get_contents('https://services5.arcgis.com/VS6HdKS0VfIhv8Ct/arcgis/rest/services/COVID19_Indonesia_per_Provinsi/FeatureServer/0/query?where=1%3D1&outFields=*&outSR=4326&f=json');
        $covid_encode = json_decode($covid)->features;

        // cek command yang ada pada database
        // $cek_provinsi = $this->db->get('bot')->result(); 
        $cek_provinsi = $this->db->get_where('bot', ['hear' => '/provinsi'])->row();

        // cek string provinsi true/false
        $provinsi = '';
        foreach ($covid_encode as $c) {
            $provinsi .= '/' . $c->attributes->Provinsi . ', ';
        }

        // hapus keyboard
        $rmkeyboard = [
            'remove_keyboard' => true
        ];

        // default keyboard
        $default_keyboard = [
            'keyboard' => [
                [
                    ['text' => '/infocovid']
                ]
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ];

        //data dari array attributes
        foreach ($covid_encode as $c) {
            // untuk keyboard
            $pilih_provinsi[] = [
                ['text' => $c->attributes->Provinsi]
            ];

            // untuk ngambil key dari tiap provinsi
            $data_provinsi[] = $c->attributes;
        }

        // ngambil key berdasarkan nama provinsi
        $keys = array_keys(array_column($data_provinsi, 'Provinsi'), $hear);
        $data_perprov = $data_provinsi[$keys[0]];

        // provinsi keyboard
        $provinsi_keyboard = [
            'keyboard' => $pilih_provinsi,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ];

        switch ($hear) {
            case '/infocovid':
                $this->db->insert('bot', ['hear' => '/provinsi']);
                $response = 'pilih provinsi';
                $reply_markup = json_encode($provinsi_keyboard);
                break;

                // case 'batal':
                //     $this->db->where(['hear' => '/provinsi'])->delete('bot');
                //     $response = 'hallo <b>@' . $username . '</b>' . "\n\n" .
                //         'berikut informasi yang bisa kamu akses :' . "\n" .
                //         '/infocovid - Informasi Covid Provinsi di Indonesia';
                //     $reply_markup = json_encode($default_keyboard);
                //     break;

            default:
                switch ($cek_provinsi->hear) {
                    case '/provinsi':
                        switch (strpos($provinsi, $hear . ', ')) {
                            case true:
                                $this->db->where(['hear' => '/provinsi'])->delete('bot');
                                $response = '<b>Informasi Covid Provinsi di Indonesia' . "\n" .
                                    date("l, d/m/Y") . '</b>' . "\n\n" .
                                    '<b>' . $data_perprov->Provinsi . "</b>\n" .
                                    'Positif : ' . $data_perprov->Kasus_Posi . "\n" .
                                    'Sembuh : ' . $data_perprov->Kasus_Semb . "\n" .
                                    'Meninggal : ' . $data_perprov->Kasus_Meni . "\n\n";
                                $reply_markup = json_encode($default_keyboard);
                                break;

                            default:
                                $response = 'pilih provinsi';
                                $reply_markup = json_encode($provinsi_keyboard);
                                break;
                        }
                        break;

                    default:
                        $response = 'Maaf, command tidak tersedia! ' . "\n" . 'silahkan akses tombol dibawah ini : ';
                        $reply_markup = json_encode($default_keyboard);
                        break;
                }
                break;
        }

        $data = [
            'chat_id' => $id,
            'text' => $response,
            'parse_mode' => 'html',
            'reply_markup' => $reply_markup,
        ];
        $method = '/sendMessage?';
        $this->_responseBot($token, $method, $data);
    }

    private function _responseBot($token, $method, $data)
    {
        $url = 'https://api.telegram.org/bot' . $token . $method;

        if (!$curld = curl_init()) {
            exit;
        }
        curl_setopt($curld, CURLOPT_POST, true);
        curl_setopt($curld, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curld, CURLOPT_URL, $url);
        curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($curld);
        curl_close($curld);
        return $output;
    }
}
