<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_mahasiswa');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }

    // List all your items
    public function index($id = false, $page = false)
    {
        $k = $this->input->get('k');
        if ($id) {
            $mhs = $this->M_mahasiswa->getMahasiswaById($id);
            $data = [
                'title' => $mhs->nim . ' | MacaksCI3',
                'mhs' => $mhs
            ];
            $this->load->view('/layout/header', $data);
            $this->load->view('detail', $data);
            $this->load->view('/layout/footer');
        } else {
            $paginate = [
                'base_url' => base_url('data'),
                'total_rows' => $this->db->get('mahasiswa')->num_rows(),
                'per_page' => 8,

                'full_tag_open' => '<nav aria-label="..."><ul class="pagination justify-content-end">',
                'full_tag_close' => '</nav></ul>',

                'first_link' => 'First',
                'first_tag_open' => '<li class="page-item">',
                'first_tag_close' => '</li>',

                'last_link' => 'Last',
                'last_tag_open' => '<li class="page-item">',
                'last_tag_close' => '</li>',

                'next_link' => '&raquo',
                'next_tag_open' => '<li class="page-item">',
                'next_tag_close' => '</li>',

                'prev_link' => '&laquo',
                'prev_tag_open' => '<li class="page-item">',
                'prev_tag_close' => '</li>',

                'cur_tag_open' => '<li class="page-item active" aria-current="page"><span class="page-link">',
                'cur_tag_close' => '</span></li>',

                'num_tag_open' => '<li class="page-item><a href="" class="page-link">',
                'num_tag_close' => '</li>',

                'attributes' => array('class' => 'page-link')
            ];
            // $mhs = $this->M_mahasiswa->getMahasiswa(8, $this->uri->segment(2), $k);
            // if ($k) {
            //     $this->session->set_userdata('k');
            //     $this->pagination->initialize($paginate);
            //     $data = [
            //         'title' => 'Pencarian | MacaksCI3',
            //         'k' => $this->session->userdata('k'),
            //         'mahasiswa' => $this->M_mahasiswa->getMahasiswa(
            //             8,
            //             $this->uri->segment(2),
            //             $this->session->userdata('k')
            //         )
            //     ];
            // }
            $this->pagination->initialize($paginate);
            $data = [
                'title' => 'Home | MacaksCI3',
                'mahasiswa' => $this->M_mahasiswa->getMahasiswa(
                    8,
                    $this->uri->segment(2)
                )
            ];
            $this->load->view('/layout/header', $data);
            $this->load->view('index', $data);
            $this->load->view('/layout/footer');
        }
    }

    public function data()
    {
        redirect('/');
    }

    // Add a new item
    public function add()
    {
        $data = [
            'title' => 'Tambah | MacaksCI3'
        ];
        $this->form_validation->set_rules([
            'nim' => [
                'field' => 'nim',
                'label' => 'Nim',
                'rules' => 'required|max_length[12]|numeric'
            ],
            'nama' => [
                'field' => 'nama',
                'label' => 'Nama',
                'rules' => 'required'
            ],
            'email' => [
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|valid_email'
            ],
            'alamat' => [
                'field' => 'alamat',
                'label' => 'Alamat',
                'rules' => 'required'
            ]
        ]);
        if ($this->form_validation->run()) {
            $this->M_mahasiswa->addMahasiswa();
            $this->session->set_flashdata('success', '<script>alert("Data mahasiswa berhasil ditambahkan!");</script>');
            redirect('/', 'refresh');
        } else {
            $this->load->view('/layout/header', $data);
            $this->load->view('add', $data);
            $this->load->view('/layout/footer');
        }
    }

    //Update one item
    public function update($id)
    {
        $data = [
            'title' => 'Update | MacaksCI3',
            'mhs' => $this->M_mahasiswa->getMahasiswaById($id)
        ];
        // var_dump($data['mhs']);
        // die();
        $this->form_validation->set_rules([
            'nim' => [
                'field' => 'nim',
                'label' => 'Nim',
                'rules' => 'required|max_length[12]|numeric'
            ],
            'nama' => [
                'field' => 'nama',
                'label' => 'Nama',
                'rules' => 'required'
            ],
            'email' => [
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|valid_email'
            ],
            'alamat' => [
                'field' => 'alamat',
                'label' => 'Alamat',
                'rules' => 'required'
            ]
        ]);
        if ($this->form_validation->run()) {
            $this->M_mahasiswa->updateMahasiswa($id);
            $this->session->set_flashdata('success', '<script>alert("Data mahasiswa berhasil diubah!");</script>');
            redirect('/');
        } else {
            $this->load->view('/layout/header', $data);
            $this->load->view('update', $data);
            $this->load->view('/layout/footer');
        }
    }

    //Delete one item
    public function delete($id)
    {
        $this->M_mahasiswa->deleteMahasiswa($id);
        $this->session->set_flashdata('success', '<script>alert("Data mahasiswa berhasil dihapus!");</script>');
        redirect('/');
    }

    public function cari()
    {
        $this->load->helper('text');
        $k = $this->input->get('k');

        $paginate = [
            'base_url' => base_url('cari'),
            'total_rows' => $this->db->like('nama', $k)->or_like('alamat', $k)->or_like('nim', $k)->from('mahasiswa')->count_all_results(),
            'per_page' => 8,

            'full_tag_open' => '<nav aria-label="..."><ul class="pagination justify-content-end">',
            'full_tag_close' => '</nav></ul>',

            'first_link' => 'First',
            'first_tag_open' => '<li class="page-item">',
            'first_tag_close' => '</li>',

            'last_link' => 'Last',
            'last_tag_open' => '<li class="page-item">',
            'last_tag_close' => '</li>',

            'next_link' => '&raquo',
            'next_tag_open' => '<li class="page-item">',
            'next_tag_close' => '</li>',

            'prev_link' => '&laquo',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tag_close' => '</li>',

            'cur_tag_open' => '<li class="page-item active" aria-current="page"><span class="page-link">',
            'cur_tag_close' => '</span></li>',

            'num_tag_open' => '<li class="page-item><a href="" class="page-link">',
            'num_tag_close' => '</li>',

            'attributes' => array('class' => 'page-link')
        ];
        $this->pagination->initialize($paginate);
        $mhs = $this->M_mahasiswa->getMahasiswa(8, $this->uri->segment(2), $k);
        $data = [
            'title' => 'Pencarian | MacaksCI3',
            'k' => $k,
            // 'mahasiswa' => $this->db->like('nama', $k)->or_like('alamat', $k)->or_like('nim', $k)->get('mahasiswa')->result(),
            'mahasiswa' => $mhs
        ];
        $this->load->view('/layout/header', $data);
        $this->load->view('index', $data);
        $this->load->view('/layout/footer');
    }

    public function bot()
    {
        $token = '1186559289:AAGca1hmtsMZ8kWM2-qwh82SMvVq8Idp5Vo';
        $update = file_get_contents('php://input');
        $update = json_decode($update);

        $client = $update->message;
        $client_id = $client->from->id ? $client->from->id : null;

        // print_r($this->M_mahasiswa->db->get_where('mahasiswa', ['nim' => $client->text])->row());
        // die;
        // var_dump($update);
        // $hear = $client->text;
        // $nim = $this->M_mahasiswa->db->get_where('mahasiswa', ['nim' => $hear])->row();
        // print_r($nim->nim);
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

            if ($hear == '/jumlah') {
                $response = 'jumlah seluruh mahasiswa <b>' . "\n" . $jmlmhs . ' jiwa</b>';
            } elseif ($hear == $datamhs->nim) {
                $response =
                    '<b>' . $datamhs->nim . "</b>\n\n" .
                    'Nama : <b>' . $datamhs->nama . "</b>\n" .
                    'Alamat : <b>' . $datamhs->alamat . "</b>\n";
                // } else {
                //     $response = 'masukkan NIM untuk mencari data mahasiswa';
                // }
            } else {
                $response = 'hello @' . $client_username  . "\n\n" .
                    'silahkan gunakan command yang tersedia' . "\n" .
                    '======================================' . "\n" .
                    'ketik /jumlah - untuk mengetahui jumlah seluruh mahasiswa' . "\n" .
                    'ketik <b>NIM(181240000xxx)</b> - untuk mencari data mahasiswa' . "\n";
            }

            $data = [
                'chat_id' => 821856771,
                // 'text' => 'hy @' . $client_username
                'text' => $response,
                'parse_mode' => 'html'
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

    public function coba()
    {
        // print_r($this->M_mahasiswa->db->get_where('mahasiswa', ['nim' => $nim])->row());
    }

    // $nim = 1812400008;
    // var_dump($this->db->get_where('mahasiswa', ['nim' => $nim])->result());
    // die();
    // $token = '1186559289:AAGca1hmtsMZ8kWM2-qwh82SMvVq8Idp5Vo';
    // $getUpdates = file_get_contents('https://api.telegram.org/bot' . $token . '/getUpdates');
    // $chatId = '821856771';
    // // $chat = $this->input->post('pesan');
    // // var_dump($getUpdates);
    // // var_dump(json_decode($getUpdates));
    // // die();

    // $result = json_decode($getUpdates)->result;
    // $text = end($result)->message->text;
    // // d($text);

    // // var_dump($text);
    // // die();

    // if ($text == '/jumlah') {
    //     // redirect('/mahasiswa/responseBot', 'refresh');
    //     $jmlmhs = $this->M_mahasiswa->db->from('mahasiswa')->count_all_results();
    //     $chat = 'jumlah mahasiswa adalah ' . $jmlmhs;
    // } elseif ($text == '/anjay') {
    //     $chat = 'kamu mengetikkan anjay';
    // } elseif ($text == '/carimahasiswa') {
    //     $chat = 'masukkan nim anda';
    //     if ($text == $this->db->where('mahasiswa', ['nim' => $text])) {
    //         echo 'alamat <b>';
    //     }
    //     $carialamat = $this->M_mahasiswa->db->from('mahasiswa')->count_all_results();
    // }

    // $url = 'https://api.telegram.org/bot' . $token . '/sendMessage?chat_id=' . $chatId . '&text=' . $chat;
    // $init = curl_init($url);
    // curl_exec($init);
    // curl_close($init);


    // redirect('/mahasiswa/bot','refresh');

    // $getUpdates = file_get_contents('https://api.telegram.org/bot' . $token . '/getUpdates');
    // $chatId = '821856771';

    // public function bot()
    // {
    //     $data = [
    //         'title' => 'bot'
    //     ];
    //     $this->load->view('/layout/header', $data);
    //     $this->load->view('bot', $data);
    //     $this->load->view('/layout/footer');
    // }

    // public function macakbot()
    // {
    //     $token = '1186559289:AAGca1hmtsMZ8kWM2-qwh82SMvVq8Idp5Vo';
    //     $path = "https://api.telegram.org/bot" . $token;

    //     $update = json_decode(file_get_contents("php://input"), TRUE);

    //     $chatId = $update["message"]["chat"]["id"];
    //     $message = $update["message"]["text"];

    //     if (strpos($message, "/weather") === 0) {
    //         $location = substr($message, 9);
    //         $weather = json_decode(file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=" . $location . "&appid=mytoken"), TRUE)["weather"][0]["main"];
    //         file_get_contents($path . "/sendmessage?chat_id=" . $chatId . "&text=Here's the weather in " . $location . ": " . $weather);
    //     }
    // }
}

/* End of file Mahasiswa.php */
