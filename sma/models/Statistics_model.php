<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Statistics_model extends CI_Model
{
    protected $_table = 'sma_pay';

    public function __construct()
    {
        parent::__construct();
    }

    public function getList($date_from, $date_to)
    {
        $date_from = strtotime(str_replace('/', '-', $date_from));
        $date_to = strtotime(str_replace('/', '-', $date_to));

        $this->db->select('sma_pay_id, sma_pay_pay, sma_pay_createtime, sma_pay_type, sma_pay_danhmuc_index, sma_pay_note');
        $this->db->where("sma_pay_createtime >=", $date_from);
        $this->db->where("sma_pay_createtime <=", $date_to);
        $this->db->where("sma_pay_userid", NULL);

        return $this->db->get($this->_table)->result_array();
    }

    public function totalPay($date_from, $date_to)
    {
        $date_from = strtotime(str_replace('/', '-', $date_from));
        $date_to = strtotime(str_replace('/', '-', $date_to));

        $total = array();

        $query = $this->db->select('SUM(sma_pay_pay) AS `total`', FALSE)
            ->from($this->_table)
            ->where("sma_pay_createtime >=", $date_from)
            ->where("sma_pay_createtime <=", $date_to)
            ->where("sma_pay_userid", NULL)
            ->get();
        $row = $query->row_array();
        $total['total'] = $row['total'];

        $query = $this->db->select('SUM(sma_pay_pay) AS `total_cpcd`', FALSE)
            ->from($this->_table)
            ->where("sma_pay_createtime >=", $date_from)
            ->where("sma_pay_createtime <=", $date_to)
            ->where('sma_pay_type', 0)
            ->where("sma_pay_userid", NULL)
            ->get();
        $row = $query->row_array();
        $total['total_cpcd'] = $row['total_cpcd'];

        $query = $this->db->select('SUM(sma_pay_pay) AS `total_cpps`', FALSE)
            ->from($this->_table)
            ->where("sma_pay_createtime >=", $date_from)
            ->where("sma_pay_createtime <=", $date_to)
            ->where('sma_pay_type', 1)
            ->where("sma_pay_userid", NULL)
            ->get();
        $row = $query->row_array();
        $total['total_cpps'] = $row['total_cpps'];


        //Doanh thu - Chi
        $query = $this->db->select('SUM(sma_pay_pay) AS `total_luong`', FALSE)
            ->from($this->_table)
            ->where("sma_pay_createtime >=", $date_from)
            ->where("sma_pay_createtime <=", $date_to)
            //->where('(sma_pay_userid > 0 OR sma_pay_userid != 0)', NULL, FALSE)
            ->where("sma_pay_userid >", 0)
            ->get();
        $row = $query->row_array();
        $total['total_luong'] = $row['total_luong'];

        $query = $this->db->select('SUM(total) AS `total_cpvl`', FALSE)
            ->from('sma_purchases')
            ->where("date >=", date('Y-m-d', $date_from))
            ->where("date <=", date('Y-m-d', $date_to))
            //->where('sma_pay_type', 0)
            ->where("status", 'received')
            ->get();
        $row = $query->row_array();
        $total['total_cpvl'] = $row['total_cpvl'];

        //Doanh thu - Lãi
        $query = $this->db->select('SUM(sma_books_price) AS `total_dichvu`', FALSE)
            ->from('sma_books')
            ->where("sma_books_endtime1 >=", $date_from)
            ->where("sma_books_endtime1 <=", $date_to)
            ->where("sma_books_status", 1)
            ->get();
        $row = $query->row_array();
        $total['total_dichvu'] = $row['total_dichvu'];

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

    public function getList_Luong($id, $month, $year)
    {
        //$id = 9;$month = "05";$year = "2018";
        //echo date('Y-m-d', 1501952400);

        $User = $this->site->getUserByID($id);
        $UserMonth = explode('-', $this->sma->fsd($this->sma->ihrsd($User->start_date)));

        if ($UserMonth[1] == $month && $UserMonth[0] == $year) {
            $firstDate = mktime(0, 0, 0, $month, $UserMonth[2], $year);
        } else {
            $firstDate = mktime(0, 0, 0, $month, 1, $year);
        }
        $now = time();
        $time = array(
            'start_date' => date('Y-m-d', $firstDate),
            'end_date' => ($month == date('m') && $year == date('Y')) ? date('Y-m-d', $now) : date('Y-m-t', $firstDate),
        );

        //$totalDayWo = (($year == $UserMonth[0] && $month >= $UserMonth[1]) || ($year > $UserMonth[0]) && $month <= date('m')) ? $this->sma->getDayStartToEnd($id, $time) : 0;
        if ($year == $UserMonth[0]) {
            $totalDayWo = ($month >= $UserMonth[1]) ? (($year <= date('Y') && $month <= date('m')) ? $this->sma->getDayStartToEnd($id, $time) : 0) : 0;
        } elseif ($year < $UserMonth[0]) {
            $totalDayWo = 0;
        } elseif ($year > $UserMonth[0]) {
            $totalDayWo = ($year <= date('Y') && $month <= date('m')) ? $this->sma->getDayStartToEnd($id, $time) : 0;
        }
        $totalDayOu = 0;
        $totalPayOut = 0;
        $dayOnMonth = date("t");
        $totalBoun = 0;
        $totalFine = 0;
        $totalPay = 0;
        $totayPayOut = 0;

        foreach ($this->site->getAllTimeoutByIDUser($id, $month, $year) as $key => $value) {
            $totalDayOu += $value['sma_timeout_day'];
        }

        foreach ($this->site->getPayByIDUser($id, null, null) as $key => $value) {
            $time = array(
                'start_date' => ($value['sma_pay_startdate'] > $firstDate) ? date('Y-m-d', $value['sma_pay_startdate']) : date('Y-m-d', $firstDate),

            );
            if ($value['sma_pay_enddate'] == 1) {
                $time['end_date'] = date('Y-m-d', $value['sma_pay_enddate']);
            } else {
                //$time['end_date'] = date('Y-m-d', $now);
                $time['end_date'] = ($month == date('m') && $year == date('Y')) ? date('Y-m-d', $now) : date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));
            }

            $totaDayPay = (strtotime($time['start_date']) <= $now) ? $this->sma->getDayStartToEnd($id, $time) : 0;

            if ($totalDayOu <= 2) {
                $totalPay += ($value['sma_pay_pay'] / 30) * $totaDayPay; //hktcustom
            } else {
                $totalPay += ($value['sma_pay_pay'] / 30) * $totaDayPay - ($value['sma_pay_pay'] / 30) * $totalDayOu; //hktcustom
            }
        }

        $LastPay = $this->site->getPayByIDUser($id, 1, 'DESC');

        if ($totalDayWo > $this->Settings->then_day) {
            //$totalPay +=  ($LastPay[0]['sma_pay_pay'] * ($this->Settings->pay_multiplication-1) )  * ($totalDayWo - $this->Settings->then_day);
        }

        $Otex = $this->site->getOtexByIDUser($id, $month, $year);
        $totalOtex = $Otex->sma_otex_pay;

        $dateO = [];

        foreach ($dateO as $key => $value) {
            $totalPayOut += $value['total'];
        }

        foreach ($this->site->getBounsByIDM($id, $month, $year) as $key => $value) {
            $totalBoun += $value['sma_bouns_bouns'];
        }

        foreach ($this->site->getFinesByIDM($id, $month, $year) as $key => $value) {
            $totalFine += $value['sma_fine_fine'];
        }

        $nowTime = strtotime(date('Y-m-d'));

        $timeA = array(
            'start_date' => date('Y-m-d', $nowTime),
            'end_date' => date('Y-m-d', $nowTime),
        );

        $active = $this->sma->getDayStatusStartToEnd($id, null, null);
        if ($active['status'] == 1) {
            $status = 'Đang hoạt động';
        } else {
            $status = 'Không hoạt động';
        }

        //book lịch
        $this->db->select('sma_books_price, sma_books_endtime1');
        $this->db->where('sma_books_staffid', $id);
        $this->db->where('sma_books_status', 1);
        $re_book_byid = $this->db->get('sma_books')->result_array();
        $book = 0;
        foreach ($re_book_byid as $book_price) {
            $endtime1 = $book_price['sma_books_endtime1'];
            $endtime1_arr = explode('-', date('Y-m-d', $endtime1));
            if ($year == $endtime1_arr[0] && $month == $endtime1_arr[1]) {
                $book += (int)$book_price['sma_books_price'];
            }
        }

        //doanh thu
        $doanhthu = 0;

        $arr = array(
            'totalDayWo' => $totalDayWo,
            'month' => date('m'),
            'totalDayOu' => $totalDayOu,
            'totalDayOnMonth' => $dayOnMonth,
            'totalBoun' => $totalBoun * 1,
            'totalFine' => $totalFine * 1,
            'totalOtex' => $totalOtex * 1,
            'totalPay' => $totalPay * 1,
            'totalPayOut' => $totalPayOut,
            'totalAll' => ($totalPay * 1) + ($totalBoun * 1) - ($totalFine * 1) + ($totalOtex * 1) - ($totalPayOut * 1),
            'status' => $active['status'],
            'statusmsg' => $status,
            'class' => $active['class'],
            'busy' => 0,
            'book' => $book,
            'doanhthu' => $book,
        );
        return $arr;
    }

    public function get_Luong($id, $date_from, $date_to)
    {
        $date_from = strtotime(str_replace('/', '-', $date_from));
        $date_to = strtotime(str_replace('/', '-', $date_to));

        if ($date_from > $date_to) return FALSE;

        $month_from = date('m', $date_from);
        $month_to = date('m', $date_to);
        $year_from = date('Y', $date_from);
        $year_to = date('Y', $date_to);

        $User = $this->site->getUserByID($id);
        $UserMonth = explode('-', $this->sma->fsd($this->sma->ihrsd($User->start_date)));

        if ($UserMonth[0] == $year_from && $UserMonth[1] == $month_from) {
            $firstDate = mktime(0, 0, 0, $month_from, $UserMonth[2], $year_from);
        } else {
            $firstDate = mktime(0, 0, 0, $month_from, date('d', $date_from), $year_from);
        }
        $now = time();
        $time = array(
            'start_date' => date('Y-m-d', $firstDate),
            'end_date' => ($month_to == date('m') && $year_to == date('Y')) ? date('Y-m-d', $now) : date('Y-m-d', $date_to),
        );

        if ($date_from > $now) {
            $totalDayWo = 0;
        } else {
            if ($date_to > $now) $time['end_date'] = date('Y-m-d', $now);
            $totalDayWo = $this->sma->getDayStartToEnd($id, $time);
        }

        $totalDayOu = array();
        $totalPayOut = 0;
        $dayOnMonth = date("t");
        $totalBoun = 0;
        $totalFine = 0;
        $totalPay = 0;
        $totayPayOut = 0;

        if ($year_from == $year_to) {
            for ($i_m = $month_from; $i_m <= $month_to; $i_m++) {
                foreach ($this->site->getAllTimeoutByIDUser($id, $i_m, $year_from) as $key => $value) {
                    $totalDayOu[$i_m . $year_from] += $value['sma_timeout_day'];
                }
            }
        } else {
            for ($i_y = $year_from; $i_y <= $year_to; $i_y++) {
                if ($i_y == $year_from) {
                    for ($i_m = $month_from; $i_m <= 12; $i_m++) {
                        foreach ($this->site->getAllTimeoutByIDUser($id, $i_m, $i_y) as $key => $value) {
                            $totalDayOu[$i_m . $i_y] += $value['sma_timeout_day'];
                        }
                    }
                } elseif ($i_y == $year_to) {
                    for ($i_m = 1; $i_m <= $month_to; $i_m++) {
                        foreach ($this->site->getAllTimeoutByIDUser($id, $i_m, $i_y) as $key => $value) {
                            $totalDayOu[$i_m . $i_y] += $value['sma_timeout_day'];
                        }
                    }
                } else {
                    for ($i_m = 1; $i_m <= 12; $i_m++) {
                        foreach ($this->site->getAllTimeoutByIDUser($id, $i_m, $i_y) as $key => $value) {
                            $totalDayOu[$i_m . $i_y] += $value['sma_timeout_day'];
                        }
                    }
                }
            }
        }
        //echo '<pre>';print_r($totalDayOu);echo '</pre>';

    }
}
