<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Companies_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllBillerCompanies()
    {
        $q = $this->db->get_where('companies', array('group_name' => 'biller'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllCustomerCompanies()
    {
        $q = $this->db->get_where('companies', array('group_name' => 'customer'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllSupplierCompanies()
    {
        $q = $this->db->get_where('companies', array('group_name' => 'supplier'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllCustomerGroups()
    {
        $q = $this->db->get('customer_groups');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCompanyUsers($company_id)
    {
        $q = $this->db->get_where('users', array('company_id' => $company_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }


    public function completeBook($id,$data){
        if($this->db->update('sma_books',$data,array('sma_books.sma_books_id' => $id))){
            return true;
        }

        return false;
    }


    public function changeBook($id,$data){
        $book = $this->site->getBookByID($id);
        $data_new = array(
            'sma_history_book.sma_history_book_bookid' => $id,
            'sma_history_book.sma_history_book_staffid' =>  $data['staff'],
            'sma_history_book.sma_history_book_status' => 'appect' ,
            'sma_history_book.sma_history_book_statusStaff' => 'complete' );

        if($this->db->update('sma_history_book',array('sma_history_book.sma_history_book_statusStaff' => 'complete'),array('sma_history_book.sma_history_book_bookid'=> $id ,'sma_history_book.sma_history_book_staffid'=> $book->sma_books_staffid )) && $this->db->insert('sma_history_book',$data_new)  && $this->db->update('sma_books',array('sma_books.sma_books_staffid' => $data['staff']),array('sma_books.sma_books_id'=>$id))){

            $this->sma->addNotice(array(
                'descriptiontitle' => 'Book lịch',
                'descriptionname' => 'Khách hàng '.$data['customername'].' đặt lịch '.$data['category_parent_name'].' thời gian '.$this->sma->ihrld($data['starttime']),
                'staffid' => $data['staff'],
                'staffname' => $staff_name,
                'type_id' => $id,
                'type' => 'book',
                'date' => $data['starttime'],
                'slug' => 'customers/book/'.$idb.'/view',
            ));


            return true;
        }
        return false;
    }

    public function updateBook($id,$data){
        $customerc = 0;
        if($data['customerid']){
            if($this->db->update('sma_companies',array('note'=>$data['note']),array('id'=>$data['customerid']))){
                $customerc++;
            }
            $customerid = $data['customerid'];
        }else{
            $customer = array(
                'group_id' => 3,
                'group_name' => 'customer',
                'customer_group_id' => 1,
                'customer_group_name' => 'Khách hàng đang hoạt động',
                'name' => $data['customername'],
                'phone' => $data['phonecustomer'],
                'note'=>$data['note'],
            );
            if($this->db->insert('sma_companies',$customer)){
                $customerc++;
                $idc = $this->db->insert_id();
                $customerid = $idc;
            }
        }

        if($customerc != 0){
            $book = array(
                'sma_books_customername' => $data['customername'],
                'sma_books_customerid' => $customerid,
                'sma_books_staffid'     => $data['staff'],
                'sma_books_starttime'     => $data['starttime'],
                'sma_books_endtime'     => $data['endtime'],
                'sma_books_categoryparentid'     => $data['category_parent'],
                'sma_books_categoryparentname'     => $data['category_parent_name'],
                'sma_books_categorychildid'     => $data['category_child'],
                'sma_books_categorychildname'     => $data['category_child_name'],
                'sma_books_createby'              => $this->session->userdata('user_id'),
                'sma_books_createtime'              => strtotime(date('Y-m-d H:i:s')),                
                'sma_books_status'              => 0,    
                'sma_books_price'               => 0,   
                'sma_books_warehouseid'         => $data['warehouseid'],
            );

            if($this->db->update('sma_books',$book,$id)){
                $typebook =  array('book', 'book_asgin');
                $this->db->where_in('sma_notice.sma_notice_type',$typebook);
                $this->db->where('sma_notice.sma_notice_typeid',$id);
                $this->db->delete('sma_notice');

                if($data['staff_asgin']){
                   foreach ($data['staff_asgin'] as $key => $value) {
                        $staffass = $this->site->getStaffAssByIDBookStaff($id,$value);
                        if($staffass){
                            $arrS[] = $staffass->id;
                        }
                        else{
                            $this->db->insert('sma_staffasgin', array('sma_staffasgin_createby' => $this->session->userdata('user_id'), 'sma_staffasgin_createtime' => strtotime(date('Y-m-d H:i:s')) ,'sma_staffasgin_idbook' => $id, 'sma_staffasgin_staffid' => $value));
                            $arrS[] = $this->db->insert_id();
                        }
                    }  

                    $this->db->where_not_in('sma_staffasgin.sma_staffasgin_id',$arrS);
                    $this->db->where('sma_staffasgin.sma_staffasgin_idbook',$id);
                    $this->db->delete('sma_staffasgin');

                    $this->sma->addNotice(array(
                        'descriptiontitle' => 'Theo dõi book lịch',
                        'descriptionname' => 'Khách hàng '.$data['customername'].' đặt lịch '.$data['category_parent_name'].' thời gian '.$this->sma->ihrld($data['starttime']),
                        'staffid' => $value,
                        'type_id' => $idb,
                        'type' => 'book_asgin',
                        'staffname' => $staff_name,
                        'date' => $data['starttime'],
                        'slug' => 'customers/book/'.$idb.'/view',                            
                    ));




                }else{
                    $this->db->delete('sma_staffasgin',array('sma_staffasgin.sma_staffasgin_idbook'=>$id));
                }

                if($data['staff']){
                    $staff_name = $this->site->getUser($data['staff'])->last_name;
                    $this->sma->addNotice(array(
                        'descriptiontitle' => 'Book lịch',
                        'descriptionname' => 'Khách hàng '.$data['customername'].' đặt lịch '.$data['category_parent_name'].' thời gian '.$this->sma->ihrld($data['starttime']),
                        'staffid' => $data['staff'],
                        'staffname' => $staff_name,
                        'type_id' => $idb,
                        'type' => 'book',
                        'date' => $data['starttime'],
                        'slug' => 'customers/book/'.$idb.'/view',
                        
                    ));
                }
                
            }  

            return true;          
        }
        return false;
        // $this->getCompanyByID()
    }

    public function addBook($data){

        
        $customerc = 0;
        if($data['customerid']){
            if($this->db->update('sma_companies',array('note'=>$data['note']),array('id'=>$data['customerid']))){
                $customerc++;
            }
            $customerid = $data['customerid'];
        }else{
            $customer = array(
                'group_id' => 3,
                'group_name' => 'customer',
                'customer_group_id' => 1,
                'customer_group_name' => 'Khách hàng đang hoạt động',
                'name' => $data['customername'],
                'phone' => $data['phonecustomer'],
                'note'=>$data['note'],
            );
            if($this->db->insert('sma_companies',$customer)){
                $customerc++;
                $idc = $this->db->insert_id();
                $customerid = $idc;
            }
        }

        if($customerc != 0){
            $book = array(
                'sma_books_customername' => $data['customername'],
                'sma_books_customerid' => $customerid,
                'sma_books_staffid' => $data['staff'],
                'sma_books_starttime' => $data['starttime'],
                'sma_books_endtime' => $data['endtime'],
                'sma_books_categoryparentid' => $data['category_parent'],
                'sma_books_categoryparentname' => $data['category_parent_name'],
                'sma_books_categorychildid' => $data['category_child'],
                'sma_books_categorychildname' => $data['category_child_name'],
                'sma_books_createby' => $this->session->userdata('user_id'),
                'sma_books_createtime' => strtotime(date('Y-m-d H:i:s')),
                'sma_books_price' => $data['sma_books_price'],
                'sma_books_status' => 0,
                'sma_books_warehouseid'=> $data['warehouseid'],
            );
            if($this->db->insert('sma_books',$book)){
                $idb = $this->db->insert_id();
                $history_books = array(
                    'sma_history_book_bookid' => $idb,
                    'sma_history_book_staffid' => $data['staff'],
                    'sma_history_book_status'  => 'appect',
                    'sma_history_book_statusStaff'  => 'complete',
                );

                $this->db->insert('sma_history_book',$history_books);

                if($data['staff_asgin']){
                    foreach ($data['staff_asgin'] as $key => $value) {
                        $staff_name = $this->site->getUser($value)->last_name;
                        $this->sma->addNotice(array(
                            'descriptiontitle' => 'Theo dõi book lịch',
                            'descriptionname' => 'Khách hàng '.$data['customername'].' đặt lịch '.$data['category_parent_name'].' thời gian '.$this->sma->ihrld($data['starttime']),
                            'staffid' => $value,
                            'type_id' => $idb,
                            'type' => 'book_asgin',
                            'staffname' => $staff_name,
                            'date' => $data['starttime'],
                            'slug' => 'customers/book/'.$idb.'/view',
                            
                        ));

                        $this->db->insert('sma_staffasgin', array('sma_staffasgin_createby' => $this->session->userdata('user_id'), 'sma_staffasgin_createtime' => strtotime(date('Y-m-d H:i:s')) ,'sma_staffasgin_idbook' => $idb, 'sma_staffasgin_staffid' => $value));
                            
                    }  
                }

                if($data['staff']){
                    $staff_name = $this->site->getUser($data['staff'])->last_name;
                    $this->sma->addNotice(array(
                        'descriptiontitle' => 'Book lịch',
                        'descriptionname' => 'Khách hàng '.$data['customername'].' đặt lịch '.$data['category_parent_name'].' thời gian '.$this->sma->ihrld($data['starttime']),
                        'staffid' => $data['staff'],
                        'staffname' => $staff_name,
                        'type_id' => $idb,
                        'type' => 'book',
                        'date' => $data['starttime'],
                        'slug' => 'customers/book/'.$idb.'/view',
                        
                    ));
                }
                return true;          
                
            }  

        }
        return false;
        // $this->getCompanyByID()
    }


    public function deleteBook($id){
        if($this->db->delete('sma_history_book',array('sma_history_book_bookid'=>$id)) && $this->db->delete('sma_staffasgin',array('sma_staffasgin_idbook'=>$id)) && $this->db->delete('sma_books',array('sma_books_id'=>$id)) ){
            return true;
        }
        return false;
    }

   


    public function cancelBook($id){
        $data = $this->site->getBookByID($id);


        if($this->db->update('sma_books',array('sma_books.sma_books_status'=>2),array('sma_books.sma_books_id'=> $id) ) ) {
                $staffass = $this->site->getStaffAssByIDBook($id);
                $username = $this->site->getUser($data->sma_books_staffid)->last_name;
                foreach ($staffass  as $key => $value) {
                    $username1 = $this->site->getUser($value->sma_books_staffid)->last_name;
                     $this->sma->addNotice(array(
                        'descriptiontitle' => 'Hủy book lịch',
                        'descriptionname' => 'Khách hàng '.$data->sma_books_customername.' đặt lịch '.$data->sma_books_categoryparentname.' thời gian '.$this->sma->ihrld($data->sma_books_starttime),
                        'staffid' => $value->sma_staffasgin_staffid,
                        'type_id' => $id,
                        'type' => 'book_cancel',
                        'staffname' => $username1,
                        'date' => $data->sma_books_starttime,
                        'slug' => 'customers/modal_book/'.$id,
                    ));
                }
                $this->sma->addNotice(array(
                    'descriptiontitle' => 'Hủy book lịch',
                    'descriptionname' => 'Khách hàng '.$data->sma_books_customername.' đặt lịch '.$data->sma_books_categoryparentname.' thời gian '.$this->sma->ihrld($data->sma_books_starttime),
                    'staffid' => $data->sma_books_staffid,
                    'type_id' => $id,
                    'type' => 'book_cancel',
                    'staffname' => $username,
                    'date' => $data->sma_books_starttime,
                    'slug' => 'customers/modal_book/'.$id,
                ));
            return true;
        }

        return false;
    }

    public function getCompanyByID($id)
    {
        $q = $this->db->get_where('companies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getCompanyByEmail($email)
    {
        $q = $this->db->get_where('companies', array('email' => $email), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getCompanyByPhone($phone)
    {
        $q = $this->db->get_where('companies', array('phone' => $phone), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addCompany($data = array())
    {
        if ($this->db->insert('companies', $data)) {
            $cid = $this->db->insert_id();
            return $cid;
        }
        return false;
    }

    public function updateCompany($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('companies', $data)) {
            return true;
        }
        return false;
    }

    public function addCompanies($data = array())
    {
        if ($this->db->insert_batch('companies', $data)) {
            return true;
        }
        return false;
    }
    
    public function deleteCustomer($id)
    {
        if ($this->getCustomerSales($id)) {
            return false;
        }
        if ($this->db->delete('companies', array('id' => $id, 'group_name' => 'customer')) && $this->db->delete('users', array('company_id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteSupplier($id)
    {
        if ($this->getSupplierPurchases($id)) {
            return false;
        }
        if ($this->db->delete('companies', array('id' => $id, 'group_name' => 'supplier')) && $this->db->delete('users', array('company_id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteBiller($id)
    {
        if ($this->getBillerSales($id)) {
            return false;
        }
        if ($this->db->delete('companies', array('id' => $id, 'group_name' => 'biller'))) {
            return true;
        }
        return FALSE;
    }

    public function getBillerSuggestions($term, $limit = 10)
    {
        $this->db->select("id, company as text");
        $this->db->where(" (id LIKE '%" . $term . "%' OR name LIKE '%" . $term . "%' OR company LIKE '%" . $term . "%') ");
        $q = $this->db->get_where('companies', array('group_name' => 'biller'), $limit);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getCustomerSuggestions($term, $limit = 10)
    {
        $this->db->select("id, CONCAT(company, ' (', name, ')') as text", FALSE);
        $this->db->where(" (id LIKE '%" . $term . "%' OR name LIKE '%" . $term . "%' OR company LIKE '%" . $term . "%' OR email LIKE '%" . $term . "%' OR phone LIKE '%" . $term . "%') ");
        $q = $this->db->get_where('companies', array('group_name' => 'customer'), $limit);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getSupplierSuggestions($term, $limit = 10)
    {
        $this->db->select("id, CONCAT(company, ' (', name, ')') as text", FALSE);
        $this->db->where(" (id LIKE '%" . $term . "%' OR name LIKE '%" . $term . "%' OR company LIKE '%" . $term . "%' OR email LIKE '%" . $term . "%' OR phone LIKE '%" . $term . "%') ");
        $q = $this->db->get_where('companies', array('group_name' => 'supplier'), $limit);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getCustomerSales($id)
    {
        $this->db->where('customer_id', $id)->from('sales');
        return $this->db->count_all_results();
    }

    public function getBillerSales($id)
    {
        $this->db->where('biller_id', $id)->from('sales');
        return $this->db->count_all_results();
    }

    public function getSupplierPurchases($id)
    {
        $this->db->where('supplier_id', $id)->from('purchases');
        return $this->db->count_all_results();
    }

}
