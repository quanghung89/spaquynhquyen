<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        if ($this->Customer || $this->Supplier) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->lang->load('customers', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('companies_model');
        $this->digital_upload_path = 'files/';
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size = '1024';
    }

    function index($action = NULL)
    {
        $this->sma->checkPermissions();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('customers')));
        $meta = array('page_title' => lang('customers'), 'bc' => $bc);
        $this->page_construct('customers/index', $meta, $this->data);
    }


    function list_customer($action = NULL)
    {

        $this->sma->checkPermissions('index');
        $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;


        if ($this->input->post('category')) {
            $this->form_validation->set_rules('category', lang("form_action"), 'required');
            if ($this->form_validation->run() == true) {
                if ($this->input->post('category') == 'delete') {

                    $error = false;
                    foreach ($_POST['val'] as $id) {
                        if (!$this->companies_model->deleteBook($id)) {
                            $error = true;
                        }
                    }
                    if ($error) {
                        $this->session->set_flashdata('warning', lang('customers_x_deleted_have_sales'));
                    } else {
                        $this->session->set_flashdata('message', $this->lang->line("Xóa các book lịch thành công"));
                    }
                    redirect($_SERVER["HTTP_REFERER"]);
                } else {
                    $this->session->set_flashdata('error', $this->lang->line("Chưa chọn book lịch"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', validation_errors());
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('customers')));
        $meta = array('page_title' => lang('customers'), 'bc' => $bc);
        $this->page_construct('customers/list_customer', $meta, $this->data);
    }

    function list_book($action = NULL)
    {

        $this->sma->checkPermissions('index');
        $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $user = $this->site->getUser();
        if ($user->warehouse_id == 'all') {
            $user->warehouse_id = '';
        }
        $warehouse_id = $user->warehouse_id;
        if ($this->Owner || $this->Admin) {
            $this->data['warehouses'] = $this->site->getAllWarehouses($user->warehouse_id);
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
        } else {
            $this->data['warehouses'] = NULL;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : NULL;
        }
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('customers')));
        $meta = array('page_title' => lang('customers'), 'bc' => $bc);
        $this->page_construct('customers/list_book', $meta, $this->data);
    }

    function search_customer($action = NULL)
    {
        // $this->sma->checkPermissions('index');

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => 'customers', 'page' => lang('Tìm kiếm khách hàng')));
        $meta = array('page_title' => lang('customers'), 'bc' => $bc);
        $this->page_construct('customers/search_customer', $meta, $this->data);
    }


    function search()
    {
        $data['success'] = true;
        if (!$this->companies_model->getCompanyByPhone($this->input->post('phonenumber'))) {
            $data['msg'] = 'Không có thông tin';
            $data['success'] = false;
        }

        $data['obj'] = $this->companies_model->getCompanyByPhone($this->input->post('phonenumber'));
        echo json_encode($data);
    }

    function getServiceChild()
    {
        $item = '';
        $data['status'] = false;
        $data1 = $this->site->getAllCategories1($this->input->post('id_c'));
        $arrAssign = $this->site->getCategoryAsgin($this->input->post('id_c'));
        foreach ($arrAssign as $key => $value) {
            $arrStaff[] = $value['sma_category_assign_userid'];
        }

        $category->staffs = $arrStaff;
        if ($data1) {


            foreach ($data1 as $key => $value) {
                $item[$key]['id'] = $value->id;
                $item[$key]['text'] = $value->name;
            }

            $data['item'] = $item;
            $data['status'] = true;

        }
        if ($arrStaff) {
            $data['asgin'] = $arrStaff;
            $data['status'] = true;
        }
        echo json_encode($data);
    }

    function getCustomers()
    {
        if ($this->input->get('cgroups')) {
            $cgroups = $this->input->get('cgroups');
        } else {
            $cgroups = NULL;
        }
        // <a class=\"tip\" title='" . $this->lang->line("list_users") . "' href='" . site_url('customers/users/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-users\"></i></a> <a class=\"tip\" title='" . $this->lang->line("add_user") . "' href='" . site_url('customers/add_user/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-plus-circle\"></i></a> 
        $this->sma->checkPermissions('index');
        $this->load->library('datatables');
        $this->datatables
            ->select("1,companies.id as ci, image,customer_group_id, name, phone, companies.id as ci1, SUM(sma_books.sma_books_price) as price, note ")
            ->join('sma_books', 'sma_books.sma_books_customerid = sma_companies.id', 'left')
            ->from("companies")
            ->where('group_name', 'customer')
            ->group_by('sma_books.sma_books_customerid')
            ->add_column("Actions", "<center><a class=\"tip\" title='" . $this->lang->line("edit_customer") . "' href='" . site_url('customers/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_customer") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('customers/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "ci");
        //->unset_column('id');
        if ($cgroups) {
            $this->datatables->where('customer_group_id', $cgroups);
        }
        $this->datatables->unset_column('customer_group_id');
        $arr = json_decode($this->datatables->generate());
        foreach ($arr->aaData as $key => $value) {
            if ($value[0]) {
                $arr->aaData[$key][0] = $key + 1;
            }
            // if($value[5]){
            //     $arr->aaData[$key][5] = '<div style="text-align: center;"><a href="#">Xem lịch sử</a></div>';
            // }


        }

        echo json_encode($arr);
    }


    function getHistoryBook()
    {


        if ($this->input->get('warehouse_id')) {
            $warehouseid = $this->input->get('warehouse_id');
        }

        if ($this->input->get('disable')) {
            $status = $this->input->get('disable');
        }

        if ($this->input->get('start_date')) {

            $start_date = strtotime($this->sma->fsd($this->input->get('start_date')) . ' 00:00:00');
        }

        if ($this->input->get('end_date')) {
            $end_date = strtotime($this->sma->fsd($this->input->get('end_date')) . ' 23:59:59');
        }

        if ($this->input->get('customers')) {
            $customers = $this->input->get('customers');
        }


        if ($this->input->get('user_id')) {
            $user = $this->input->get('user_id');
        }

        if ($status == 'all') {
            $status = NULL;
        }

        if ($warehouseid == 'all') {
            $warehouseid = NULL;
        }

        if ($user == 'all') {
            $user = NULL;
        }


        // "<center><a class=\"tip\" title='" . $this->lang->line("Sửa book lịch") . "' href='" . site_url('customers/book/$1') . "''><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . $this->lang->line("Xóa book lịch") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('customers/delete_book/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>"


        $delete_link = "<a href='#' class='tip po' title='<b>" . $this->lang->line("Xóa book lịch") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete1' id='a__$1' href='" . site_url('customers/delete_book/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('Xóa book lịch') . "</a>";


        $cancel_link = "<a href='#' class='tip po' title='<b>" . $this->lang->line("Hủy book lịch") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-cancel' id='a__$1' href='" . site_url('customers/cancel_book/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-ban\"></i> "
            . lang('Hủy book lịch') . "</a>";
        // }
        // $single_barcode = anchor_popup('products/single_barcode/$1/' . ($warehouse_id ? $warehouse_id : ''), '<i class="fa fa-print"></i> ' . lang('print_barcode'), $this->popup_attributes);
        // $single_label = anchor_popup('products/single_label/$1/' . ($warehouse_id ? $warehouse_id : ''), '<i class="fa fa-print"></i> ' . lang('print_label'), $this->popup_attributes);
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>
            
            <li><a href="' . site_url('customers/book/$1') . '"><i class="fa fa-edit"></i> ' . lang('Sửa book lịch') . '</a></li>
            <li><a  data-toggle="modal" data-target="#myModal" href="' . site_url('customers/complete_book/$1') . '"><i class="fa fa-check-circle"></i> ' . lang('Thanh toán') . '</a></li>';
        // <li><a href="' . site_url('products/add_adjustment/$1/' . ($warehouse_id ? $warehouse_id : '')) . '" data-toggle="modal" data-target="#myModal"><i class="fa fa-filter"></i> '
        //      . lang('adjust_quantity') . '</a></li>
        $action .= '
            
            
                <li class="divider"></li>
                <li>' . $cancel_link . '</li>
                <li>' . $delete_link . '</li>
            </ul>
        </div></div>';


        // <a class=\"tip\" title='" . $this->lang->line("list_users") . "' href='" . site_url('customers/users/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-users\"></i></a> <a class=\"tip\" title='" . $this->lang->line("add_user") . "' href='" . site_url('customers/add_user/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-plus-circle\"></i></a> 
        $this->sma->checkPermissions('index');
        $this->load->library('datatables');
        $this->datatables
            ->select("1,sma_books.sma_books_customerid, sma_books.sma_books_categoryparentname, sma_books.sma_books_categorychildname, sma_warehouses.name as wname, sma_books.sma_books_staffid,
                  GROUP_CONCAT(sma_staffasgin.sma_staffasgin_staffid SEPARATOR ',') as inamestaffass, sma_books.sma_books_starttime,sma_books.sma_books_price,sma_books.sma_books_status")
            ->from("sma_books")
            ->join("sma_companies", "sma_books.sma_books_customerid = sma_companies.id", "left")
            ->join("sma_warehouses", 'sma_books.sma_books_warehouseid = sma_warehouses.id', 'left')
            ->join("sma_staffasgin", 'sma_staffasgin.sma_staffasgin_idbook = sma_books.sma_books_id', 'left')
            ->group_by('sma_books.sma_books_id')
            ->add_column("Actions", $action, "sma_books.sma_books_id");
        //->unset_column('id');

        // $user = $this->site->getUser($this->session->userdata('user_id'));
        if (!$this->Owner) {
            $this->datatables->where('sma_books.sma_books_staffid', $this->session->userdata('user_id'));
        }

        if ($customers) {
            $this->datatables->where('sma_books.sma_books_customerid', $customers);
        }

        if ($this->Owner || $this->Admin) {

            if ($warehouseid) {
                $this->datatables->where('sma_books_warehouseid', $warehouseid);
            }
        }

        if ($status) {
            $this->datatables->where('sma_books_status', $status);
        }

        if ($user) {
            $this->datatables->where('sma_books.sma_books_staffid', $user);
        }

        if ($start_date && $end_date) {
            $this->datatables->where($this->db->dbprefix('books') . '.sma_books_starttime BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        } else {
            if ($start_date) {
                $this->datatables->where($this->db->dbprefix('books') . '.sma_books_starttime >=', $start_date);
            }
            if ($end_date) {
                $this->datatables->where($this->db->dbprefix('books') . '.sma_books_starttime <=', $end_date);
            }
        }
        // $this->datatables->unset_column('customer_group_id');
        $this->datatables->unset_column('sma_books.sma_books_customerid');
        $arr = json_decode($this->datatables->generate());
        foreach ($arr->aaData as $key => $value) {
            $arr->aaData[$key][0] = $key + 1;
            $arr->aaData[$key][6] = $this->sma->ihrld($value[6]);
            // $arr->aaData[$key][9] = $this->sma->ihrld($value[9]);
            // if($value[10]){
            //     $arr->aaData[$key][10] = $this->sma->ihrld($value[10]);
            // }
            $arr->aaData[$key][4] = $this->site->getUser($value[4])->last_name;

            $arrStaffA = explode(',', $value[5]);

            $arrStaffName = '';
            foreach ($arrStaffA as $key1 => $value1) {
                $arrStaffName[] = $this->site->getUser($value1)->last_name;
            }

            $arr->aaData[$key][5] = implode(', ', $arrStaffName);
            $arr->aaData[$key][8] = 'Chưa hoàn thành';
            if ($value[8] == 1) {
                $arr->aaData[$key][8] = 'Hoàn thành';
            }
            if ($value[8] == 2) {
                $arr->aaData[$key][8] = 'Hủy';
            }
        }

        echo json_encode($arr);
    }

    function getBooks()
    {


        if ($this->input->get('warehouse_id')) {
            $warehouseid = $this->input->get('warehouse_id');
        }

        if ($this->input->get('disable')) {
            $status = $this->input->get('disable');
        }

        if ($this->input->get('start_date')) {

            $start_date = strtotime($this->sma->fsd($this->input->get('start_date')) . ' 00:00:00');
        }

        if ($this->input->get('end_date')) {
            $end_date = strtotime($this->sma->fsd($this->input->get('end_date')) . ' 23:59:59');
        }

        if ($status == 'all') {
            $status = NULL;
        }

        if ($warehouseid == 'all') {
            $warehouseid = NULL;
        }

        $user = $this->site->getUser();
        if ($user->warehouse_id == 'all') {
            $user->warehouse_id = '';
        }


        // "<center><a class=\"tip\" title='" . $this->lang->line("Sửa book lịch") . "' href='" . site_url('customers/book/$1') . "''><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . $this->lang->line("Xóa book lịch") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('customers/delete_book/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>"


        $delete_link = "<a href='#' class='tip po' title='<b>" . $this->lang->line("Xóa book lịch") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete1' id='a__$1' href='" . site_url('customers/delete_book/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('Xóa book lịch') . "</a>";


        $cancel_link = "<a href='#' class='tip po' title='<b>" . $this->lang->line("Hủy book lịch") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-cancel' id='a__$1' href='" . site_url('customers/cancel_book/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-ban\"></i> "
            . lang('Hủy book lịch') . "</a>";
        // }
        // $single_barcode = anchor_popup('products/single_barcode/$1/' . ($warehouse_id ? $warehouse_id : ''), '<i class="fa fa-print"></i> ' . lang('print_barcode'), $this->popup_attributes);
        // $single_label = anchor_popup('products/single_label/$1/' . ($warehouse_id ? $warehouse_id : ''), '<i class="fa fa-print"></i> ' . lang('print_label'), $this->popup_attributes);
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>
            
            <li><a href="' . site_url('customers/book/$1') . '"><i class="fa fa-edit"></i> ' . lang('Sửa book lịch') . '</a></li>
            <li><a  data-toggle="modal" data-target="#myModal" href="' . site_url('customers/complete_book/$1') . '"><i class="fa fa-check-circle"></i> ' . lang('Thanh toán') . '</a></li>';
        // <li><a href="' . site_url('products/add_adjustment/$1/' . ($warehouse_id ? $warehouse_id : '')) . '" data-toggle="modal" data-target="#myModal"><i class="fa fa-filter"></i> '
        //      . lang('adjust_quantity') . '</a></li>
        $action .= '
            
            
                <li class="divider"></li>
                <li>' . $cancel_link . '</li>
                <li>' . $delete_link . '</li>
            </ul>
        </div></div>';


        // <a class=\"tip\" title='" . $this->lang->line("list_users") . "' href='" . site_url('customers/users/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-users\"></i></a> <a class=\"tip\" title='" . $this->lang->line("add_user") . "' href='" . site_url('customers/add_user/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-plus-circle\"></i></a> 
        $this->sma->checkPermissions('index');
        $this->load->library('datatables');
        $this->datatables
            ->select("1,sma_books.sma_books_id, sma_books.sma_books_customername,sma_companies.phone, sma_companies.note, sma_books.sma_books_categoryparentname, sma_books.sma_books_categorychildname, sma_warehouses.name as wname, sma_books.sma_books_starttime, sma_books.sma_books_endtime,sma_books.sma_books_endtime1, sma_books.sma_books_staffid,
                  GROUP_CONCAT(sma_staffasgin.sma_staffasgin_staffid SEPARATOR ',') as inamestaffass, sma_books.sma_books_price,sma_books.sma_books_status")
            ->from("sma_books")
            ->join("sma_companies", "sma_books.sma_books_customerid = sma_companies.id", "left")
            ->join("sma_warehouses", 'sma_books.sma_books_warehouseid = sma_warehouses.id', 'left')
            ->join("sma_staffasgin", 'sma_staffasgin.sma_staffasgin_idbook = sma_books.sma_books_id', 'left')
            ->group_by('sma_books.sma_books_id')
            ->add_column("Actions", $action, "sma_books.sma_books_id");
        //->unset_column('id');

        // $user = $this->site->getUser($this->session->userdata('user_id'));
        if (!$this->Owner) {
            $this->db->where('sma_books.sma_books_staffid', $this->session->userdata('user_id'));


        }

        if ($this->Owner || $this->Admin) {

            if ($warehouseid) {
                $this->datatables->where('sma_books_warehouseid', $warehouseid);
            }
        }

        if ($status) {
            $this->datatables->where('sma_books_status', $status);
        }

        if ($start_date && $end_date) {
            $this->db->where($this->db->dbprefix('books') . '.sma_books_starttime BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        } else {
            if ($start_date) {
                $this->db->where($this->db->dbprefix('books') . '.sma_books_starttime >=', $start_date);
            }
            if ($end_date) {
                $this->db->where($this->db->dbprefix('books') . '.sma_books_starttime <=', $end_date);
            }
        }
        // $this->datatables->unset_column('customer_group_id');

        $arr = json_decode($this->datatables->generate());
        foreach ($arr->aaData as $key => $value) {
            $arr->aaData[$key][0] = $key + 1;
            $arr->aaData[$key][8] = $this->sma->ihrld($value[8]);
            $arr->aaData[$key][9] = $value[9];
            if ($value[10]) {
                $arr->aaData[$key][10] = $value[10];
            }
            $arr->aaData[$key][11] = $this->site->getUser($value[11])->last_name;

            $arrStaffA = explode(',', $value[12]);

            $arrStaffName = '';
            foreach ($arrStaffA as $key1 => $value1) {
                $arrStaffName[] = $this->site->getUser($value1)->last_name;
            }

            $arr->aaData[$key][12] = implode(', ', $arrStaffName);
            $arr->aaData[$key][14] = 'Chưa hoàn thành';
            if ($value[14] == 1) {
                $arr->aaData[$key][14] = 'Hoàn thành';
            }
            if ($value[14] == 2) {
                $arr->aaData[$key][14] = 'Hủy';
            }
        }

        echo json_encode($arr);
    }

    function cancel_book($id)
    {
        $inv = $this->site->getBookByID($id);
        if ($inv->sma_books_status != 0) {
            $this->sma->checkPermissionsModelView(false, true, '', 'Không thể thanh toán');
        }
        if ($this->companies_model->cancelBook($id)) {
            if ($this->input->is_ajax_request()) {
                echo lang("Xóa book lịch thành công");
                die();
            }
            $this->session->set_flashdata('message', lang('Hủy book lịch thành công'));
            redirect('customers/list_book');
        }
    }

    function delete_book($id)
    {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }


        if ($this->companies_model->deleteBook($id)) {
            if ($this->input->is_ajax_request()) {
                echo lang("Xóa book lịch thành công");
                die();
            }
            $this->session->set_flashdata('message', lang('Xóa book lịch thành công'));
            redirect('welcome');
        }
    }


    function complete_book($id)
    {
        // $this->sma->checkPermissions(false, true);
        $inv = $this->site->getBookByID($id);

        if ($inv->sma_books_status != 0) {
            $this->sma->checkPermissionsModelView(false, true, '', 'Không thể thanh toán');
        }

        $this->form_validation->set_rules('price', $this->lang->line("số tiền thanh toán thực tế"), 'required');
        $this->form_validation->set_rules('endtime1', $this->lang->line("thời gian kết thúc thực tê"), 'required');
        if ($this->form_validation->run() == true) {
            $data = array(
                'sma_books_endtime1' => strtotime($this->sma->fld($this->input->post('endtime1'))),
                'sma_books_price' => str_replace(",", "", $this->input->post('price')),
                'sma_books_status' => 1,

            );
        }

        if ($this->form_validation->run()) {
            if ($cid = $this->companies_model->completeBook($id, $data)) {
                $this->session->set_flashdata('message', lang('Thanh toán thành công'));
            } else {
                $this->session->set_flashdata('error', lang('Thanh toán thất bại'));
            }
            redirect($_SERVER["HTTP_REFERER"]);

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['inv'] = $inv;
            $this->load->view($this->theme . 'customers/complete_book', $this->data);
        }
    }


    function add()
    {
        $this->sma->checkPermissions(false, true);

        $this->form_validation->set_rules('phone', $this->lang->line("phone"), 'required');
        $this->form_validation->set_rules('name', $this->lang->line("name"), 'required');
        $this->form_validation->set_rules('email', $this->lang->line("email"), 'required');
        if ($this->form_validation->run() == true && $this->input->is_ajax_request()) {
            $cg = $this->site->getCustomerGroupByID($this->Settings->customer_group);
            $data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'group_id' => '3',
                'group_name' => 'customer',
                'customer_group_id' => $this->Settings->customer_group,
                'customer_group_name' => $cg->name,
                'address' => $this->input->post('address'),
                'phone' => $this->input->post('phone'),

            );
            if ($_FILES['image']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('image')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['image'] = $photo;

                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }


            }
        }

        if ($this->form_validation->run() == true && $this->input->is_ajax_request()) {
            if ($cid = $this->companies_model->addCompany($data)) {
                echo json_encode(array(
                    'msg' => 'Thêm thành công',
                    'success' => true,
                ));
            } else {
                echo json_encode(array(
                    'msg' => 'Thêm thất bại',
                    'success' => false,
                ));
            }

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
            $this->load->view($this->theme . 'customers/add', $this->data);
        }
    }

    function check_error()
    {
        $id = $this->input->post('id');
        $val = $this->input->post('valphone');
        $flag = true;


        $all = $this->companies_model->getAllCustomerCompanies();

        $er = '';

        if ($val != '') {
            foreach ($all as $key => $value) {
                $phoneArr = explode(",", $value->phone);

                foreach ($phoneArr as $key1 => $value1) {
                    if ($value1 == $val) {
                        $er = $val;
                        $flag = false;
                    }
                }
            }
        }

        if ($id) {
            $this1 = $this->companies_model->getCompanyByID($id);

            $phoneArr1 = explode(",", $this1->phone);

            foreach ($phoneArr1 as $key1 => $value1) {
                if ($value1 == $val) {
                    $flag = true;
                }
            }

        }


        if ($flag == false) {
            echo json_encode(array(
                    'success' => false,
                    'msg' => 'Số điện thoại đã ' . $er . ' tồn tại',
                    'number' => $er,
                )
            );
        } else {
            echo json_encode(array(
                    'success' => true,
                )
            );
        }

    }

    function edit($id = NULL)
    {
        $this->sma->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $company_details = $this->companies_model->getCompanyByID($id);
        $this->form_validation->set_rules('phone', $this->lang->line("phone"), 'required');
        $this->form_validation->set_rules('name', $this->lang->line("name"), 'required');
        $this->form_validation->set_rules('email', $this->lang->line("email"), 'required');

        if ($this->form_validation->run() == true && $this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $cg = $this->site->getCustomerGroupByID($this->Settings->customer_group);
            $data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'group_id' => '3',
                'group_name' => 'customer',
                'address' => $this->input->post('address'),
                'phone' => $this->input->post('phone'),

            );

            if ($this->input->post('img') == $company_details->image) {
                if ($_FILES['image']['size'] > 0) {
                    $this->load->library('upload');
                    $config['upload_path'] = $this->upload_path;
                    $config['allowed_types'] = $this->digital_file_types;
                    $config['max_size'] = $this->allowed_file_size;
                    $config['overwrite'] = FALSE;
                    $config['encrypt_name'] = TRUE;
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('image')) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('error', $error);
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                    $photo = $this->upload->file_name;
                    $data['image'] = $photo;

                    $this->load->library('image_lib');
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $this->upload_path . $photo;
                    $config['new_image'] = $this->thumbs_path . $photo;
                    $config['maintain_ratio'] = TRUE;
                    $config['width'] = $this->Settings->twidth;
                    $config['height'] = $this->Settings->theight;
                    $this->image_lib->clear();
                    $this->image_lib->initialize($config);
                    if (!$this->image_lib->resize()) {
                        echo $this->image_lib->display_errors();
                    }
                }
            }

        }
        if ($this->form_validation->run() == true && $this->input->is_ajax_request()) {
            if ($this->companies_model->updateCompany($id, $data)) {
                echo json_encode(array(
                    'msg' => 'Sửa thành công',
                    'success' => true,
                ));
            } else {
                echo json_encode(array(
                    'msg' => 'Sửa thất bại',
                    'success' => false,
                ));
            }
        } else {
            $this->data['customer'] = $company_details;
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
            $this->load->view($this->theme . 'customers/edit', $this->data);
        }
    }

    function users($company_id = NULL)
    {
        $this->sma->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }


        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['company'] = $this->companies_model->getCompanyByID($company_id);
        $this->data['users'] = $this->companies_model->getCompanyUsers($company_id);
        $this->load->view($this->theme . 'customers/users', $this->data);

    }

    function book($id = NULL, $change = null)
    {
        $this->sma->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('name_customer', $this->lang->line("Tên khách hàng"), 'required');
        $this->form_validation->set_rules('phone_customer', $this->lang->line("Số điện thoại"), 'required');
        $this->form_validation->set_rules('category_parent', $this->lang->line("Dịch vụ"), 'required');
        $this->form_validation->set_rules('time_work_book', $this->lang->line("Giờ làm"), 'required');


        if ($this->form_validation->run() == true) {
            $data = array(
                'customername' => $this->input->post('name_customer'),
                'customerid' => $this->input->post('id_cus'),
                'note' => $this->input->post('note'),
                'starttime' => strtotime($this->sma->fld($this->input->post('time_work_book'))),
                'warehouseid' => $this->input->post('warehouse_id'),
                'category_parent' => $this->input->post('category_parent'),
                'category_child' => $this->input->post('category_child'),
                'staff_asgin' => $this->input->post('staff_asgin'),
                'phonecustomer' => $this->input->post('phone_customer'),
            );
            $timeparent = $this->site->getCategoryByID($data['category_parent'])->time;
            $total_price = $this->site->getCategoryByID($data['category_parent'])->price;
            $endtime = strtotime('+' . $timeparent . ' minutes', $data['starttime']);
            if ($data['category_child']) {
                $timechild = $this->site->getCategoryByID($data['category_child'])->time;
                $endtime = strtotime('+' . $timechild . ' minutes', $endtime);
                $data['category_child_name'] = $this->site->getCategoryByID($data['category_child'])->name;
                $total_price += $this->site->getCategoryByID($data['category_child'])->price;
            }
            $data['category_parent_name'] = $this->site->getCategoryByID($data['category_parent'])->name;
            $data['sma_books_price'] = $total_price;
            $data['endtime'] = $endtime;
            $id_book1 = NULL;
            if ($id) {
                $id_book1 = $id;
            }
            if ($this->input->post('staff')) {
                $data['staff'] = $this->input->post('staff_id');

            } else {
                $time = array(
                    'start_time' => $data['starttime'],
                    'end_time' => $data['endtime'],
                );
                foreach ($this->site->getAllUserByWarhouseID($data['warehouseid']) as $key => $value) {

                    $detail = $this->sma->getUserWork($value['id'], $time, $id_book1);
                    if ($detail['status'] == 1 && $detail['busy'] == 0) {
                        $data['staff'] = $value['id'];
                        break;
                    }
                }


            }

        }


        if ($this->form_validation->run() == true) {
            if (!$id) {
                if ($this->companies_model->addBook($data)) {
                    $this->session->set_flashdata('message', lang('Thêm book lịch thành công'));
                } else {
                    $this->session->set_flashdata('error', lang('Thêm book lịch thất bại'));
                }
                redirect('customers/list_book/');

            } else {
                if ($change) {
                    if ($this->companies_model->changeBook($id, $data)) {
                        $this->session->set_flashdata('message', lang('Sửa book lịch thành công'));
                    } else {
                        $this->session->set_flashdata('error', lang('Sửa book lịch thất bại'));
                    }
                } else {
                    if ($this->companies_model->updateBook($id, $data)) {
                        $this->session->set_flashdata('message', lang('Sửa book lịch thành công'));
                    } else {
                        $this->session->set_flashdata('error', lang('Sửa book lịch thất bại'));
                    }
                }
                redirect('customers/list_book/');
            }
        } else {

            $cate = $this->site->getAllCategories1();
            foreach ($cate as $key => $value) {
                if ($value->id_parent) {
                    $cate[$key]->name_parent = $this->site->getCategoryByID($value->id_parent)->name;
                }
            }

            $user = $this->site->getUser();

            $this->data['warehouses'] = $this->site->getAllWarehouses(null);

            $this->data['staffs'] = $this->site->getAllUser();
            $this->data['id'] = $id;
            $this->data['categories'] = $cate;
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            if ($id) {
                $inv = $this->site->getBooks($id);
                $inv->staffname = $this->site->getUser($inv->sma_books_staffid)->last_name;
                $staff_asgin = $this->site->getStaffAssByIDBook($inv->sma_books_id);
                foreach ($staff_asgin as $key => $value) {
                    $staff_asgin_id[] = $value['sma_staffasgin_staffid'];
                }
                $inv->staffasgin = $staff_asgin_id;
                $this->data['inv'] = $inv;


            }


            if ($change) {
                $this->data['change'] = $change;
            }
            // $this->data['company'] = $this->companies_model->getCompanyByID($company_id);
            // $this->data['users'] = $this->companies_model->getCompanyUsers($company_id);
            // $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('add_sale')));
            $meta = array('page_title' => lang('Book lịch'), 'bc' => $bc);
            $this->page_construct('customers/book', $meta, $this->data);
        }

    }

    function add_user($company_id = NULL)
    {
        $this->sma->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }
        $company = $this->companies_model->getCompanyByID($company_id);

        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'is_unique[users.email]');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'required|min_length[8]|max_length[20]|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('confirm_password'), 'required');

        if ($this->form_validation->run('companies/add_user') == true) {
            $active = $this->input->post('status');
            $notify = $this->input->post('notify');
            list($username, $domain) = explode("@", $this->input->post('email'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
                'gender' => $this->input->post('gender'),
                'company_id' => $company->id,
                'company' => $company->company,
                'group_id' => 3
            );
            $this->load->library('ion_auth');
        } elseif ($this->input->post('add_user')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customers');
        }

        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $active, $notify)) {
            $this->session->set_flashdata('message', $this->lang->line("user_added"));
            redirect("customers");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['company'] = $company;
            $this->load->view($this->theme . 'customers/add_user', $this->data);
        }
    }

    function history_cate($company_id = NULL)
    {

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['id_customer'] = $company_id;
        $this->data['users'] = $this->site->getAllUserByWarhouseID(null, 1);
        $this->load->view($this->theme . 'customers/history_cate', $this->data);

    }

    function import_csv()
    {
        $this->sma->checkPermissions();
        $this->load->helper('security');
        $this->form_validation->set_rules('csv_file', $this->lang->line("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (DEMO) {
                $this->session->set_flashdata('warning', $this->lang->line("disabled_in_demo"));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            if (isset($_FILES["csv_file"])) /* if($_FILES['userfile']['size'] > 0) */ {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/csv/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = '2000';
                $config['overwrite'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('csv_file')) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("customers");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen("assets/uploads/csv/" . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 5001, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);

                $keys = array('company', 'name', 'email', 'phone', 'address', 'city', 'state', 'postal_code', 'country', 'vat_no', 'cf1', 'cf2', 'cf3', 'cf4', 'cf5', 'cf6');

                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }
                $rw = 2;
                foreach ($final as $csv) {
                    if ($this->companies_model->getCompanyByEmail($csv['email'])) {
                        $this->session->set_flashdata('error', $this->lang->line("check_customer_email") . " (" . $csv['email'] . "). " . $this->lang->line("customer_already_exist") . " (" . $this->lang->line("line_no") . " " . $rw . ")");
                        redirect("customers");
                    }
                    $rw++;
                }
                foreach ($final as $record) {
                    $record['group_id'] = 3;
                    $record['group_name'] = 'customer';
                    $record['customer_group_id'] = 1;
                    $record['customer_group_name'] = 'General';
                    $data[] = $record;
                }
                //$this->sma->print_arrays($data);
            }

        } elseif ($this->input->post('import')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customers');
        }

        if ($this->form_validation->run() == true && !empty($data)) {
            if ($this->companies_model->addCompanies($data)) {
                $this->session->set_flashdata('message', $this->lang->line("customers_added"));
                redirect('customers');
            }
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'customers/import', $this->data);
        }
    }

    function check_customer()
    {
        $phone = $this->input->post('phone');
        $inv = $this->site->getCompanyByPhone($phone);
        if (!$inv->note) {
            $inv->note = '';
        }
        echo json_encode($inv);
    }

    function delete($id = NULL)
    {
        $this->sma->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->input->get('id') == 1) {
            $this->session->set_flashdata('error', lang('customer_x_deleted'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }

        if ($this->companies_model->deleteCustomer($id)) {
            echo $this->lang->line("customer_deleted");
        } else {
            $this->session->set_flashdata('warning', lang('customer_x_deleted_have_sales'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }
    }

    function suggestions($term = NULL, $limit = NULL)
    {
        $this->sma->checkPermissions('index');
        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        if (strlen($term) < 1) {
            return FALSE;
        }
        $limit = $this->input->get('limit', TRUE);
        $rows['results'] = $this->companies_model->getCustomerSuggestions($term, $limit);
        echo json_encode($rows);
    }

    function getCustomer($id = NULL)
    {
        $this->sma->checkPermissions('index');
        $row = $this->companies_model->getCompanyByID($id);
        echo json_encode(array(array('id' => $row->id, 'text' => ($row->company != '-' ? $row->company : $row->name))));
    }


    function getStaff()
    {

        if ($this->input->post('time')) {
            $time1 = array(
                'start_time' => strtotime($this->sma->fld($this->input->post('time'))),
            );

        }
        $id_book = NULL;
        if ($this->input->post('id_book')) {
            $id_book = $this->input->post('id_book');
            $data_book = $this->site->getBookByID($id_book);
        }
        $user = $this->site->getAllUserByWarhouseID($this->input->post('warehouse_id'), null, null);
        foreach ($user as $key => $value) {


            if ($data_book->sma_books_starttime <= strtotime($this->sma->fld($this->input->post('time'))) && strtotime($this->sma->fld($this->input->post('time'))) <= $data_book->sma_books_endtime) {
                $detail = $this->sma->getUserWork($value['id'], $time1, $id_book);
            } else {
                $detail = $this->sma->getUserWork($value['id'], $time1, null);
            }

            if ($detail['status'] == 1) {
                $status = 'Hoạt động';
                $total = 1;
                $detail['class'] = '';
                if ($detail['busy'] == 1) {
                    $busy = 'Bận';
                    $total = 2;
                } else {
                    $busy = null;
                }
            } else {
                $busy = null;
                $status = 'Nghỉ';
                $total = 3;
                if ($detail['class'] != 'Nghỉ') {
                    $total = 4;
                } else {
                    $class1 = 'Nghỉ';
                    $total = 3;
                }
            }


            $user[$key]['status'] = $detail['status'];
            $user[$key]['busy'] = $detail['busy'];
            $user[$key]['class'] = $detail['class'];
            $user[$key]['status_active'] = $total;

        }
        echo json_encode($user);
    }

    function get_award_points($id = NULL)
    {
        $this->sma->checkPermissions('index');
        $row = $this->companies_model->getCompanyByID($id);
        echo json_encode(array('ca_points' => $row->award_points));
    }


    function customer_actions_1()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');
        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    $error = false;
                    foreach ($_POST['val'] as $id) {
                        if (!$this->companies_model->deleteCustomer($id)) {
                            $error = true;
                        }
                    }
                    if ($error) {
                        $this->session->set_flashdata('warning', lang('customers_x_deleted_have_sales'));
                    } else {
                        $this->session->set_flashdata('message', $this->lang->line("customers_deleted"));
                    }
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('customer'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('company'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('email'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('phone'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('address'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('city'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('state'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('postal_code'));
                    $this->excel->getActiveSheet()->SetCellValue('I1', lang('country'));
                    $this->excel->getActiveSheet()->SetCellValue('J1', lang('vat_no'));
                    $this->excel->getActiveSheet()->SetCellValue('K1', lang('ccf1'));
                    $this->excel->getActiveSheet()->SetCellValue('L1', lang('ccf2'));
                    $this->excel->getActiveSheet()->SetCellValue('M1', lang('ccf3'));
                    $this->excel->getActiveSheet()->SetCellValue('N1', lang('ccf4'));
                    $this->excel->getActiveSheet()->SetCellValue('O1', lang('ccf5'));
                    $this->excel->getActiveSheet()->SetCellValue('P1', lang('ccf6'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $customer = $this->site->getCompanyByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $customer->company);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $customer->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $customer->email);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $customer->phone);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $customer->address);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $customer->city);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $customer->state);
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $customer->postal_code);
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $customer->country);
                        $this->excel->getActiveSheet()->SetCellValue('J' . $row, $customer->vat_no);
                        $this->excel->getActiveSheet()->SetCellValue('K' . $row, $customer->cf1);
                        $this->excel->getActiveSheet()->SetCellValue('L' . $row, $customer->cf2);
                        $this->excel->getActiveSheet()->SetCellValue('M' . $row, $customer->cf3);
                        $this->excel->getActiveSheet()->SetCellValue('N' . $row, $customer->cf4);
                        $this->excel->getActiveSheet()->SetCellValue('O' . $row, $customer->cf5);
                        $this->excel->getActiveSheet()->SetCellValue('P' . $row, $customer->cf6);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'customers_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', $this->lang->line("no_customer_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }


    function customer_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');
        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    $error = false;
                    foreach ($_POST['val'] as $id) {
                        if (!$this->companies_model->deleteCustomer($id)) {
                            $error = true;
                        }
                    }
                    if ($error) {
                        $this->session->set_flashdata('warning', lang('customers_x_deleted_have_sales'));
                    } else {
                        $this->session->set_flashdata('message', $this->lang->line("customers_deleted"));
                    }
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('customer'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('company'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('email'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('phone'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('address'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('city'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('state'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('postal_code'));
                    $this->excel->getActiveSheet()->SetCellValue('I1', lang('country'));
                    $this->excel->getActiveSheet()->SetCellValue('J1', lang('vat_no'));
                    $this->excel->getActiveSheet()->SetCellValue('K1', lang('ccf1'));
                    $this->excel->getActiveSheet()->SetCellValue('L1', lang('ccf2'));
                    $this->excel->getActiveSheet()->SetCellValue('M1', lang('ccf3'));
                    $this->excel->getActiveSheet()->SetCellValue('N1', lang('ccf4'));
                    $this->excel->getActiveSheet()->SetCellValue('O1', lang('ccf5'));
                    $this->excel->getActiveSheet()->SetCellValue('P1', lang('ccf6'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $customer = $this->site->getCompanyByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $customer->company);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $customer->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $customer->email);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $customer->phone);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $customer->address);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $customer->city);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $customer->state);
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $customer->postal_code);
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $customer->country);
                        $this->excel->getActiveSheet()->SetCellValue('J' . $row, $customer->vat_no);
                        $this->excel->getActiveSheet()->SetCellValue('K' . $row, $customer->cf1);
                        $this->excel->getActiveSheet()->SetCellValue('L' . $row, $customer->cf2);
                        $this->excel->getActiveSheet()->SetCellValue('M' . $row, $customer->cf3);
                        $this->excel->getActiveSheet()->SetCellValue('N' . $row, $customer->cf4);
                        $this->excel->getActiveSheet()->SetCellValue('O' . $row, $customer->cf5);
                        $this->excel->getActiveSheet()->SetCellValue('P' . $row, $customer->cf6);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'customers_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', $this->lang->line("no_customer_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

}
