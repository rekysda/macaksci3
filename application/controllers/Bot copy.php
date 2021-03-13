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

    // public function coba()
    // {
    //     $nilai = $this->M_mahasiswa->db->where(['ta' => '2018-1'])->get('nilai')->result();
    //     print_r($nilai);
    // }
    //     $ta = $this->M_mahasiswa->db->get('ta')->result();
    //     foreach ($ta as $t) {
    //         echo '<a href="' . $t->ta . '">' . $t->ta . '</a>' . "\n";
    //     }

    //     $s3 = $this->uri->segment(3);
    //     $nilai = $this->M_mahasiswa->db->where(['ta' => $s3])->get('nilai')->result();

    //     foreach ($nilai as $n) {
    //         echo '<li>' . $n->makul . ' : ' . $n->nilai . '</li>';
    //     }

    //     // $s3 = '2018-1';

    //     // switch ($s3) {
    //     //     case '2018-1':
    //     //         $nilai = $this->M_mahasiswa->db->where(['ta' => $s3])->get('nilai')->result();
    //     //         break;

    //     //     default:
    //     //         # code...
    //     //         break;
    //     // }
    //     // // if $this->uri->segment(3);
    // }

    public function index()
    {
        $token = '1186559289:AAGca1hmtsMZ8kWM2-qwh82SMvVq8Idp5Vo';
        $update = file_get_contents('php://input');
        $update = json_decode($update);

        $client = $update->message;
        $client_id = $client->from->id ? $client->from->id : null;

        // print_r($update->message->text);
        // die();

        if ($client_id) {
            // $client_text = $client->text ? $client->text : 'nothing';
            // $client_first_name = $client->from->first_name ? $client->from->first_name : 'anonim';
            // $client_last_name = $client->from->last_name ? $client->from->last_name : 'anonim';
            // $client_full_name = $client_first_name . '' . $client_last_name;
            // $reply_message = 'hello ' . $client_full_name;
            $client_username = $client->from->username;
            $hear = $client->text;
            $jmlmhs = $this->M_mahasiswa->db->from('mahasiswa')->count_all_results();
            $datamhs = $this->M_mahasiswa->db->get_where('mahasiswa', ['nim' => $hear])->row();

            //nilai
            $ta = $this->M_mahasiswa->db->get('ta')->result();
            $nilai = $this->M_mahasiswa->db->where(['ta' => $hear])->get('nilai')->row();
            $dbnilai = $this->M_mahasiswa->db->where(['hear' => '/nilai'])->get('bot')->row()->hear;
            $dbcari = $this->M_mahasiswa->db->where(['hear' => '/cari'])->get('bot')->row()->hear;

            switch ($hear) {
                case '/nilai':
                    $this->db->insert('bot', ['hear' => $hear]);
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
                    // $reply_to_message = 
                    switch ($dbnilai) {
                        case '/nilai':
                            $cekta = $this->M_mahasiswa->db->get_where('nilai', ['ta' => $hear])->row();
                            if ($hear == $cekta->ta) {
                                $this->db->where('hear', '/nilai')->delete('bot');
                                $response =
                                    '<b>' . $nilai->ta . "</b>\n\n" .
                                    'Makul : <b>' . $nilai->makul . "</b>\n" .
                                    'Nilai : <b>' . $nilai->nilai . "</b>\n";
                            } else {
                                $response = 'masukkan TA';
                            }
                            break;
                    }
                    break;

                    // case '/cari':
                    //     $this->db->insert('bot', ['hear' => $hear]);
                    //     $response = 'masukkan NIM';
                    //     switch ($dbcari) {
                    //         case '/cari':
                    //             if ($hear == $datamhs->nim) {
                    //                 $this->db->where('hear', '/cari')->delete('bot');
                    //                 $response =
                    //                     '<b>' . $datamhs->nim . "</b>\n\n" .
                    //                     'Nama : <b>' . $datamhs->nama . "</b>\n" .
                    //                     'Alamat : <b>' . $datamhs->nama . "</b>\n";
                    //             } else {
                    //                 $response = 'masukkan NIM';
                    //             }
                    //             break;

                    //         default:
                    //             $response = 'hello @' . $client_username;
                    //             break;
                    //     }
                    //     break;

                default:
                    // cek apakah di database ada value cari atau tidak
                    // $dbnilai = $this->M_mahasiswa->db->where(['hear' => '/nilai'])->get('bot')->row();
                    switch ($dbnilai) {
                        case '/nilai':
                            $cekta = $this->M_mahasiswa->db->get_where('nilai', ['ta' => $hear])->row();
                            if ($hear == $cekta->ta) {
                                $this->db->where('hear', '/nilai')->delete('bot');
                                $response =
                                    '<b>' . $nilai->ta . "</b>\n\n" .
                                    'Makul : <b>' . $nilai->makul . "</b>\n" .
                                    'Nilai : <b>' . $nilai->nilai . "</b>\n";
                            } else {
                                $response = 'masukkan TA';
                            }
                            break;

                        default:
                            $response = 'hello @' . $client_username;
                            break;
                    }
                    // switch ($dbcari) {
                    //     case '/cari':
                    //         if ($hear == $datamhs->nim) {
                    //             $this->db->where('hear', '/cari')->delete('bot');
                    //             $response =
                    //                 '<b>' . $datamhs->nim . "</b>\n\n" .
                    //                 'Nama : <b>' . $datamhs->nama . "</b>\n" .
                    //                 'Alamat : <b>' . $datamhs->nama . "</b>\n";
                    //         } else {
                    //             $response = 'masukkan NIM';
                    //         }
                    //         break;

                    //     default:
                    //         $response = 'hello @' . $client_username;
                    //         break;
                    // }
                    // break;
            }

            // switch ($hear) {
            //     case '/cari':
            //         $array = array(
            //             'sesi' => 'cari'
            //         );
            //         $this->session->set_userdata($array);
            //         $response = 'masukkan NIM untuk mencari data mahasiswa';
            //         // $sesi = $this->session->userdata('sesi');
            //         switch ($this->session->userdata('sesi')) {
            //             case 'cari':
            //                 if ($hear == $datamhs->nim) {
            //                     session_destroy();
            //                     $response =
            //                         '<b>' . $datamhs->nim . "</b>\n\n" .
            //                         'Nama : <b>' . $datamhs->nama . "</b>\n" .
            //                         'Alamat : <b>' . $datamhs->alamat . "</b>\n";
            //                 } else {
            //                     $response = 'masukkan NIM untuk mencari data mahasiswa';
            //                 }
            //                 break;

            //             default:
            //                 $response = 'masukkan NIM untuk mencari data mahasiswa';
            //                 // $response = 'hello @' . $client_username  . "\n\n";
            //                 break;
            //                 // $response = 'hello @' . $client_username  . "\n\n";
            //         }
            //         break;
            //     case '/nilai':
            //         $array = array(
            //             'sesi' => 'nilai'
            //         );
            //         $this->session->set_userdata($array);
            //         $response = 'masukkan tahun akademik';
            //         break;

            //     default:
            //         if ($this->session->userdata('sesi')) {
            //             switch ($this->session->userdata('sesi')) {
            //                 case 'cari':
            //                     if ($hear == $datamhs->nim) {
            //                         session_destroy();
            //                         $response =
            //                             '<b>' . $datamhs->nim . "</b>\n\n" .
            //                             'Nama : <b>' . $datamhs->nama . "</b>\n" .
            //                             'Alamat : <b>' . $datamhs->alamat . "</b>\n";
            //                     } else {
            //                         $response = 'masukkan NIM untuk mencari data mahasiswa';
            //                     }
            //                     break;

            //                 default:
            //                     // $response = 'hello @' . $client_username  . "\n\n";
            //                     $response = 'masukkan NIM untuk mencari data mahasiswa';
            //                     break;
            //             }
            //         } else {
            //             $response = 'hello @' . $client_username  . "\n\n";
            //         }
            //         break;
            // }


            // switch ($hear) {
            //     case '/jumlah':
            //         $response = 'jumlah seluruh mahasiswa <b>' . "\n" . $jmlmhs . ' jiwa</b>';
            //         break;
            //     case '/cari':
            //         $response = 'masukkan NIM untuk mencari data mahasiswa';
            //         break;
            //     case $datamhs->nim:
            //         $response =
            //             '<b>' . $datamhs->nim . "</b>\n\n" .
            //             'Nama : <b>' . $datamhs->nama . "</b>\n" .
            //             'Alamat : <b>' . $datamhs->alamat . "</b>\n";
            //         break;

            //     case '/nilai':
            //         $response = 'pilih tahun akademik';
            //         $this->_nilai($token);
            //         break;

            //     default:
            //         $response = 'hello @' . $client_username  . "\n\n";
            //         // .'silahkan gunakan command yang tersedia' . "\n" .
            //         // '====================================' . "\n" .
            //         // 'ketik /jumlah - Jumlah Mahasiswa' . "\n" .
            //         // 'ketik /nilai - Daftar nilai' . "\n" .
            //         // 'ketik <b>NIM(181240000xxx)</b> - Cari Data Mahasiswa' . "\n";
            //         break;
            // }

            $data = [
                'chat_id' => $client_id,
                'text' => $response,
                'parse_mode' => 'html',
                'reply_markup' => $reply_markup,
                // 'reply_to_message' = $reply_to_message
            ];
        }
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

    // private function _nilai($token)
    // {
    //     $keyboard = [
    //         'keyboard' => [
    //             [
    //                 ['text' => '2018-1'],
    //                 ['text' => '2018-2'],
    //                 ['text' => '2019-1'],
    //                 ['text' => '2019-2'],
    //                 ['text' => '2020-1'],
    //                 ['text' => '2020-2']
    //             ]
    //         ],
    //         'resize_keyboard' => true,
    //         'one_time_keyboard' => true
    //     ];
    //     $reply_markup = json_encode($keyboard);
    //     $data = [
    //         'chat_id' => $client_id,
    //         'text' => $response,
    //         'parse_mode' => 'html',
    //         'reply_markup' => $reply_markup
    //     ];
    //     $method = '/sendMessage?';

    //     $url = 'https://api.telegram.org/bot' . $token . $method;

    //     if (!$curld = curl_init()) {
    //         exit;
    //     }
    //     curl_setopt($curld, CURLOPT_POST, true);
    //     curl_setopt($curld, CURLOPT_POSTFIELDS, $data);
    //     curl_setopt($curld, CURLOPT_URL, $url);
    //     curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);

    //     $output = curl_exec($curld);
    //     curl_close($curld);
    //     return $output;
    // }
}
// if ($hear == '/jumlah') {
//     $response = 'jumlah seluruh mahasiswa <but>' . "\n" . $jmlmhs . ' jiwa</button>';
// } elseif ($hear == $datamhs->nim) {
//     $response =
//         '<b>' . $datamhs->nim . "</b>\n\n" .
//         'Nama : <b>' . $datamhs->nama . "</b>\n" .
//         'Alamat : <b>' . $datamhs->alamat . "</b>\n";
//     // } else {
//     //     $response = 'masukkan NIM untuk mencari data mahasiswa';
//     // }
// } elseif ($hear == '/cari') {
//     $response = 'masukkan NIM untuk mencari data mahasiswa';
//     $keyboard = [
//         'keyboard' => [
//             [
//                 ['text' => 181240000833, 'callback_data' => 'someString'],
//                 ['text' => 181240000830, 'callback_data' => 'someString'],
//             ]
//         ],
//         'resize_keyboard' => true,
//         'one_time_keyboard' => true
//     ];
//     $reply_markup = json_encode($keyboard);
//     // $reply_markup = [
//     //     'keyboard' => [
//     //         'KeyboardButton' => [
//     //             'text' => 'asjbajs',
//     //             'text' => 'asjhas'
//     //         ]
//     //     ]
//     // ];
// } elseif ($hear == '/nilai') {
//     $response = 'pilih tahun akademik';
//     $keyboard = [
//         'keyboard' => [
//             [
//                 ['text' => $n->ta, 'callback_data' => 'someString']
//             ]
//         ],
//         'resize_keyboard' => true,
//         'one_time_keyboard' => true
//     ];
//     $reply_markup = json_encode($keyboard);
// } else {
//     $response = 'hello @' . $client_username  . "\n\n" .
//         'silahkan gunakan command yang tersedia' . "\n" .
//         '====================================' . "\n" .
//         'ketik /jumlah - untuk mengetahui jumlah seluruh mahasiswa' . "\n" .
//         'ketik <b>NIM(181240000xxx)</b> - untuk mencari data mahasiswa' . "\n";
// }

