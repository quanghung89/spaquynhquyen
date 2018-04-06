<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->lang->load('auth', $this->Settings->language);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->load->model('auth_model');
        $this->load->library('ion_auth');
    }

    function index()
    {

        if (!$this->loggedIn) {
            redirect('login');
        } else {
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }




   

    function list_user()
    {
        if (!$this->loggedIn) {
            redirect('login');
        }
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $user = $this->site->getUserByID($this->session->userdata('user_id'));
        if($user){
            $this->data['warehouse'] = $this->site->getWarehouseByID($user->warehouse_id);
        }

        $users = $this->auth_model->where("warehouse_id", $user->warehouse_id)->users()->result();
        $this->data['users'] = $users;

        $this->data['warehouses'] = $this->site->getAllWarehouses(null);
        $this->data['groups'] = $this->site->getAllGroup();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('users')));
        $meta = array('page_title' => lang('users'), 'bc' => $bc);
        $this->page_construct('auth/list_user', $meta, $this->data);
        
    }


     function check_user()
    {
       
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        
        $user = $this->site->getUserByID($this->session->userdata('user_id'));

        if($user){
            $this->data['warehouse'] = $this->site->getWarehouseByID($user->warehouse_id);
        }

        $this->data['warehouses'] = $this->site->getAllWarehouses(null);
        $this->data['groups'] = $this->site->getAllGroup();

        $users = $this->auth_model->where("warehouse_id", $user->warehouse_id)->users()->result();

        $startDate = $this->input->post('start_date');
        $endDate = $this->input->post('end_date');

        $nowdate = new DateTime();
        $nowdate = $nowdate->getTimestamp();
        $isNowdate = false;

        if(!isset($startDate)) {
            $isNowdate = true;
            $startDate = $nowdate;
        }

        if(!isset($endDate)) {
            $endDate = $nowdate;
        }

        $startDate = strtotime($this->sma->fsd($startDate).' 00:00:00');
        $endDate = strtotime($this->sma->fsd($endDate).' 23:59:59');

        $userOff = $this->site->getCurrentUsersDayOf(null);
        $userWork = $this->site->getCurrentUsersWork($user->warehouse_id, $startDate, $endDate);

        foreach ($users as $us) {
            $us->isOff = false;
            $us->isNowdate = $isNowdate;
            foreach ($userOff as $off) {
                if($us->id == $off['sma_timeout_userid']){
                    $us->isOff = true;
                }
            }

            $us->isWorking = false;
            foreach ($userWork as $work) {
                if($us->id == $work['sma_books_staffid']){
                    $us->isWorking = true;

                    $us->customerName = $work['sma_books_customername'];
                    $us->dateWorkStart = date("d/m/Y H:i:s", $work['sma_books_starttime']);
                    $us->serviceName = $work['sma_books_categoryparentname'];
                }
            }
        }
        $this->data['users'] = $users;

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('users')));
        $meta = array('page_title' => lang('users'), 'bc' => $bc);
        $this->page_construct('auth/check_user', $meta, $this->data);
        
    }

    function users()
    {
        if (!$this->loggedIn) {
            redirect('login');
        }
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('users')));
        $meta = array('page_title' => lang('users'), 'bc' => $bc);
        $this->page_construct('auth/index', $meta, $this->data);
    }

    function getUsers()
    {
        $this->sma->checkPermissions('users', TRUE);
        $warehouse_id = $this->input->get('warehouse_id');
        $user_id = $this->input->get('group_id');
        $status_id = $this->input->get('status');
        
        if($warehouse_id == 'all'){
            $warehouse_id = '';
        }
        if($user_id == 'all'){
            $user_id = '';
        }

       

        if($status_id == 'all'){
            $status_id = '';
        }

        $this->load->library('datatables');
        $this->datatables
            ->select("sma_users.id, last_name,sma_warehouses.name, sma_users.email, " . $this->db->dbprefix('groups') . ".name as gname, active, sma_users.company_id")
            ->from("users")
            ->join('groups', 'users.group_id=groups.id', 'left')
            ->join('sma_warehouses','sma_warehouses.id = sma_users.company_id','left')
            ->group_by('users.id')            
            ->edit_column('active', '$1__$2', 'active, sma_users.id')
            ->add_column("Actions", "<center><a href='" . site_url('auth/profile/$1') . "' class='tip' title='" . lang("edit_user") . "'><i class=\"fa fa-edit\"></i></a></center>", "sma_users.id");
        if($warehouse_id){
            $this->datatables->where('sma_users.company_id',$warehouse_id);
        }
        if($user_id){
            $this->datatables->where('sma_users.group_id',$user_id);
        }

        if($status_id){
            if($status_id == 2){
                $status_id = 0;
            }
            $this->datatables->where('sma_users.active',$status_id);
        }

        $this->datatables->unset_column('sma_users.company_id');
        if (!$this->Owner) {
            $this->datatables->unset_column('sma_users.id');
        }
        echo $this->datatables->generate();
    }

    
    function getPay()
    {
        // $this->sma->checkPermissions('users', TRUE);
        if($this->input->get('start_date')){
            $start_date = strtotime($this->sma->fsd($this->input->get('start_date')));
        }
        if($this->input->get('userid')){
            $userid = $this->input->get('userid');
        }

        if($this->input->get('end_date')){
            $end_date = strtotime($this->sma->fsd($this->input->get('end_date')));
        }

        $edit_link = anchor('auth/update_pay/$2/$1', '<i class="fa fa-edit"></i> ' . lang('Sửa tiền lương'),'data-toggle="modal" data-target="#myModal2"');
        $pdf_link = anchor('purchases/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("Xóa tiền lương") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('auth/delete_pay/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('Xóa tiền lương') . "</a>";

        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
         
            <li>' . $edit_link . '</li>
            
            <li>' . $delete_link . '</li>
        </ul>
        </div></div>';

        $this->load->library('datatables');
        $this->datatables
            ->select("1,sma_pay.sma_pay_id,sma_pay.sma_pay_userid, sma_pay.sma_pay_pay, sma_pay.sma_pay_note, sma_pay.sma_pay_startdate, CONCAT(sma_users.first_name,' ' ,sma_users.last_name,'') as fullname")
            ->join('sma_users','sma_users.id = sma_pay.sma_pay_createby','left')
            ->from('sma_pay');

        if($userid){
            $this->db->where('sma_pay.sma_pay_userid',$userid);
        }

        if($start_date && $end_date){
            $this->db->where('sma_pay.sma_pay_startdate BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        }else{
            if ($start_date) {
                $this->db->where('sma_pay.sma_pay_startdate >=', $start_date);
            }
            
            if ($end_date) {
                $this->db->where('sma_pay.sma_pay_startdate <=', $end_date);
            }
        }

        // if($month){
        //     $this->db->where_in('sma_bouns.sma_bouns_month',$month);
        // }

        // if($year){
        //     $this->db->where('sma_bouns.sma_bouns_year',$year);
        // }

        $this->datatables->add_column("Actions", $action, "sma_pay.sma_pay_id,sma_pay.sma_pay_userid");  
        $this->datatables->unset_column('sma_pay.sma_pay_id'); 
        $this->datatables->unset_column('sma_pay.sma_pay_userid'); 

        $arr = json_decode($this->datatables->generate());
        foreach ($arr->aaData as $key => $value) {
            
                if($value[0]){
                    $arr->aaData[$key][0] = $key;
                } 

                if($value[3]){
                    $arr->aaData[$key][3] = $this->sma->ihrsd($value[3]);
                } 
        }


        echo json_encode($arr);
    }

    function getBouns()
    {
        // $this->sma->checkPermissions('users', TRUE);
        if($this->input->get('month')){
            $month = explode(",", $this->input->get('month'));
        }
        if($this->input->get('userid')){
            $userid = $this->input->get('userid');
        }

        if($this->input->get('year')){
            $year = $this->input->get('year');
        }

        // $user_id = $this->input->get('group_id');
        // $status_id = $this->input->get('status');
        
        // if($warehouse_id == 'all'){
        //     $warehouse_id = '';
        // }
        // if($user_id == 'all'){
        //     $user_id = '';
        // }

       

        // if($status_id == 'all'){
        //     $status_id = '';
        // }

        $edit_link = anchor('auth/update_bouns/$2/$1', '<i class="fa fa-edit"></i> ' . lang('Sửa tiền thưởng'),'data-toggle="modal" data-target="#myModal2"');
        $pdf_link = anchor('purchases/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("Xóa tiền thưởng") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('auth/delete_bouns/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('Xóa tiền thưởng') . "</a>";

        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
         
            <li>' . $edit_link . '</li>
            
            <li>' . $delete_link . '</li>
        </ul>
        </div></div>';

        $this->load->library('datatables');
        $this->datatables
            ->select("1,sma_bouns.sma_bouns_id,sma_bouns.sma_bouns_userid, sma_bouns.sma_bouns_bouns, sma_bouns.sma_bouns_note, sma_bouns.sma_bouns_month,sma_bouns.sma_bouns_year, CONCAT(sma_users.first_name,' ' ,sma_users.last_name,'') as fullname")
            ->join('sma_users','sma_users.id = sma_bouns.sma_bouns_createby','left')
            ->from('sma_bouns');

        if($userid){
            $this->db->where('sma_bouns.sma_bouns_userid',$userid);
        }

        if($month){
            $this->db->where_in('sma_bouns.sma_bouns_month',$month);
        }

        if($year){
            $this->db->where('sma_bouns.sma_bouns_year',$year);
        }

        $this->datatables->add_column("Actions", $action, "sma_bouns.sma_bouns_id,sma_bouns.sma_bouns_userid");  
        $this->datatables->unset_column('sma_bouns.sma_bouns_id'); 
        $this->datatables->unset_column('sma_bouns.sma_bouns_userid'); 

        $arr = json_decode($this->datatables->generate());
        foreach ($arr->aaData as $key => $value) {
            if($value[0]){
                $arr->aaData[$key][0] = $key+1;
            } 
        }

        echo json_encode($arr);
    }

    function getOtex()
    {
        // $this->sma->checkPermissions('users', TRUE);
        if($this->input->get('month')){
            $month = explode(",", $this->input->get('month'));
        }
        if($this->input->get('userid')){
            $userid = $this->input->get('userid');
        }

        if($this->input->get('year')){
            $year = $this->input->get('year');
        }

        // $user_id = $this->input->get('group_id');
        // $status_id = $this->input->get('status');
        
        // if($warehouse_id == 'all'){
        //     $warehouse_id = '';
        // }
        // if($user_id == 'all'){
        //     $user_id = '';
        // }

       

        // if($status_id == 'all'){
        //     $status_id = '';
        // }

        $edit_link = anchor('auth/update_bouns/$2/$1', '<i class="fa fa-edit"></i> ' . lang('Sửa chi phí'),'data-toggle="modal" data-target="#myModal2"');
        $pdf_link = anchor('purchases/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("Xóa chi phí") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('auth/delete_otex/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('Xóa chi phí') . "</a>";

        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
         
            <li>' . $edit_link . '</li>
            
            <li>' . $delete_link . '</li>
        </ul>
        </div></div>';

        $this->load->library('datatables');
        $this->datatables
            ->select("1,sma_otex.sma_otex_id,sma_otex.sma_otex_userid, sma_otex.sma_otex_pay, sma_otex.sma_otex_note, sma_otex.sma_otex_month,sma_otex.sma_otex_year, CONCAT(sma_users.first_name,' ' ,sma_users.last_name,'') as fullname")
            ->join('sma_users','sma_users.id = sma_otex.sma_otex_createby','left')
            ->from('sma_otex');

        if($userid){
            $this->db->where('sma_otex.sma_otex_userid',$userid);
        }

        if($month){
            $this->db->where_in('sma_otex.sma_otex_month',$month);
        }

        if($year){
            $this->db->where('sma_otex.sma_otex_year',$year);
        }
        $this->datatables->add_column("Actions", $action, "sma_otex.sma_otex_id,sma_otex.sma_otex_userid"); 
        $this->datatables->unset_column('sma_otex.sma_otex_id'); 
        $this->datatables->unset_column('sma_otex.sma_otex_userid');

        $arr = json_decode($this->datatables->generate());
        foreach ($arr->aaData as $key => $value) {
            if($value[0]){
                $arr->aaData[$key][0] = $key+1;
            } 
        }

        echo json_encode($arr);
    }
    function getTimeout()
    {
        // $this->sma->checkPermissions('users', TRUE);
        if($this->input->get('month')){
            $month = explode(",", $this->input->get('month'));
            foreach ($month as $key => $value) {
                $month[$key] = $value *1;
            }
        }
        if($this->input->get('userid')){
            $userid = $this->input->get('userid');
        }

        if($this->input->get('year')){
            $year = $this->input->get('year');
        }
        // $user_id = $this->input->get('group_id');
        // $status_id = $this->input->get('status');
        
        // if($warehouse_id == 'all'){
        //     $warehouse_id = '';
        // }
        // if($user_id == 'all'){
        //     $user_id = '';
        // }

       

        // if($status_id == 'all'){
        //     $status_id = '';
        // }

        $edit_link = anchor('auth/update_bouns/$2/$1', '<i class="fa fa-edit"></i> ' . lang('Sửa tiền thưởng'),'data-toggle="modal" data-target="#myModal2"');
        $pdf_link = anchor('purchases/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("Xóa ngày nghỉ") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger ' href='" . site_url('auth/delete_timeout/$2/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('Xóa ngày nghỉ') . "</a>";

        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
         
            
            
            <li>' . $delete_link . '</li>
        </ul>
        </div></div>';

        $this->load->library('datatables');
        $this->datatables
            ->select("1,sma_timeout.sma_timeout_id,sma_timeout.sma_timeout_userid, sma_timeout.sma_timeout_startdate,sma_timeout.sma_timeout_enddate,sma_timeout.sma_timeout_day,,sma_timeout.sma_timeout_type")
            // ->join('sma_users','sma_users.id = sma_bouns.sma_bouns_createby','left')
            ->from('sma_timeout');


        if($userid){
            $this->db->where('sma_timeout.sma_timeout_userid',$userid);
        }

        if($month){            
            $this->db->where_in('sma_timeout.sma_timeout_month',$month);
        }

        if($year){
            $this->db->where('sma_timeout.sma_timeout_year',$year);
        }

        if($this->Owner || $this->Admin){
            $this->datatables->add_column("Actions", $action, "sma_timeout.sma_timeout_id,sma_timeout.sma_timeout_userid");
        }  
        $this->datatables->unset_column('sma_timeout.sma_timeout_id'); 
        $this->datatables->unset_column('sma_timeout.sma_timeout_userid'); 

        $arr = json_decode($this->datatables->generate());
        foreach ($arr->aaData as $key => $value) {
            if($value[0]){
                $arr->aaData[$key][0] = $key+1;
            } 
            if($value[1]){
                $arr->aaData[$key][1] = $this->sma->ihrsd($value[1]);
            } 
            if($value[2]){
                $arr->aaData[$key][2] = $this->sma->ihrsd($value[2]);
            }

            $arr->aaData[$key][4] = $this->sma->timeoutTypeText($value[4]);

        }

        echo json_encode($arr);
    }

    function getFines()
    {
        // $this->sma->checkPermissions('users', TRUE);
        if($this->input->get('month')){
            $month = explode(",", $this->input->get('month'));
        }
        if($this->input->get('userid')){
            $userid = $this->input->get('userid');
        }



        // $user_id = $this->input->get('group_id');
        // $status_id = $this->input->get('status');
        
        // if($warehouse_id == 'all'){
        //     $warehouse_id = '';
        // }
        // if($user_id == 'all'){
        //     $user_id = '';
        // }

       

        // if($status_id == 'all'){
        //     $status_id = '';
        // }

        $edit_link = anchor('auth/update_fine/$2/$1', '<i class="fa fa-edit"></i> ' . lang('Sửa tiền phạt'),'data-toggle="modal" data-target="#myModal2"');
        $pdf_link = anchor('purchases/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
        $delete_link = "<a href='#' class='po' title='<b>" . $this->lang->line("Xóa tiền phạt") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('auth/delete_fine/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('Xóa tiền thưởng') . "</a>";

        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
         
            <li>' . $edit_link . '</li>
            
            <li>' . $delete_link . '</li>
        </ul>
        </div></div>';

        $this->load->library('datatables');
        $this->datatables
            ->select("1,sma_fine.sma_fine_id,sma_fine.sma_fine_userid, sma_fine.sma_fine_fine, sma_fine.sma_fine_note, sma_fine.sma_fine_month,sma_fine.sma_fine_year, CONCAT(sma_users.first_name,' ' ,sma_users.last_name,'') as fullname")
            ->join('sma_users','sma_users.id = sma_fine.sma_fine_createby','left')
            ->from('sma_fine');

        if($userid){
            $this->db->where('sma_fine.sma_fine_userid',$userid);
        }

        if($month){
            $this->db->where_in('sma_fine.sma_fine_month',$month);
        }

        $this->datatables->add_column("Actions", $action, "sma_fine.sma_fine_id,sma_fine.sma_fine_userid");  
        $this->datatables->unset_column('sma_fine.sma_fine_id'); 
        $this->datatables->unset_column('sma_fine.sma_fine_userid'); 

        $arr = json_decode($this->datatables->generate());

        foreach ($arr->aaData as $key => $value) {
            if($value[0]){
                $arr->aaData[$key][0] = $key+1;
            } 
        }

        echo json_encode($arr);
    }

    function getUserLogins($id = NULL)
    {
        if (!$this->ion_auth->in_group(array('super-admin', 'admin'))) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect('welcome');
        }
        $this->load->library('datatables');
        $this->datatables
            ->select("login, ip_address, time")
            ->from("user_logins")
            ->where('user_id', $id);

        echo $this->datatables->generate();
    }

    function delete_avatar($id = NULL, $avatar = NULL)
    {

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group('owner') && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . $_SERVER["HTTP_REFERER"] . "'; }, 0);</script>");
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            unlink('assets/uploads/avatars/' . $avatar);
            unlink('assets/uploads/avatars/thumbs/' . $avatar);
            if ($id == $this->session->userdata('user_id')) {
                $this->session->unset_userdata('avatar');
            }
            $this->db->update('users', array('avatar' => NULL), array('id' => $id));
            $this->session->set_flashdata('message', lang("avatar_deleted"));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . $_SERVER["HTTP_REFERER"] . "'; }, 0);</script>");
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    

    function profile($id = NULL)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group('owner') && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$id || empty($id)) {
            redirect('auth');
        }

        $this->data['title'] = lang('profile');

        $user = $this->ion_auth->user($id)->row();
        $user->CompanyName = $this->site->getWarehouseByID($user->company_id)->name;
        if($user->warehouse_id){
            if($user->warehouse_id != 'all'){
                $user->warehouse_name = $this->site->getWarehouseByID($user->warehouse_id)->name;
            }else{
                $user->warehouse_name = 'Tất cả';
            }
        }

        $user->group_name = $this->site->getGroupByID($user->group_id)->description;
        
        $groups = $this->ion_auth->groups()->result_array();
        $this->data['csrf'] = $this->_get_csrf_nonce();
        $this->data['user'] = $user;
        $time = explode('-', $this->sma->fsd($this->sma->ihrsd($user->start_date)));
        if($time[0] == date('Y') && $time[1] == date('m')){
            $this->data['userTime'] =  $time;
        }
        
        $this->data['groups'] = $groups;
        $this->data['billers'] = $this->site->getAllCompanies('biller');
        $this->data['warehouses'] = $this->site->getAllWarehouses(null);
        $this->data['timeworks'] = $this->site->getAllTimeWork();
        $this->data['pays'] = $this->site->getAllPayByIDUser($id);

        $this->data['usertimeworks'] = $this->site->getAllUserTimeWorkByIDUser($id);
        $a = $this->site->getAllTimeWork();
        foreach ($a as $key => $value) {

            $a[$key]->monday = $this->site->getAllUserTimeWorkByIDUserIDWork($id,$value->id,'monday');
            $a[$key]->tuesday = $this->site->getAllUserTimeWorkByIDUserIDWork($id,$value->id,'tuesday');
            $a[$key]->wednesday = $this->site->getAllUserTimeWorkByIDUserIDWork($id,$value->id,'wednesday');
            $a[$key]->thursday = $this->site->getAllUserTimeWorkByIDUserIDWork($id,$value->id,'thursday');
            $a[$key]->friday = $this->site->getAllUserTimeWorkByIDUserIDWork($id,$value->id,'friday');
            $a[$key]->saturday = $this->site->getAllUserTimeWorkByIDUserIDWork($id,$value->id,'saturday');
            $a[$key]->sunday = $this->site->getAllUserTimeWorkByIDUserIDWork($id,$value->id,'sunday');

        }

        // if($totalDayWo <= 28){
        //     tiền = $totalDayWo * lương
        // }else{
        //     tiền = ($dayOnMonth - $totalDayWo) * (lương * 2)
        // }
        // echo '<pre>';var_dump($totalDayWo);die();
        $this->data['details'] = $this->sma->getDayUserWork($id);
        $this->data['usertimeworksB'] = $a;

        $this->data['timeouts'] = $this->site->getAllTimeoutByIDUser($id, null, null);
        //$this->data['product_groups'] = $this->auth_model->getAllUserProductGroups($id);

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['password'] = array(
            'name' => 'password',
            'id' => 'password',
            'class' => 'form-control',
            'type' => 'password',
            'value' => ''
        );
        $this->data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'class' => 'form-control',
            'type' => 'password',
            'value' => ''
        );
        $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
        $this->data['old_password'] = array(
            'name' => 'old',
            'id' => 'old',
            'class' => 'form-control',
            'type' => 'password',
        );
        $this->data['new_password'] = array(
            'name' => 'new',
            'id' => 'new',
            'type' => 'password',
            'class' => 'form-control',
            'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
        );
        $this->data['new_password_confirm'] = array(
            'name' => 'new_confirm',
            'id' => 'new_confirm',
            'type' => 'password',
            'class' => 'form-control',
            'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
        );
        $this->data['user_id'] = array(
            'name' => 'user_id',
            'id' => 'user_id',
            'type' => 'hidden',
            'value' => $user->id,
        );

        $this->data['id'] = $id;

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('auth/users'), 'page' => lang('users')), array('link' => '#', 'page' => lang('profile')));
        $meta = array('page_title' => lang('profile'), 'bc' => $bc);
        $this->page_construct('auth/profile', $meta, $this->data);
    }

    public function getLastDateEndTimeout(){   

        $inv = $this->site->getAllTimeoutByIDUserDESC($this->input->post('id'),date('m'));  
        $inv->sma_timeout_startdate = $this->sma->ihrsd($inv->sma_timeout_startdate);  
        $inv->sma_timeout_enddate = $this->sma->ihrsd($inv->sma_timeout_enddate);
        echo json_encode($inv);  

    }

    public function captcha_check($cap)
    {
        $expiration = time() - 300; // 5 minutes limit
        $this->db->delete('captcha', array('captcha_time <' => $expiration));

        $this->db->select('COUNT(*) AS count')
            ->where('word', $cap)
            ->where('ip_address', $this->input->ip_address())
            ->where('captcha_time >', $expiration);

        if ($this->db->count_all_results('captcha')) {
            return true;
        } else {
            $this->form_validation->set_message('captcha_check', lang('captcha_wrong'));
            return FALSE;
        }
    }

   function update_bouns($id = NULL,$idbouns = NULL)
    {

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->form_validation->set_rules('bouns', $this->lang->line("Tiền thưởng"), 'required');

        if ($this->form_validation->run() == true ) {
            if($this->input->post('idbouns')){
                 $data = array(
                    'sma_bouns_userid' => $id,
                    'sma_bouns_bouns' => str_replace(',','',$this->input->post('bouns')),
                    'sma_bouns_note' => $this->input->post('note'),
                    'sma_bouns_createby' =>  $this->session->userdata('user_id'),
                    'sma_bouns_creattime' => strtotime(date('Y-m-d h:i:s')),
                );
            }else{
                $data = array(
                    'sma_bouns_userid' => $id,
                    'sma_bouns_bouns' => str_replace(',','',$this->input->post('bouns')),
                    'sma_bouns_note' => $this->input->post('note'),
                    'sma_bouns_month' => date('m'),
                    'sma_bouns_year' => date('Y'),
                    'sma_bouns_createby' =>  $this->session->userdata('user_id'),
                    'sma_bouns_creattime' => strtotime(date('Y-m-d h:i:s')),
                );
               
            }


        } 
        if ($this->form_validation->run() == true) {
            if($this->auth_model->updateBouns($data,$this->input->post('idbouns'))){
                $this->session->set_flashdata('message', lang('Cập nhật tiền thưởng thành công'));
            }else{
                $this->session->set_flashdata('message', lang('Cập nhật tiền thưởng thất bại'));
            }
            redirect('auth/profile/' . $id . '/#pay');
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['id'] = $id;
            if($idbouns){
                $this->data['inv'] = $this->site->getBounsByID($idbouns);
            }
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'auth/update_bouns', $this->data);
        }
    }

    function update_ot_ex($id = NULL,$idbouns = NULL)
    {

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->form_validation->set_rules('fine', $this->lang->line("Chi phí ăn trưa"), 'required');

        if ($this->form_validation->run() == true ) {
            if(str_replace(',','',$this->input->post('fine') ) > 0){
                if($this->input->post('idotex')){
                     $data = array(
                        'sma_otex_userid' => $id,
                        'sma_otex_bouns' => str_replace(',','',$this->input->post('fine')),
                        'sma_otex_note' => $this->input->post('note'),
                        'sma_otex_createby' =>  $this->session->userdata('user_id'),
                        'sma_otex_creattime' => strtotime(date('Y-m-d h:i:s')),
                    );
                }else{
                    $data = array(
                        'sma_otex_userid' => $id,
                        'sma_otex_pay' => str_replace(',','',$this->input->post('fine')),
                        'sma_otex_note' => $this->input->post('note'),
                        'sma_otex_month' => date('m'),
                        'sma_otex_year' => date('Y'),
                        'sma_otex_createby' =>  $this->session->userdata('user_id'),
                        'sma_otex_creattime' => strtotime(date('Y-m-d h:i:s')),
                    );
                   
                }
            }
        } 
        if ($this->form_validation->run() == true) {
            if($this->auth_model->updateOxte($data,$this->input->post('idotex'))){
                $this->session->set_flashdata('message', lang('Cập nhật chi phí ăn trưa thành công'));
            }else{
                $this->session->set_flashdata('message', lang('Cập nhật chi phí ăn trưa thất bại'));
            }
            redirect('auth/profile/' . $id . '/#pay');
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['id'] = $id;
            if($idbouns){
                $this->data['inv'] = $this->site->getBounsByID($idbouns);
            }
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'auth/update_ot_ex', $this->data);
        }
    }



    function update_pay($id = NULL,$idpay = NULL)
    {

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $this->form_validation->set_rules('fine', $this->lang->line("Tiền lương"), 'required');
        $this->form_validation->set_rules('start_date', $this->lang->line("Ngày áp dụng"), 'required');


        if ($this->form_validation->run() == true ) {
            if($this->input->post('idpay')){
                 $data = array(
                    'sma_bouns_userid' => $id,
                    'sma_bouns_bouns' => str_replace(',','',$this->input->post('bouns')),
                    'sma_bouns_note' => $this->input->post('note'),
                    'sma_bouns_createby' =>  $this->session->userdata('user_id'),
                    'sma_bouns_creattime' => strtotime(date('Y-m-d h:i:s')),
                );
            }else{                
                $data = array(
                    'sma_pay_userid' => $id,
                    'sma_pay_pay' => str_replace(',','',$this->input->post('fine')),
                    'sma_pay_startdate' => strtotime($this->sma->fsd($this->input->post('start_date'))),
                    'sma_pay_createby' => $this->session->userdata('user_id'),
                    'sma_pay_createtime' =>  strtotime(date('Y-m-d h:i:s')),
                    'sma_pay_note' => $this->input->post('note'),
                );
            }


        } 
        if ($this->form_validation->run() == true) {
            if($this->auth_model->updatePay($data,$this->input->post('idpay'))){
                $this->session->set_flashdata('message', lang('Cập nhật tăng lương thành công'));
            }else{
                $this->session->set_flashdata('message', lang('Cập nhật tăng lương thất bại'));
            }
            redirect('auth/profile/' . $id . '/#pay');
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['id'] = $id;
            $pays = $this->site->getAllPayByIDUser($id);

            $this->data['payDate'] = $this->sma->ihrsd($pays[count($pays)-1]['sma_pay_startdate']);
            
            if($idpay){
                $this->data['inv'] = $this->site->getPayByID($idpay);
                $this->data['payDate'] = $this->sma->ihrsd($pays[count($pays)-2]['sma_pay_startdate']);
                

            }
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'auth/update_pay', $this->data);
        }
    }


    function update_fine($id = NULL,$idfine = NULL)
    {

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('fine', $this->lang->line("Tiền Phạt"), 'required');

        if ($this->form_validation->run() == true ) {
            if($this->input->post('idfine')){
                 $data = array(
                    'sma_fine_userid' => $id,
                    'sma_fine_fine' => str_replace(',','',$this->input->post('fine')),
                    'sma_fine_note' => $this->input->post('note'),
                    'sma_fine_createby' =>  $this->session->userdata('user_id'),
                    'sma_fine_creattime' => strtotime(date('Y-m-d h:i:s')),
                );
            }else{
                $data = array(
                    'sma_fine_userid' => $id,
                    'sma_fine_fine' => str_replace(',','',$this->input->post('fine')),
                    'sma_fine_note' => $this->input->post('note'),
                    'sma_fine_month' => date('m'),
                    'sma_fine_year' => date('Y'),
                    'sma_fine_createby' =>  $this->session->userdata('user_id'),
                    'sma_fine_creattime' => strtotime(date('Y-m-d h:i:s')),
                );
               
            }


        } 
        if ($this->form_validation->run() == true) {
            if($this->auth_model->updateFine($data,$this->input->post('idfine'))){
                $this->session->set_flashdata('message', lang('Cập nhật tiền phạt thành công'));
            }else{
                $this->session->set_flashdata('message', lang('Cập nhật tiền phạt thất bại'));
            }
            redirect('auth/profile/' . $id . '/#pay');
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['id'] = $id;
            if($idfine){
                $this->data['inv'] = $this->site->getFinesByID($idfine);
            }
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'auth/update_fine', $this->data);
        }
    }

    function history_bouns($id = NULL,$idbouns = NULL)
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['id'] = $id;
        $this->data['modal_js'] = $this->site->modal_js();
        $this->load->view($this->theme . 'auth/history_bouns', $this->data);
    }

    function history_pay($id = NULL,$idbouns = NULL)
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['id'] = $id;
        $this->data['modal_js'] = $this->site->modal_js();
        $this->load->view($this->theme . 'auth/history_pay', $this->data);
    }

    function history_fine($id = NULL,$idbouns = NULL)
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['id'] = $id;
        $this->data['modal_js'] = $this->site->modal_js();
        $this->load->view($this->theme . 'auth/history_fine', $this->data);
    }

    function history_ot_ex($id = NULL,$idbouns = NULL)
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['id'] = $id;
        $this->data['modal_js'] = $this->site->modal_js();
        $this->load->view($this->theme . 'auth/history_ot_ex', $this->data);
    }

    



    function login($m = NULL)
    {
        $this->data['title'] = lang('login');

        if ($this->Settings->captcha) {
            $this->form_validation->set_rules('captcha', lang('captcha'), 'required|callback_captcha_check');
        }

        if ($this->form_validation->run() == true) {

            $remember = (bool)$this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                if ($this->Settings->mmode) {
                    if (!$this->ion_auth->in_group('owner')) {
                        $this->session->set_flashdata('error', lang('site_is_offline_plz_try_later'));
                        redirect('auth/logout');
                    }
                }
                if ($this->ion_auth->in_group('customer') || $this->ion_auth->in_group('supplier')) {
                    redirect('auth/logout/1');
                }
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $referrer = $this->session->userdata('requested_page') ? $this->session->userdata('requested_page') : 'welcome';
                redirect($referrer);
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                redirect('login');
            }
        } else {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['message'] = $this->session->flashdata('message');
            if ($this->Settings->captcha) {
                $this->load->helper('captcha');
                $vals = array(
                    'img_path' => './assets/captcha/',
                    'img_url' => site_url() . 'assets/captcha/',
                    'img_width' => 150,
                    'img_height' => 34,
                    'word_length' => 5,
                    'colors' => array('background' => array(255, 255, 255), 'border' => array(204, 204, 204), 'text' => array(102, 102, 102), 'grid' => array(204, 204, 204))
                );
                $cap = create_captcha($vals);
                $capdata = array(
                    'captcha_time' => $cap['time'],
                    'ip_address' => $this->input->ip_address(),
                    'word' => $cap['word']
                );

                $query = $this->db->insert_string('captcha', $capdata);
                $this->db->query($query);
                $this->data['image'] = $cap['image'];
                $this->data['captcha'] = array('name' => 'captcha',
                    'id' => 'captcha',
                    'type' => 'text',
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => lang('type_captcha')
                );
            }

            $this->data['identity'] = array('name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => lang('email'),
                'value' => $this->form_validation->set_value('identity'),
            );
            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => lang('password'),
            );
            $this->data['allow_reg'] = $this->Settings->allow_reg;
            if ($m == 'db') {
                $this->data['message'] = lang('db_restored');
            } elseif ($m) {
                $this->data['error'] = lang('we_are_sorry_as_this_sction_is_still_under_development.');
            }

            $this->load->view($this->theme . 'auth/login', $this->data);
        }
    }

    function reload_captcha()
    {
        $this->load->helper('captcha');
        $vals = array(
            'img_path' => './assets/captcha/',
            'img_url' => site_url() . 'assets/captcha/',
            'img_width' => 150,
            'img_height' => 34,
            'word_length' => 5,
            'colors' => array('background' => array(255, 255, 255), 'border' => array(204, 204, 204), 'text' => array(102, 102, 102), 'grid' => array(204, 204, 204))
        );
        $cap = create_captcha($vals);
        $capdata = array(
            'captcha_time' => $cap['time'],
            'ip_address' => $this->input->ip_address(),
            'word' => $cap['word']
        );
        $query = $this->db->insert_string('captcha', $capdata);
        $this->db->query($query);
        //$this->data['image'] = $cap['image'];

        echo $cap['image'];
    }

    function logout($m = NULL)
    {

        $logout = $this->ion_auth->logout();
        $this->session->set_flashdata('message', $this->ion_auth->messages());

        redirect('login/' . $m);
    }

    function change_password()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('login');
        }
        $this->form_validation->set_rules('old_password', lang('old_password'), 'required');
        $this->form_validation->set_rules('new_password', lang('new_password'), 'required|min_length[8]|max_length[25]');
        $this->form_validation->set_rules('new_password_confirm', lang('confirm_password'), 'required|matches[new_password]');

        $user = $this->ion_auth->user()->row();

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('auth/profile/' . $user->id . '/#cpassword');
        } else {
            if (DEMO) {
                $this->session->set_flashdata('warning', lang('disabled_in_demo'));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

            $change = $this->ion_auth->change_password($identity, $this->input->post('old_password'), $this->input->post('new_password'));

            if ($change) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->logout();
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                redirect('auth/profile/' . $user->id . '/#cpassword');
            }
        }
    }

    function forgot_password()
    {
        $this->form_validation->set_rules('forgot_email', lang('email_address'), 'required|valid_email');

        if ($this->form_validation->run() == false) {
            $error = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->session->set_flashdata('error', $error);
            redirect("login#forgot_password");
        } else {

            $identity = $this->ion_auth->where('email', strtolower($this->input->post('forgot_email')))->users()->row();
            if (empty($identity)) {
                $this->ion_auth->set_message('forgot_password_email_not_found');
                $this->session->set_flashdata('error', $this->ion_auth->messages());
                redirect("login#forgot_password");
            }

            $forgotten = $this->ion_auth->forgotten_password($identity->email);

            if ($forgotten) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("login#forgot_password");
            } else {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                redirect("login#forgot_password");
            }
        }
    }

    public function reset_password($code = NULL)
    {
        if (!$code) {
            show_404();
        }

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user) {

            $this->form_validation->set_rules('new', lang('password'), 'required|min_length[8]|max_length[25]|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', lang('confirm_password'), 'required');

            if ($this->form_validation->run() == false) {

                $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['title'] = lang('reset_password');
                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['new_password'] = array(
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'class' => 'form-control',
                    'pattern' => '^.{8}.*$',
                );
                $this->data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'class' => 'form-control',
                    'pattern' => '^.{8}.*$',
                );
                $this->data['user_id'] = array(
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                );
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;
                $this->data['identity_label'] = $user->email;
                //render
                $this->load->view($this->theme . 'auth/reset_password', $this->data);
            } else {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {

                    //something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($code);
                    show_error(lang('error_csrf'));

                } else {
                    // finally change the password
                    $identity = $user->email;

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change) {
                        //if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        //$this->logout();
                        redirect('login');
                    } else {
                        $this->session->set_flashdata('error', $this->ion_auth->errors());
                        redirect('auth/reset_password/' . $code);
                    }
                }
            }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('error', $this->ion_auth->errors());
            redirect("login#forgot_password");
        }
    }

    function activate($id, $code = false)
    {

        if ($code !== false) {
            $activation = $this->ion_auth->activate($id, $code);
        } else if ($this->Owner) {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation) {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            if ($this->Owner) {
                redirect($_SERVER["HTTP_REFERER"]);
            } else {
                redirect("auth/login");
            }
        } else {
            $this->session->set_flashdata('error', $this->ion_auth->errors());
            redirect("forgot_password");
        }
    }

    function deactivate($id = NULL)
    {
        $this->sma->checkPermissions('users', TRUE);
        $id = $this->config->item('use_mongodb', 'ion_auth') ? (string)$id : (int)$id;
        $this->form_validation->set_rules('confirm', lang("confirm"), 'required');

        if ($this->form_validation->run() == FALSE) {
            if ($this->input->post('deactivate')) {
                $this->session->set_flashdata('error', validation_errors());
                redirect($_SERVER["HTTP_REFERER"]);
            } else {
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['user'] = $this->ion_auth->user($id)->row();
                $this->data['modal_js'] = $this->site->modal_js();
                $this->load->view($this->theme . 'auth/deactivate_user', $this->data);
            }
        } else {

            if ($this->input->post('confirm') == 'yes') {
                if ($id != $this->input->post('id')) {
                    show_error(lang('error_csrf'));
                }

                if ($this->ion_auth->logged_in() && $this->Owner) {
                    $this->ion_auth->deactivate($id);
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                }
            }

            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function create_user()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->data['title'] = "Create User";
        $this->form_validation->set_rules('username', lang("username"), 'trim|is_unique[users.username]');
        $this->form_validation->set_rules('email', lang("email"), 'trim|is_unique[users.email]');

        if ($this->form_validation->run() == true) {

            $username = strtolower($this->input->post('username'));
            $email = strtolower($this->input->post('email'));
            $password = $this->generateRandomString(6);
            $notify = $this->input->post('notify');

            $pay = array(
                'start_date' => strtotime($this->sma->fsd($this->input->post('date_start'))),
                'pay' => str_replace(',', '', $this->input->post('pay')),
            );


            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company_id' => $this->input->post('basis'),
                'phone' => $this->input->post('phone'),
                'gender' => $this->input->post('gender'),
                'group_id' => $this->input->post('group') ? $this->input->post('group') : '3',
                // 'biller_id' => $this->input->post('biller'),
                'warehouse_id' => $this->input->post('warehouse'),
            );
            $active = $this->input->post('status');
            //$groupData = $this->ion_auth->in_group('super-admin') ? array($this->input->post('group')) : NULL;
            //$this->sma->print_arrays($data);

        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $active, $notify,$pay)) {
            $content = "Xin chào ". $additional_data["last_name"]."! \nQuỳnh Quyên Spa gửi bạn thông tin đăng nhập tại hệ thống. \nUsername: ".$username."\n". "Mật khẩu: ".$password;
            $this->sendMail($email, $content);
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth/users");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('error')));

            $this->data['groups'] = $this->ion_auth->groups()->result_array();
            

            
            //$this->data['product_groups'] = $this->auth_model->getAllProductGroups();
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['warehouses'] = $this->site->getAllWarehouses();

            //$this->_render_page('auth/create_user', $this->data);
            $bc = array(array('link' => site_url('home'), 'page' => lang('home')), array('link' => site_url('auth/users'), 'page' => lang('users')), array('link' => '#', 'page' => lang('create_user')));
            $meta = array('page_title' => lang('users'), 'bc' => $bc);
            $this->page_construct('auth/create_user', $meta, $this->data);
        }


    }


    function delete_bouns($id = NULL)
    {
        // $this->sma->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->auth_model->deleteBouns($id)) {
            if($this->input->is_ajax_request()) {
                echo lang("Xóa thành công"); die();
            }
            $this->session->set_flashdata('message', lang('Xóa thành công'));
            redirect('auth/profile/' . $id . '/#pay');
        }
    }

    function delete_pay($id = NULL)
    {
        // $this->sma->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $dataInv = $this->site->getPayByID($id);
        $inv = $this->site->getPayByIDUser($dataInv->sma_pay_userid,'2','DESC');
        $data['prev'] = $inv[1];
        $data['now'] = $inv[0];

        if ($this->auth_model->deletePay($data)) {
            if($this->input->is_ajax_request()) {
                echo lang("Xóa thành công"); die();
            }
            $this->session->set_flashdata('message', lang('Xóa thành công'));
            redirect('auth/profile/' . $id . '/#pay');
        }
    }

    function delete_fine($id = NULL)
    {
        // $this->sma->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->auth_model->deleteFine($id)) {
            if($this->input->is_ajax_request()) {
                echo lang("Xóa thành công"); die();
            }
            $this->session->set_flashdata('message', lang('Xóa thành công'));
            redirect('auth/profile/' . $id . '/#pay');
        }
    }

    function check_group(){
        $a = $this->site->getPermisionByIDGroup($this->input->post('id_g'));
        if($this->input->post('id_user')){
            $data['user'] = $this->site->getUserByID($this->input->post('id_user'));
        }
        $data['flag'] = 0;
        if($a->sales_add == 1 && $a->purchases_add == 1){
            $data['flag'] = 1;
        }
        echo json_encode($data);
    }

    function time_work($id){
        
        $this->form_validation->set_rules('duty', lang("Dạng làm việc"), 'required');
        if ($this->form_validation->run() === TRUE) {
           
                if($this->input->post('monday')){
                    foreach ($this->input->post('monday') as $key => $value) {
                       $val = explode("_", $value);
                       $data[] = array(
                            'sma_usertimework_userid' => $id,
                            'sma_usertimework_dayofweek' => $val[0],
                            'sma_usertimework_timeworkid' => $val[1],
                            'sma_usertimework_duty' => $this->input->post('duty'),
                            'sma_usertimework_createby' => $this->session->userdata('user_id'),
                            'sma_usertimework_createtime' => strtotime(date('Y-m-d H:i:s')),
                        );
                    }
                }

                if($this->input->post('tuesday')){
                    foreach ($this->input->post('tuesday') as $key => $value) {
                       $val = explode("_", $value);
                       $data[] = array(
                            'sma_usertimework_userid' => $id,
                            'sma_usertimework_dayofweek' => $val[0],
                            'sma_usertimework_timeworkid' => $val[1],
                            'sma_usertimework_duty' => $this->input->post('duty'),
                            'sma_usertimework_createby'=> $this->session->userdata('user_id'),
                            'sma_usertimework_createtime' => strtotime(date('Y-m-d H:i:s')),
                        );
                    }
                }

                if($this->input->post('wednesday')){
                    foreach ($this->input->post('wednesday') as $key => $value) {
                       $val = explode("_", $value);
                       $data[] = array(
                            'sma_usertimework_userid' => $id,
                            'sma_usertimework_dayofweek' => $val[0],
                            'sma_usertimework_timeworkid' => $val[1],
                            'sma_usertimework_duty' => $this->input->post('duty'),
                            'sma_usertimework_createby'=> $this->session->userdata('user_id'),
                            'sma_usertimework_createtime' => strtotime(date('Y-m-d H:i:s')),
                        );
                    }
                }

                if($this->input->post('thursday')){
                    foreach ($this->input->post('thursday') as $key => $value) {
                       $val = explode("_", $value);
                       $data[] = array(
                            'sma_usertimework_userid' => $id,
                            'sma_usertimework_dayofweek' => $val[0],
                            'sma_usertimework_timeworkid' => $val[1],
                            'sma_usertimework_duty' => $this->input->post('duty'),
                            'sma_usertimework_createby'=> $this->session->userdata('user_id'),
                            'sma_usertimework_createtime' => strtotime(date('Y-m-d H:i:s')),
                        );
                    }
                }

                if($this->input->post('friday')){
                    foreach ($this->input->post('friday') as $key => $value) {
                       $val = explode("_", $value);
                       $data[] = array(
                            'sma_usertimework_userid' => $id,
                            'sma_usertimework_dayofweek' => $val[0],
                            'sma_usertimework_timeworkid' => $val[1],
                            'sma_usertimework_duty' => $this->input->post('duty'),
                            'sma_usertimework_createby'=> $this->session->userdata('user_id'),
                            'sma_usertimework_createtime' => strtotime(date('Y-m-d H:i:s')),
                        );
                    }
                }

                if($this->input->post('saturday')){
                    foreach ($this->input->post('saturday') as $key => $value) {
                       $val = explode("_", $value);
                       $data[] = array(
                            'sma_usertimework_userid' => $id,
                            'sma_usertimework_dayofweek' => $val[0],
                            'sma_usertimework_timeworkid' => $val[1],
                            'sma_usertimework_duty' => $this->input->post('duty'),
                            'sma_usertimework_createby'=> $this->session->userdata('user_id'),
                            'sma_usertimework_createtime' => strtotime(date('Y-m-d H:i:s')),
                        );
                    }
                }

                if($this->input->post('sunday')){
                    foreach ($this->input->post('sunday') as $key => $value) {
                       $val = explode("_", $value);
                       $data[] = array(
                            'sma_usertimework_userid' => $id,
                            'sma_usertimework_dayofweek' => $val[0],
                            'sma_usertimework_timeworkid' => $val[1],
                            'sma_usertimework_duty' => $this->input->post('duty'),
                            'sma_usertimework_createby'=> $this->session->userdata('user_id'),
                            'sma_usertimework_createtime' => strtotime(date('Y-m-d H:i:s')),
                        );
                    }
                }



                    // $data[] = array(
                    //     'sma_timework_userid' => $id,
                    //     'sma_timework_dayofweek' => ,
                    //     'sma_timework_type' =>,
                    //     'sma_timework_duty' =>,
                    //     'sma_timework_createby'=>,
                    //     'sma_timework_createtime' =>,
                    // );

                    
            

        }

        if ($this->form_validation->run() === TRUE) {
            if($this->auth_model->updateTimework($id, $data)){
                $this->session->set_flashdata('message', lang('user_updated'));
                 redirect("auth/profile/" . $id.'#time_work');
            }else{
                $this->session->set_flashdata('error', lang('Cập nhật thất bại'));
                redirect("auth/profile/" . $id.'#time_work');
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function pay($id){
        $this->form_validation->set_rules('pay', lang("Lương"), 'required');
        if ($this->form_validation->run() === TRUE) {            
            
            $data = array(
                'sma_pay_pay' => str_replace(',', '', $this->input->post('pay')),
                'sma_pay_note' => $this->input->post('ponote'),
                'sma_pay_userid' => $id,
                'sma_pay_createby' => $this->session->userdata('user_id'),
                'sma_pay_createtime' => strtotime(date('Y-m-d H:i:s')),
            );

        }

        if ($this->form_validation->run() === TRUE) {
            if($this->auth_model->updatePay($id, $data)){
                $this->session->set_flashdata('message', lang('user_updated'));
                redirect("auth/profile/" . $id);
            }else{
                $this->session->set_flashdata('error', lang('Cập nhật thất bại'));
                redirect("auth/profile/" . $id);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    

    function time_out($id){
        $this->form_validation->set_rules('start_date', lang("Ngày bắt đầu"), 'required');
        $this->form_validation->set_rules('end_date', lang("Ngày kết thúc"), 'required');
        if ($this->form_validation->run() === TRUE) {
            // $unit_day = strtotime($this->sma->fsd($this->input->post('end_date'))) - strtotime($this->sma->fsd($this->input->post('start_date')));

            
            $time = array(
                'start_date' => $this->sma->fsd($this->input->post('start_date')),
                'end_date'   => $this->sma->fsd($this->input->post('end_date')),
                'type'   => $this->input->post('type'),
            );

            $totalDayWo = $this->sma->getDayStartToEnd($id,$time);

            $data = array(
                'sma_timeout_startdate' => strtotime($this->sma->fsd($this->input->post('start_date')).' 00:00:00'),
                'sma_timeout_enddate' => strtotime($this->sma->fsd($this->input->post('end_date')).' 23:59:59'),
                'sma_timeout_userid' => $id,
                'sma_timeout_createby' => $this->session->userdata('user_id'),
                'sma_timeout_createtime' => strtotime(date('Y-m-d H:i:s')),
                'sma_timeout_day' => $totalDayWo,
                'sma_timeout_month' => explode('/', $this->input->post('start_date'))[1],
                'sma_timeout_year' => explode('/', $this->input->post('start_date'))[2],
                'sma_timeout_type' => $this->input->post('type')
            );

        }

        if ($this->form_validation->run() === TRUE) {
            if($this->auth_model->updateTimeout($id, $data)){
                $this->session->set_flashdata('message', lang('user_updated'));
                redirect("auth/profile/" . $id.'#time_out');
            }else{
                $this->session->set_flashdata('error', lang('Cập nhật thất bại'));
                redirect("auth/profile/" . $id.'#time_out');
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function delete_timeout($idu,$id){
        if($this->auth_model->delete_timeout($id)){
            $this->session->set_flashdata('message', lang('user_updated'));
                redirect("auth/profile/" . $idu.'#time_out');
        }else{
            $this->session->set_flashdata('error', lang('Cập nhật thất bại'));
                redirect("auth/profile/" . $idu.'#time_out');
        }
    }

    function edit_user($id = NULL)
    {
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }
        $this->data['title'] = lang("edit_user");

        if (!$this->loggedIn || !$this->Owner && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $user = $this->ion_auth->user($id)->row();
        //$groups = $this->ion_auth->groups()->result_array();
        //$currentGroups = $this->ion_auth->get_users_groups($id)->result();
        if ($user->username != $this->input->post('username')) {
            $this->form_validation->set_rules('username', lang("username"), 'trim|is_unique[users.username]');
        }
        if ($user->email != $this->input->post('email')) {
            $this->form_validation->set_rules('email', lang("email"), 'trim|is_unique[users.email]');
        }

        $this->form_validation->set_rules('last_name', lang("Họ tên"), 'required');
        
        if ($this->form_validation->run() === TRUE) {
            if ($this->Owner) {
                if ($id == $this->session->userdata('user_id')) {
                    $data = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'company' => $this->input->post('company'),
                        'phone' => $this->input->post('phone'),
                        //'website' => $this->input->post('website'),
                        'gender' => $this->input->post('gender'),
                    );
                } elseif ($this->ion_auth->in_group('customer', $id) || $this->ion_auth->in_group('supplier', $id)) {
                    $data = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'company' => $this->input->post('company'),
                        'phone' => $this->input->post('phone'),
                        //'website' => $this->input->post('website'),
                        'gender' => $this->input->post('gender'),
                    );
                } else {
                    $data = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'company' => $this->input->post('company'),
                        'username' => $this->input->post('username'),
                        'email' => $this->input->post('email'),
                        'phone' => $this->input->post('phone'),
                        //'website' => $this->input->post('website'),
                        'gender' => $this->input->post('gender'),
                        'active' => $this->input->post('status'),
                        'group_id' => $this->input->post('group'),
                        'biller_id' => $this->input->post('biller') ? $this->input->post('biller') : NULL,
                        'warehouse_id' => $this->input->post('warehouse') ? $this->input->post('warehouse') : NULL,
                        'award_points' => $this->input->post('award_points'),
                    );
                }
                //$pgs = $this->auth_model->getAllUserProductGroups($id);
                //foreach($pgs as $pg) {
                //    $upg[] = array('user_id' => $id, 'product_group_id' => $this->input->post('group'.$pg->id), 'percent' => $this->input->post('percent'.$pg->id));
                //}
                //print_r($upg); die();
            } elseif ($this->Admin) {
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'phone' => $this->input->post('phone'),
                    //'website' => $this->input->post('website'),
                    'gender' => $this->input->post('gender'),
                    'active' => $this->input->post('status'),
                    'award_points' => $this->input->post('award_points'),
                );
            } else {
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'phone' => $this->input->post('phone'),
                    //'website' => $this->input->post('website'),
                    'gender' => $this->input->post('gender'),
                );
            }
            if ($this->Owner) {
                if ($this->input->post('password')) {
                    if (DEMO) {
                        $this->session->set_flashdata('warning', lang('disabled_in_demo'));
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                    $this->form_validation->set_rules('password', lang('edit_user_validation_password_label'), 'required|min_length[8]|max_length[25]|matches[password_confirm]');
                    $this->form_validation->set_rules('password_confirm', lang('edit_user_validation_password_confirm_label'), 'required');

                    $data['password'] = $this->input->post('password');
                }
            }
            //$this->sma->print_arrays($data);

        }
        if ($this->form_validation->run() === TRUE && $this->ion_auth->update($user->id, $data)) {
            $this->session->set_flashdata('message', lang('user_updated'));
            redirect("auth/profile/" . $id);
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }


    function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
            $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')
        ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function _render_page($view, $data = null, $render = false)
    {

        $this->viewdata = (empty($data)) ? $this->data : $data;
        $view_html = $this->load->view('header', $this->viewdata, $render);
        $view_html .= $this->load->view($view, $this->viewdata, $render);
        $view_html = $this->load->view('footer', $this->viewdata, $render);

        if (!$render)
            return $view_html;
    }

    /**
     * @param null $id
     */
    function update_avatar($id = NULL)
    {
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }

        if (!$this->ion_auth->logged_in() || !$this->Owner && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        //validate form input
        $this->form_validation->set_rules('avatar', lang("avatar"), 'trim');

        if ($this->form_validation->run() == true) {

            if ($_FILES['avatar']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/avatars';
                $config['allowed_types'] = 'gif|jpg|png';
                //$config['max_size'] = '500';
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['max_filename'] = 25;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('avatar')) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                $photo = $this->upload->file_name;

                $this->load->helper('file');
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'assets/uploads/avatars/' . $photo;
                $config['new_image'] = 'assets/uploads/avatars/thumbs/' . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 150;
                $config['height'] = 150;;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);

                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                $user = $this->ion_auth->user($id)->row();
            } else {
                $this->form_validation->set_rules('avatar', lang("avatar"), 'required');
            }
        }

        if ($this->form_validation->run() == true && $this->auth_model->updateAvatar($id, $photo)) {
            unlink('assets/uploads/avatars/' . $user->avatar);
            unlink('assets/uploads/avatars/thumbs/' . $user->avatar);
            $this->session->set_userdata('avatar', $photo);
            $this->session->set_flashdata('message', lang("avatar_updated"));
            redirect("auth/profile/" . $id);
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect("auth/profile/" . $id);
        }
    }

    function register()
    {
        $this->data['title'] = "Register";
        if (!$this->allow_reg) {
            $this->session->set_flashdata('error', lang('registration_is_disabled'));
            redirect("login");
        }
        //validate form input
        //$this->form_validation->set_message('is_unique', 'An account already registered with this email');
        $this->form_validation->set_rules('first_name', lang('first_name'), 'required');
        $this->form_validation->set_rules('last_name', lang('last_name'), 'required');
        $this->form_validation->set_rules('email', lang('email_address'), 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('phone', lang('phone'), 'required');
        $this->form_validation->set_rules('company', lang('company'), 'required');
        $this->form_validation->set_rules('password', lang('password'), 'required|min_length[8]|max_length[25]|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', lang('confirm_password'), 'required');
        $this->form_validation->set_rules('captcha', 'captcha', 'required|callback_captcha_check');

        if ($this->form_validation->run() == true) {
            list($username, $domain) = explode("@", $this->input->post('email'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone'),
            );
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data)) {

            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("login");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('error')));
            $this->data['groups'] = $this->ion_auth->groups()->result_array();

            $this->load->helper('captcha');
            $vals = array(
                'img_path' => './assets/captcha/',
                'img_url' => site_url() . 'assets/captcha/',
                'img_width' => 150,
                'img_height' => 34,
            );
            $cap = create_captcha($vals);
            $capdata = array(
                'captcha_time' => $cap['time'],
                'ip_address' => $this->input->ip_address(),
                'word' => $cap['word']
            );

            $query = $this->db->insert_string('captcha', $capdata);
            $this->db->query($query);
            $this->data['image'] = $cap['image'];
            $this->data['captcha'] = array('name' => 'captcha',
                'id' => 'captcha',
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => lang('type_captcha')
            );

            $this->data['first_name'] = array(
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['company'] = array(
                'name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('company'),
            );
            $this->data['phone'] = array(
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('phone'),
            );
            $this->data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'required' => 'required',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password_confirm'),
            );

            $this->load->view('auth/register', $this->data);
        }
    }

    function user_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('category', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('category') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->auth_model->delete_user($id);
                    }
                    $this->session->set_flashdata('message', lang("users_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('category') == 'export_excel' || $this->input->post('category') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('sales'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('first_name'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('last_name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('email'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('company'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('group'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('status'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $user = $this->site->getUser($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $user->first_name);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $user->last_name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $user->email);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $user->company);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $user->group);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $user->status);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'users_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('category') == 'export_pdf') {
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
                    if ($this->input->post('category') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_user_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function delete($id = NULL)
    {
        $this->sma->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->auth_model->delete_user($id)) {
            //echo lang("user_deleted");
            $this->session->set_flashdata('message', 'user_deleted');
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function productivity()
    {

        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $categories = $this->site->getAllCategories("id");
        $data['categories'] = $categories;

        $startDate = $this->input->get('start_date');
        $endDate = $this->input->get('end_date');
        $warehouse_id = $this->input->get('warehouseId');

        if(!isset($startDate)) {
            $startDate = date('01-m-Y');
        }

        if(!isset($endDate)) {
            $endDate = date('t-m-Y');
        }

        $startDate = strtotime($this->sma->fsd($startDate).' 00:00:00');
        $endDate = strtotime($this->sma->fsd($endDate).' 23:59:59');

        $sql = "select id, concat(FIRST_name,' ', last_name) as name, p.categoryId, IF(id = p.staffId, p.count, 0) as dem"
            . " from sma_users "
            . " full join ("
            . " select sma_categories.id as categoryId, sma_books.sma_books_staffid as staffId, count(sma_books.sma_books_staffid) as count "
            . " from sma_categories left join sma_books on sma_categories.id = sma_books.sma_books_categoryparentid AND sma_books_status = 1 "
            . " AND sma_books_createtime <=".$endDate ." AND sma_books_createtime >=".$startDate ." group by categoryId) as p "
            . " where group_id=5";
        if(isset($warehouse_id)){
            $sql = $sql." AND warehouse_id = ".$warehouse_id;
        };
        $sql = $sql . " group by id, p.categoryId"
            . " order by id, p.categoryId";
        $dsNangsuat = $this->db->query($sql)->result();

        $staffId = 0;
        $data['nangsuat'] = [];
        $tmp = [];
        for ($i = 0; $i < count($dsNangsuat); $i++) {
            $nangsuat = $dsNangsuat[$i];

            if ($nangsuat->id != $staffId) {
                $tmp = [$nangsuat->name];
                $staffId = $nangsuat->id;
            }
            array_push($tmp, (int)$nangsuat->dem);

            if ((count($tmp) == (count($categories)+1) || ($i == count($dsNangsuat)-1)) ) {
                array_push($data['nangsuat'], $tmp);

            }
        }

        $this->data['categories'] = $categories;
        $this->data['nangsuat'] = $data['nangsuat'];
        $this->data['warehouses'] = $this->site->getAllWarehouses(null);

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('users')));
        $meta = array('page_title' => lang('users'), 'bc' => $bc);
        $this->page_construct('auth/productivity_nv', $meta, $this->data);

    }

    function sendMail($mailto="",$content="")
    {
        $config = $this->config->config['email'];
        $emailFrom = $this->config->config['emailFrom'];
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from($emailFrom); // change it to yours
        $this->email->to($mailto);// change it to yours
        $this->email->subject('Quỳnh Queen Spa!');
        $this->email->message($content);
        if($this->email->send())
        {
            //echo 'Email sent.';
        }
        else
        {
            show_error($this->email->print_debugger());
        }

    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
