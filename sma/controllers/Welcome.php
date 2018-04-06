<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $this->load->library('form_validation');
        $this->load->model('db_model');
    }

    public function index()
    {
        if ($this->Settings->version == '2.3') {
            $this->session->set_flashdata('warning', 'Please complete your update by synchronizing your database.');
            redirect('sync');
        }
        
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['sales'] = $this->db_model->getLatestSales();
        $this->data['quotes'] = $this->db_model->getLastestQuotes();
        $this->data['purchases'] = $this->db_model->getLatestPurchases();
        $this->data['transfers'] = $this->db_model->getLatestTransfers();
        $this->data['customers'] = $this->db_model->getLatestCustomers();
        $this->data['suppliers'] = $this->db_model->getLatestSuppliers();
        $this->data['chatData'] = $this->db_model->getChartData();
        $this->data['stock'] = $this->db_model->getStockValue();
        $this->data['bs'] = $this->db_model->getBestSeller();
        $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
        $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
        $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
        $bc = array(array('link' => '#', 'page' => lang('dashboard')));
        $meta = array('page_title' => lang('dashboard'), 'bc' => $bc);
        $this->page_construct('dashboard', $meta, $this->data);

    }

    function promotions()
    {
        $this->load->view($this->theme . 'promotions', $this->data);
    }

    function readBookNoti(){
        $id_noti  = $this->input->post('id_noti');
        $this->site->updateReadNotie($id_noti);
        echo json_encode($id_noti);
    }

    function getBookNoti(){
        if($this->input->post('user_id')){
            $user_id = $this->input->post('user_id');
        }

        $data = $this->site->getNoticeByUserID($user_id,'book');
        echo json_encode($data);

    }

    function getBookNotiCancel(){
        if($this->input->post('warehouse_id')){
            $warehouse_id = $this->input->post('warehouse_id');
        }
        if($warehouse_id == 'all'){
            $warehouse_id = NULL;
        }
        $data = $this->site->getNoticeCancel($warehouse_id);
        foreach ($data as $key => $value) {
            $data[$key]['sma_books_starttime'] = $this->sma->ihrld($value['sma_books_starttime']);
        }
        echo json_encode($data);
    }

    function readBookNotiCancel(){
        $id_noti  = $this->input->post('id_noti');
        $this->site->updateReadNotieCancel($id_noti);
        echo json_encode($id_noti);
    }

    function getProductNoti(){
        if($this->input->post('warehouse_id')){
            $warehouse_id = $this->input->post('warehouse_id');
            if($warehouse_id == "all"){
                $warehouse_id = NULL;
            }
        }
        $data['products'] = $this->site->getAlertQuantityProduct($warehouse_id,3);
        $data['count'] = 0;
        if($data['products']){
            $data['count'] = count($this->site->getAlertQuantityProduct($warehouse_id));
        }
        
        $data['allproducts'] = $this->site->getAlertQuantityProduct($warehouse_id);
        echo json_encode($data);
        
    }

    function image_upload()
    {
        if (DEMO) {
            $error = array('error' => $this->lang->line('disabled_in_demo'));
            echo json_encode($error);
            exit;
        }
        $this->security->csrf_verify();
        if (isset($_FILES['file'])) {
            $this->load->library('upload');
            $config['upload_path'] = 'assets/uploads/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = '500';
            $config['max_width'] = $this->Settings->iwidth;
            $config['max_height'] = $this->Settings->iheight;
            $config['encrypt_name'] = TRUE;
            $config['overwrite'] = FALSE;
            $config['max_filename'] = 25;
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('file')) {
                $error = $this->upload->display_errors();
                $error = array('error' => $error);
                echo json_encode($error);
                exit;
            }
            $photo = $this->upload->file_name;
            $array = array(
                'filelink' => base_url() . 'assets/uploads/images/' . $photo
            );
            echo stripslashes(json_encode($array));
            exit;

        } else {
            $error = array('error' => 'No file selected to upload!');
            echo json_encode($error);
            exit;
        }
    }

    function set_data($ud, $value)
    {
        $this->session->set_userdata($ud, $value);
        echo true;
    }

    function hideNotification($id = NULL)
    {
        $this->session->set_userdata('hidden' . $id, 1);
        echo true;
    }

    function language($lang = false)
    {
        if ($this->input->get('lang')) {
            $lang = $this->input->get('lang');
        }
        //$this->load->helper('cookie');
        $folder = 'sma/language/';
        $languagefiles = scandir($folder);
        if (in_array($lang, $languagefiles)) {
            $cookie = array(
                'name' => 'language',
                'value' => $lang,
                'expire' => '31536000',
                'prefix' => 'sma_',
                'secure' => false
            );

            $this->input->set_cookie($cookie);
        }
        redirect($_SERVER["HTTP_REFERER"]);
    }

    function download($file)
    {
        $this->load->helper('download');
        force_download('./files/'.$file, NULL);
        exit();
    }

    function getOutTimeBook(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $user_id = $this->input->post('user_id');
        $nowtime = strtotime(date('Y-m-d H:i:s'));
        $data_book = $this->site->getBooks1($user_id);
        foreach ($data_book as $key => $value) {
            if($value['sma_books_endtime'] < $nowtime){
                $data[] = $value;
            }
        }

        echo json_encode($data);

    }

}