// public function index()
// {
//     $token = '1186559289:AAGca1hmtsMZ8kWM2-qwh82SMvVq8Idp5Vo';
//     $update = file_get_contents('php://input');
//     $update = json_decode($update, true);

//     $client = $update['message'];
//     $client_id = $client['from']['id'] ? $client['from']['id'] : null;

//     if ($client_id) {
//         // $client_text = $client->text ? $client->text : 'nothing';
//         // $client_first_name = $client->from->first_name ? $client->from->first_name : 'anonim';
//         // $client_last_name = $client->from->last_name ? $client->from->last_name : 'anonim';
//         // $client_full_name = $client_first_name . '' . $client_last_name;
//         // $reply_message = 'hello ' . $client_full_name;
//         $client_username = $client['from']['username'];
//         $hear = $client['text'];
//         $jmlmhs = $this->M_mahasiswa->db->from('mahasiswa')->count_all_results();
//         $datamhs = $this->M_mahasiswa->db->get_where('mahasiswa', ['nim' => $hear])->row();

//         if ($hear == '/jumlah') {
//             $response = 'jumlah seluruh mahasiswa <b>' . "\n" . $jmlmhs . ' jiwa</b>';
//         } elseif ($hear == $datamhs['nim']) {
//             $response =
//                 '<b>' . $datamhs['nim'] . "</b>\n\n" .
//                 'Nama : <b>' . $datamhs['nama'] . "</b>\n" .
//                 'Alamat : <b>' . $datamhs['alamat'] . "</b>\n";
//             // } else {
//             //     $response = 'masukkan NIM untuk mencari data mahasiswa';
//             // }
//         } else {
//             $response = 'hello @' . $client_username  . "\n\n" .
//                 'silahkan gunakan command yang tersedia' . "\n" .
//                 '====================================' . "\n" .
//                 'ketik /jumlah - untuk mengetahui jumlah seluruh mahasiswa' . "\n" .
//                 'ketik <b>NIM(181240000xxx)</b> - untuk mencari data mahasiswa' . "\n";
//         }

//         $data = [
//             'chat_id' => $client_id,
//             'text' => $response,
//             'parse_mode' => 'html'
//         ];
//     }
//     $method = '/sendMessage?';
//     $this->_responseBot($token, $method, $data);
// }
/* End of file Bot.php */


// keyboard
// $response = 'hello @' . $client_username . "\n\n" .
//     'berikut informasi yang bisa kamu akses:' . "\n" .
//     '1. /jumlah - Informasi Status Mahasiswa' . "\n" .
//     '2. /nilai - Daftar Nilai Mahasiswa' . "\n" .
//     '3. /cari - Cari Data Mahasiswa';
// $keyboard = [
//     'keyboard' => [
//         [
//             ['text' => '/jumlah'],
//             ['text' => '/nilai'],
//             ['text' => '/cari']
//         ]
//     ],
//     'resize_keyboard' => true,
//     'one_time_keyboard' => true
// ];
// $reply_markup = json_encode($keyboard);
