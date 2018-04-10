<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Statistics_model extends CI_Model
{
    protected $_table = 'sma_pay';

    public function __construct()
    {
        parent::__construct();
    }

    public function getList()
    {
        $this->db->select('sma_pay_id, sma_pay_pay, sma_pay_createtime, sma_pay_type, sma_pay_danhmuc_index, sma_pay_note');
        return $this->db->get($this->_table)->result_array();
    }

    public function totalPay()
    {
        $total = array();

        $query = $this->db->select('SUM(sma_pay_pay) AS `total`', FALSE)
            ->from($this->_table)
            ->get();
        $row = $query->row_array();
        $total['total'] = $row['total'];

        $query = $this->db->select('SUM(sma_pay_pay) AS `total_cpcd`', FALSE)
            ->from($this->_table)
            ->where('sma_pay_type', 0)
            ->get();
        $row = $query->row_array();
        $total['total_cpcd'] = $row['total_cpcd'];

        $query = $this->db->select('SUM(sma_pay_pay) AS `total_cpps`', FALSE)
            ->from($this->_table)
            ->where('sma_pay_type', 1)
            ->get();
        $row = $query->row_array();
        $total['total_cpps'] = $row['total_cpps'];

        return $total;

    }

    public function insertPay($data_insert)
    {
        $this->db->insert($this->_table, $data_insert);
    }

    public function updatePay($data_update, $pay_id)
    {
        $this->db->where("sma_pay_id", $pay_id);
        $this->db->update($this->_table, $data_update);
    }

    public function deletePay($pay_id)
    {
        $this->db->where("sma_pay_id", $pay_id);
        $this->db->delete($this->_table);
    }
}