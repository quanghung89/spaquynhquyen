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

        $today = date('Y-m-d');
        $date_from = $first_m = date('01/m/Y', strtotime($today));
        $date_to = $last_m = date('t/m/Y', strtotime($today));

        if (count($_POST) > 0) {
            $date_from = $this->input->post('date_from');
            $date_to = $this->input->post('date_to');
        }

        $this->data['lists'] = $this->Statistics_model->getList($date_from, $date_to);
        $this->data['total'] = $this->Statistics_model->totalPay($date_from, $date_to);
        $this->data['date_from'] = $date_from;
        $this->data['date_to'] = $date_to;

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

    public function save_pay2()
    {
        if ($this->input->is_ajax_request()) {
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
                    "sma_pay_pay" => $sotien,
                    "sma_pay_createtime" => $ngaynop,
                    "sma_pay_note" => $this->input->post("noidung"),
                );
                $this->Statistics_model->insertPay($data_insert);
            } else {
                $data_update = array(
                    "sma_pay_type" => $this->input->post("pay_type"),
                    "sma_pay_danhmuc_index" => $this->input->post("pay_danhmuc_index"),
                    "sma_pay_pay" => $sotien,
                    "sma_pay_createtime" => $ngaynop,
                    "sma_pay_note" => $this->input->post("noidung"),
                );
                $this->Statistics_model->updatePay($data_update, $pay_id);
            }
        } else {
            show_404();
        }

    }

    public function save_pay()
    {
        if ($this->input->is_ajax_request()) {
            //insert
            $pay_types = $this->input->post("sma_pay_type");
            $pay_danhmuc_index = $this->input->post("sma_pay_danhmuc_index");
            $sotien = $this->input->post("sotien");
            $ngaynop = $this->input->post("ngaynop");
            $noidung = $this->input->post("noidung");
            if (count($pay_types) > 0) {
                $p = 0;
                foreach ($pay_types as $pay_type) {
                    $data_insert = array(
                        "sma_pay_type" => $pay_type[$p],
                        "sma_pay_danhmuc_index" => $pay_danhmuc_index[$p],
                        "sma_pay_pay" => str_replace(',', '', str_replace('.', '', $sotien[$p])),
                        "sma_pay_createtime" => strtotime(str_replace('/', '-', $ngaynop[$p])),
                        "sma_pay_note" => $noidung[$p],
                    );
                    $this->Statistics_model->insertPay($data_insert);

                    $p++;
                }
            }

            //update
            $pay_ids = $this->input->post("sma_pay_id_edit");
            $pay_danhmuc_index_edit = $this->input->post("sma_pay_danhmuc_index_edit");
            $sotien_edit = $this->input->post("sotien_edit");
            $ngaynop_edit = $this->input->post("ngaynop_edit");
            $noidung_edit = $this->input->post("noidung_edit");
            if (count($pay_ids) > 0) {
                $q = 0;
                foreach ($pay_ids as $pay_id) {
                    $data_update = array(
                        "sma_pay_danhmuc_index" => $pay_danhmuc_index_edit[$q],
                        "sma_pay_pay" => str_replace(',', '', str_replace('.', '', $sotien_edit[$q])),
                        "sma_pay_createtime" => strtotime(str_replace('/', '-', $ngaynop_edit[$q])),
                        "sma_pay_note" => $noidung_edit[$q],
                    );
                    $this->Statistics_model->updatePay($data_update, $pay_id);

                    $q++;
                }
            }
        } else {
            show_404();
        }
    }

    public function del_pay()
    {
        if ($this->input->is_ajax_request()) {
            $pay_id = $this->input->post('pay_id');
            $this->Statistics_model->deletePay($pay_id);
            $this->session->set_flashdata("flash_mess", "Delete Success");
            //redirect(base_url() . "statistics/tongchi");
        } else {
            show_404();
        }
    }
}
