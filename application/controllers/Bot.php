<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bot extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_mahasiswa');
        $this->load->library('session');
    }

    public function coba()
    {
        $covid = file_get_contents('https://services5.arcgis.com/VS6HdKS0VfIhv8Ct/arcgis/rest/services/COVID19_Indonesia_per_Provinsi/FeatureServer/0/query?where=1%3D1&outFields=*&outSR=4326&f=json');
        $covid_encode = json_decode($covid)->features;
        // if ($this->db->get('bot')->result()) {
        //     echo 'ada data';
        // } else {
        //     echo 'kosong';
        // }
        // print_r($this->db->get('bot')->row()->hear);
        // $nilai = $this->M_mahasiswa->db->where(['ta' => '2018-1'])->get('nilai')->result();
        // print_r($covid_encode);
        $kopet = '';
        foreach ($covid_encode as $covid => $c) {
            $kopet .= $c->attributes->Provinsi . ' : ' . $c->attributes->Kasus_Posi . "\n";
        }
        echo $kopet;
        // $nilai = $this->M_mahasiswa->db->where(['ta' => '2018-1'])->get('nilai')->row();

        // // $response = '';
        // foreach ($nilai as $n) {
        //     echo 'Daftar Nilai TA <b>' . $n->ta . "</b>\n\n" .
        //         'Kode: <b>' . $n->kd_makul . "</b>\n" .
        //         'Makul : <b>' . $n->makul . "</b>\n" .
        //         'Nilai : <b>' . $n->nilai . "</b>\n\n\n";
        //     // return $response;
        //     // echo $response;
        // }
    }

    public function index()
    {
        $token = '1186559289:AAH1OHBybUiba_KvmOLGPxm79TaIu105BcE';
        $update = file_get_contents('php://input');
        $update = json_decode($update);

        $id = $update->message->from->id;
        $username = $update->message->chat->username;
        $hear = $update->message->text;
        $rmkeyboard = [
            'remove_keyboard' => true
        ];
        $keyboard = [
            'keyboard' => [
                [
                    ['text' => '/jumlah'],
                    ['text' => '/nilai']
                ], [
                    ['text' => '/cari'],
                    ['text' => '/infocovid']
                ]
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ];

        switch ($hear) {
            case '/jumlah':
                if ($this->db->get('bot')->result()) {
                    $response = 'masukkan TA';
                    $keyboard = [
                        'keyboard' => [
                            [
                                ['text' => '2018-1'],
                                ['text' => '2018-2'],
                                ['text' => '2019-1']
                            ],
                            [
                                ['text' => '2019-2'],
                                ['text' => '2020-1'],
                                ['text' => '2020-2']
                            ]
                        ],
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ];
                    $reply_markup = json_encode($keyboard);
                } else {
                    $jmlmhs = $this->db->from('mahasiswa')->count_all_results();
                    $response = 'jumlah mahasiswa ada ' . $jmlmhs;
                    // remove keyboard
                    $reply_markup = json_encode($keyboard);
                }
                break;

            case '/nilai':
                $this->db->insert('bot', ['hear' => '/nilai']);
                $response = 'masukkan TA';
                $keyboard = [
                    'keyboard' => [
                        [
                            ['text' => '2018-1'],
                            ['text' => '2018-2'],
                            ['text' => '2019-1']
                        ],
                        [
                            ['text' => '2019-2'],
                            ['text' => '2020-1'],
                            ['text' => '2020-2']
                        ]
                    ],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true
                ];
                $reply_markup = json_encode($keyboard);
                break;

            case '/infocovid':
                if ($this->db->get('bot')->result()) {
                    $response = 'masukkan TA';
                    $keyboard = [
                        'keyboard' => [
                            [
                                ['text' => '2018-1'],
                                ['text' => '2018-2'],
                                ['text' => '2019-1']
                            ],
                            [
                                ['text' => '2019-2'],
                                ['text' => '2020-1'],
                                ['text' => '2020-2']
                            ]
                        ],
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ];
                    $reply_markup = json_encode($keyboard);
                } else {
                    $covid = file_get_contents('https://services5.arcgis.com/VS6HdKS0VfIhv8Ct/arcgis/rest/services/COVID19_Indonesia_per_Provinsi/FeatureServer/0/query?where=1%3D1&outFields=*&outSR=4326&f=json');
                    $covid_encode = json_decode($covid)->features;
                    $response = '<b>Informasi Covid Provinsi di Indonesia' . "\n" .
                        date("l, d/m/Y") . '</b>' . "\n\n";
                    foreach ($covid_encode as $covid => $c) {
                        $response .= '<b>' . $c->attributes->Provinsi . "</b>\n" .
                            'Positif : ' . $c->attributes->Kasus_Posi . "\n" .
                            'Sembuh : ' . $c->attributes->Kasus_Semb . "\n" .
                            'Meninggal : ' . $c->attributes->Kasus_Meni . "\n\n";
                    }
                }
                break;

            default:

                if ($this->M_mahasiswa->db->where(['hear' => '/nilai'])->get('bot')->row()->hear == '/nilai') {
                    if ($hear = $this->M_mahasiswa->db->get_where('nilai', ['ta' => $hear])->row()->ta) {
                        $this->db->where('hear', '/nilai')->delete('bot');
                        $nilai = $this->M_mahasiswa->db->where(['ta' => $hear])->get('nilai')->result();

                        $response = '<b>Daftar Nilai TA ' . $hear . "</b>\n\n";
                        foreach ($nilai as $n) {
                            $response .=
                                '<b>' . $n->makul . ' (' . $n->kd_makul . ")</b>\n" .
                                'Nilai : <b>' . $n->nilai . "</>\n\n";
                        }
                        // $nilai = $this->M_mahasiswa->db->where(['ta' => '2018-1'])->get('nilai')->row();
                        // $response =
                        //     'Daftar Nilai TA <b>' . $nilai->ta . "</b>\n\n" .
                        //     'Kode: <b>' . $nilai->kd_makul . "</b>\n" .
                        //     'Makul : <b>' . $nilai->makul . "</b>\n" .
                        //     'Nilai : <b>' . $nilai->nilai . "</b>\n\n\n";
                        $reply_markup = json_encode($keyboard);
                    } else {
                        $response = 'masukkan TA';
                        $keyboard = [
                            'keyboard' => [
                                [
                                    ['text' => '2018-1'],
                                    ['text' => '2018-2'],
                                    ['text' => '2019-1']
                                ],
                                [
                                    ['text' => '2019-2'],
                                    ['text' => '2020-1'],
                                    ['text' => '2020-2']
                                ]
                            ],
                            'resize_keyboard' => true,
                            'one_time_keyboard' => true
                        ];
                        $reply_markup = json_encode($keyboard);
                    }
                } else if ($hear == '/start') {
                    // jika tidak ada command yang cocok maka lakukan
                    $response = 'hello @' . $username . ",\n\n" .
                        'berikut informasi yang bisa kamu akses :' . "\n" .
                        '1. /jumlah - Informasi Status Mahasiswa' . "\n" .
                        '2. /nilai - Daftar Nilai Mahasiswa' . "\n" .
                        '3. /cari - Cari Data Mahasiswa' . "\n" .
                        '4. /infocovid - Informasi Covid Provinsi di Indonesia';
                    // add keyboard
                    $reply_markup = json_encode($keyboard);
                    break;
                } else {
                    $response = 'Maaf, command tidak tersedia! ' . "\n" . 'silahkan akses tombol dibawah ini : ';
                    $reply_markup = json_encode($keyboard);
                }
        }
        $data = [
            'chat_id' => $id,
            'text' => $response,
            'parse_mode' => 'html',
            'reply_markup' => $reply_markup,
            // 'reply_to_message' = $reply_to_message
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

// remove keyboard
// $keyboard = [
//     'remove_keyboard' => true
// ];
// $reply_markup = json_encode($keyboard);