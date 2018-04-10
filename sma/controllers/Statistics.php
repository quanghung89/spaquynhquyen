<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Statistics extends MY_Controller
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
        $this->load->model('Statistics_model');
    }

    public function index()
    {
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
        $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : NULL;


        $this->data['users'] = $this->site->getAllUser();

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('sales')));
        $meta = array('page_title' => lang('sales'), 'bc' => $bc);
        $this->page_construct('statistics/index', $meta, $this->data);

    }

    public function doanhthu()
    {
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
        $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : NULL;


        $this->data['users'] = $this->site->getAllUser();

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('sales')));
        $meta = array('page_title' => lang('sales'), 'bc' => $bc);
        $this->page_construct('statistics/doanhthu', $meta, $this->data);

    }

    public function tongchi()
    {
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $this->data['lists'] = $this->Statistics_model->getList();
        $this->data['total'] = $this->Statistics_model->totalPay();

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('sales')));
        $meta = array('page_title' => lang('sales'), 'bc' => $bc);
        $this->page_construct('statistics/tongchi', $meta, $this->data);

    }

    public function luong()
    {
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
        $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : NULL;

        $this->data['users'] = $this->site->getAllUser();

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('sales')));
        $meta = array('page_title' => lang('sales'), 'bc' => $bc);
        $this->page_construct('statistics/luong', $meta, $this->data);

    }

    public function save_pay()
    {
        if( $this->input->is_ajax_request() ) {
            $pay_id = $this->input->post('pay_id');
            $sotien = $this->input->post("sotien");
            $sotien = str_replace('.', '', $sotien);
            $sotien = str_replace(',', '', $sotien);
            $ngaynop = $this->input->post("ngaynop");
            $ngaynop = strtotime(str_replace('/', '-', $ngaynop));
            if ($pay_id == 0) {
                $data_insert = array(
                    "sma_pay_type" => $this->input->post("pay_type"),
                    "sma_pay_danhmuc_index" => $this->input->post("pay_danhmuc_index"),
                    "sma_pay_pay"    => $sotien,
                    "sma_pay_createtime"    => $ngaynop,
                    "sma_pay_note"    => $this->input->post("noidung"),
                );
                $this->Statistics_model->insertPay($data_insert);
            } else {
                $data_update = array(
                    "sma_pay_type" => $this->input->post("pay_type"),
                    "sma_pay_danhmuc_index" => $this->input->post("pay_danhmuc_index"),
                    "sma_pay_pay"    => $sotien,
                    "sma_pay_createtime"    => $ngaynop,
                    "sma_pay_note"    => $this->input->post("noidung"),
                );
                $this->Statistics_model->updatePay($data_update, $pay_id);
            }
        } else {
            show_404();
        }

    }

    public function del_pay() {
        if( $this->input->is_ajax_request() ) {
            $pay_id = $this->input->post('pay_id');
            $this->Statistics_model->deletePay($pay_id);
            $this->session->set_flashdata("flash_mess", "Delete Success");
            //redirect(base_url() . "statistics/tongchi");
        } else {
            show_404();
        }
    }
}
