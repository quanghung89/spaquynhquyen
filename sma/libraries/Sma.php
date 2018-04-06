<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *  ==============================================================================
 *  Author	: Mian Saleem
 *  Email	: saleem@tecdiary.com
 *  For		: Stock Manager Advance
 *  Web		: http://tecdiary.com
 *  ==============================================================================
 */

class Sma
{

    public function __construct()
    {

    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    private function _rglobRead($source, &$array = array())
    {
        if (!$source || trim($source) == "") {
            $source = ".";
        }
        foreach ((array)glob($source . "/*/") as $key => $value) {
            $this->_rglobRead(str_replace("//", "/", $value), $array);
        }
        $hidden_files = glob($source . ".*") AND $htaccess = preg_grep('/\.htaccess$/', $hidden_files);
        $files = array_merge(glob($source . "*.*"), $htaccess);
        foreach ($files as $key => $value) {
            $array[] = str_replace("//", "/", $value);
        }
    }

    private function _zip($array, $part, $destination, $output_name = 'sma')
    {
        $zip = new ZipArchive;
        @mkdir($destination, 0777, true);

        if ($zip->open(str_replace("//", "/", "{$destination}/{$output_name}" . ($part ? '_p' . $part : '') . ".zip"), ZipArchive::CREATE)) {
            foreach ((array)$array as $key => $value) {
                $zip->addFile($value, str_replace(array("../", "./"), NULL, $value));
            }
            $zip->close();
        }
    }

    public function formatMoney($number, $currency = '')
    {
        if($this->Settings->sac) {
            return $currency . $this->formatSAC($this->formatDecimal($number));
        }
        $decimals = $this->Settings->decimals;
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return $currency . number_format($number, $decimals, $ds, $ts);
    }

    public function formatNumber($number, $decimals = NULL)
    {
        if($this->Settings->sac) {
            return $this->formatSAC($this->formatDecimal($number));
        }
        if (!$decimals) {
            $decimals = $this->Settings->decimals;
        }
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return number_format($number, $decimals, $ds, $ts);
    }

    public function formatDecimal($number, $decimals = NULL)
    {
        if ( ! is_numeric($number)) {
            return NULL;
        }
        if (!$decimals) {
            $decimals = $this->Settings->decimals;
        }
        return number_format($number, $decimals, '.', '');
    }

    public function checkPermissionsModelView($permiss = true, $js = true, $atrrUrl = NULL, $msg = NULL)
    {
        
        if ($permiss == false) {
            
            $this->session->set_flashdata('warning', lang($msg));
            if ($js) {
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] . $atrrUrl : site_url('welcome')) . "'; location.reload();}, 10); </script>");
            } else {
                
                redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
            }
        }
    }

    public function clear_tags($str)
    {
        return htmlentities(
            strip_tags($str,
                '<span><div><a><br><p><b><i><u><img><blockquote><small><ul><ol><li><hr><big><pre><code><strong><em><table><tr><td><th><tbody><thead><tfoot><h3><h4><h5><h6>'
            ),
            ENT_QUOTES | ENT_XHTML | ENT_HTML5,
            'UTF-8'
        );
    }

    public function decode_html($str)
    {
        return html_entity_decode($str, ENT_QUOTES | ENT_XHTML | ENT_HTML5, 'UTF-8');
    }

    public function roundMoney($num, $nearest = 0.05)
    {
        return round($num * (1 / $nearest)) * $nearest;
    }

    public function roundNumber($number, $toref = NULL)
    {
        switch($toref) {
            case 1:
                $rn = round($number * 20)/20;
                break;
            case 2:
                $rn = round($number * 2)/2;
                break;
            case 3:
                $rn = round($number);
                break;
            case 4:
                $rn = ceil($number);
                break;
            default:
                $rn = $number;
        }
        return $rn;
    }

    public function unset_data($ud)
    {
        if ($this->session->userdata($ud)) {
            $this->session->unset_userdata($ud);
            return true;
        }
        return FALSE;
    }

    public function hrsd($sdate)
    {
        if ($sdate) {
            return date($this->dateFormats['php_sdate'], strtotime($sdate));
        } else {
            return '0000-00-00';
        }
    }

    public function ihrsd($sdate)
    {
        if ($sdate) {
            return $this->hrsd(date('Y-m-d',$sdate));
        } 

        return false;
    }

     public function ihrld($ldate)
    {
        if ($ldate) {
            return $this->hrld(date('Y-m-d H:i:s',$ldate));
        } 

        return false;
    }

    public function hrld($ldate)
    {
        if ($ldate) {
            return date($this->dateFormats['php_ldate'], strtotime($ldate));
        } else {
            return '0000-00-00 00:00:00';
        }
    }

    function getDayStartToEnd($id,$data){

        $countM = 0;
        $countT = 0;
        $countW = 0;
        $countTh = 0;
        $countF = 0;
        $countSa = 0;
        $countSu = 0;
        
        $firstDate = strtotime($data['start_date']);
        $lastDate = strtotime($data['end_date']);
        for ($i = $firstDate; $i <= $lastDate; $i = $i + 24*3600) {
            if (date("D", $i) == "Mon"){
                $countM ++;
            }
                      

           if(date("D", $i) == "Tue"){
                $countT++;
           }

           if(date("D", $i) == "Wed"){
                $countW++;
           }

           if(date("D", $i) == "Thu"){
                $countTh++;
           }

           if(date("D", $i) == "Fri"){
                $countF++;
           }

           if(date("D", $i) == "Sat"){
                $countSa++;
           }

           if(date("D", $i) == "Sun"){
                $countSu++;
           }
        }

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

        $dayM = 0;
        if($a[0]->monday && $a[1]->monday){
            $dayM++;
        }else{
            if($a[0]->monday){
                $dayM++;
            }if($a[1]->monday){
                $dayM++;
            }
        }

        $totalDM = $dayM * $countM;

        $dayT = 0;
        if($a[0]->tuesday && $a[1]->tuesday){
            $dayT++;
        }else{
            if($a[0]->tuesday){
                $dayT++;
            }if($a[1]->tuesday){
                $dayT++;
            }
        }

        $totalDT = $dayT * $countT;


        $dayW = 0;
        if($a[0]->wednesday && $a[1]->wednesday){
            $dayW++;
        }else{
            if($a[0]->wednesday){
                $dayW++;
            }if($a[1]->wednesday){
                $dayW++;
            }
        }
        $totalDW = $dayW * $countW;


        $dayTh = 0;
        if($a[0]->thursday && $a[1]->thursday){
            $dayTh++;
        }else{
            if($a[0]->thursday){
                $dayTh++;
            }if($a[1]->thursday){
                $dayTh++;
            }
        }
        $totalDTh = $dayTh * $countTh;

        $dayF = 0;
        if($a[0]->friday && $a[1]->friday){
            $dayF++;
        }else{
            if($a[0]->friday){
                $dayF++;
            }if($a[1]->friday){
                $dayF++;
            }
        }
        $totalDF = $dayF * $countF;

        $daySa = 0;
        if($a[0]->saturday && $a[1]->saturday){
            $daySa++;
        }else{
            if($a[0]->saturday){
                $daySa++;
            }if($a[1]->saturday){
                $daySa++;
            }
        }
        $totalSa = $daySa * $countSa;

        $daySu = 0;
        if($a[0]->sunday && $a[1]->sunday){
            $daySu++;
        }else{
            if($a[0]->sunday){
                $daySu++;
            }if($a[1]->sunday){
                $daySu++;
            }
        }

        $totalSu = $daySu * $countSu;

        $totalDay = $totalDM + $totalDT + $totalDW + $totalDTh + $totalDF + $totalSa + $totalSu;
        return $totalDay;

    }

    function getDayStatusStartToEnd($id,$timeA,$id_book){
        $dateD = 'monday';
        if($timeA){
            $DayOfW = date('D',strtotime($timeA['start_time']));           
        }else{
            $DayOfW = date("D");
        }
        if ($DayOfW == "Mon"){
            $dateD = 'monday';
        }
                  

       if($DayOfW == "Tue"){
            $dateD = 'tuesday';
       }

       if($DayOfW == "Wed"){
            $dateD = 'wednesday';
       }

       if($DayOfW == "Thu"){
            $dateD = 'thursday';
       }

       if($DayOfW == "Fri"){
            $dateD = 'friday';
       }

       if($DayOfW == "Sat"){
            $dateD = 'saturday';
       }

       if($DayOfW == "Sun"){
            $dateD = 'sunday';
       }



        $a = $this->site->getAllTimeWork();
        foreach ($a as $key => $value) {
            $b[$key] = $this->site->getAllUserTimeWorkByIDUserIDWork($id,$value->id,$dateD);

        }

        $countO = 0;
        $class[0] = 'Nghỉ';

        foreach ($b as $key => $value) {
            if($value){
                $countO++;
                $class[$key] = $value->sma_timework_name;
            } 

        }

        $countTW = 0;
        if($a){
            $countTW = count($a);
        }

        $status = array(
            'status' => 1,
            'class'  => 'Không hoạt động',
            'busy'  => 0,
        );


        if($countTW != 0 && $countO !=0){

            if($countO == $countTW ){
                $status['class'] = 'Cả ngày';
                $status['class_1'] = 'Cả ngày';
                
            }else{
                $valueT = 0;
                foreach ($b as $key => $value) {
                    if($value){
                        $valueT++;
                        $valueX = $key;
                    }
                }             
                $status['class_1'] = $class[0];
            }


            if($valueT > 0){

                $time = array(
                    'start_time' => date('H:i:s',$a[$valueX]->sma_timework_starttime),
                    'now_time' => date('H:i:s'),
                    'end_time' => date('H:i:s',$a[$valueX]->sma_timework_endtime),
                    'class' => $a[$valueX]->sma_timework_name,
                );

                
            }            

        }

        if($timeA){
            $time['now_time'] = $timeA['start_time'];            
        }


        foreach ($this->site->getBookByIDStaff($id) as $key => $value) {
            
            if($value['sma_books_starttime'] <= strtotime($time['now_time']) &&  strtotime($time['now_time']) <=  $value['sma_books_endtime']){
                $status['busy'] = 1;
                if($id_book){
                    $status['busy'] = 0;
                }
                break;
                
            }else{
                $status['busy'] = 0;
            }
        }

        if($timeA){
            $time['now_time'] = date('H:i:s',strtotime($timeA['start_time']));            
        }

        if($time['start_time'] && $time['end_time']){
            if($time['start_time'] <= $time['now_time'] && $time['now_time'] <= $time['end_time']){

                $status['class'] = $time['class'];
                $status['class_1'] = $time['class'];

                return $status;
            }else{
                $status['status'] = 0;
                if(!$class[1] && $class[0] == 'Nghỉ'){
                    $status['class_1'] = 'Nghỉ';
                    return $status;


                }

                if(!$class[1] && $class[0] != 'Nghỉ'){
                    $status['class_1'] = $class[0];
                    return $status;


                }

                if($class[1] != 'Nghỉ' && $class[0] == 'Nghỉ'){
                    $status['class_1'] = $class[1];
                    return $status;
                }
            }                     
        }else{
            $status['class_1'] = $class[0];
            $status['status'] = 0; 

            if($status['class'] == 'Cả ngày'){
                $status['status'] = 1; 
            }
            return $status;
        }
        return $status;


    }

    function getDayUserWork($id){
        $User = $this->site->getUserByID($id);
        $UserMonth = explode('-', $this->sma->fsd($this->sma->ihrsd($User->start_date)) );
        if($UserMonth[1] == date('m') && $UserMonth[0] == date('Y')){
            $firstDate = mktime(0, 0, 0, date("n"), $UserMonth[2], $UserMonth[0]);
        }else{
            $firstDate = mktime(0, 0, 0, date("n"), 1, date("Y"));
        }
        $now = time();
        $time = array(
            'start_date' => date('Y-m-d',$firstDate),
            'end_date' => date('Y-m-d',$now),
        );


        $totalDayWo = $this->sma->getDayStartToEnd($id,$time);
        $totalDayOu = 0;
        $totalPayOut = 0;
        $dayOnMonth = date("t");
        $totalBoun = 0;
        $totalFine = 0;
        $totalPay = 0;
        $totayPayOut = 0;

        foreach ($this->site->getPayByIDUser($id, null, null) as $key => $value) {
            $time = array(
                'start_date' => date('Y-m-d',$value['sma_pay_startdate']),
                
            );
            if($value['sma_pay_enddate']){
                $time['end_date'] = date('Y-m-d',$value['sma_pay_enddate']);
            }else{
                 $time['end_date'] = date('Y-m-d',$now);
            }


            $totaDayPay = $this->sma->getDayStartToEnd($id,$time);

            $totalPay +=  $value['sma_pay_pay'] * $totaDayPay;
            
        }
        

        $LastPay = $this->site->getPayByIDUser($id,1,'DESC');

        if($totalDayWo > $this->Settings->then_day){
            $totalPay +=  ($LastPay[0]['sma_pay_pay'] * ($this->Settings->pay_multiplication-1) )  * ($totalDayWo - $this->Settings->then_day);
        }

        $Otex = $this->site->getOtexByIDUser($id,date('m'),date('Y'));
        $totalOtex = $Otex->sma_otex_pay;
        $dateO = [];
        foreach ($this->site->getAllTimeoutByIDUser($id,date('m'),date('Y')) as $key => $value) {
            $totalDayOu += $value['sma_timeout_day'];
            $start_dateO = $value['sma_timeout_startdate'];
            $end_dateO = $value['sma_timeout_enddate'];

            $time = array(
                'start_date' => date('Y-m-d',strtotime("-1 day", $start_dateO)),
                'end_date' => date('Y-m-d',$start_dateO),
            );

            $dateO[$start_dateO * 1]['day'] = $this->sma->getDayStartToEnd($id,$time);
            $dateO[$start_dateO * 1]['pay'] = 0;
            $dateO[$start_dateO * 1]['total'] = 0;

            if($start_dateO <= $end_dateO){
                $time = array(
                    'start_date' => date('Y-m-d',$start_dateO),
                    'end_date' => strtotime("+1 day", $start_dateO),
                );
                $dateO[strtotime("+1 day", $start_dateO)]['day'] = $this->sma->getDayStartToEnd($id,$time);
                $dateO[strtotime("+1 day", $start_dateO)]['pay'] = 0;
                $dateO[strtotime("+1 day", $start_dateO)]['total'] = 0;
            }

            foreach ($this->site->getPayByIDUser($id,NULL,'ASC') as $key1 => $value1) {
                $time = array(
                    'start_date' => strtotime(date('Y-m-d',$value1['sma_pay_startdate'])),
                    
                );
                if($value['sma_pay_enddate']){
                    $time['end_date'] = strtotime(date('Y-m-d',$value1['sma_pay_enddate']));
                }else{
                    $time['end_date'] = strtotime(date('Y-m-d',$now));
                }

                $dateO[$time['start_date']]['pay'] = $value1['sma_pay_pay'];
                $dateO[$time['start_date']]['total'] = $dateO[$time['start_date']]['day'] * $value1['sma_pay_pay'];
                if($time['start_date'] < $time['end_date']){                                    
                    $dateO[strtotime("+1 day", $time['start_date'])]['pay'] = $value1['sma_pay_pay'];
                    $dateO[strtotime("+1 day", $time['start_date'])]['total'] = $dateO[strtotime("+1 day", $time['start_date'])]['day'] * $value1['sma_pay_pay'];
                }
            }


        };

        foreach ($dateO as $key => $value) {
            $totalPayOut += $value['total'];
        }

        foreach ($this->site->getBounsByIDM($id,date('m'),date('Y')) as $key => $value) {
            $totalBoun += $value['sma_bouns_bouns'];
        }

        foreach ($this->site->getFinesByIDM($id,date('m'),date('Y')) as $key => $value) {
            $totalFine += $value['sma_fine_fine'];
        }

        $nowTime = strtotime(date('Y-m-d'));

        $timeA = array(
            'start_date' => date('Y-m-d',$nowTime),
            'end_date' => date('Y-m-d',$nowTime),
        );

        $active = $this->sma->getDayStatusStartToEnd($id, null, null);
        if($active['status'] == 1){
            $status = 'Đang hoạt động';
        }else{
            $status = 'Không hoạt động';            
        }
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
            'class'  => $active['class'],
            'busy' => 0,
        );
        return $arr;
    }

    function addNotice($arr){
        $data = array(
            'sma_notice_descriptiontitle' => $arr['descriptiontitle'],
            'sma_notice_descriptionname' => $arr['descriptionname'],
            'sma_notice_type' => $arr['type'],
            'sma_notice_typeid' => $arr['type_id'],
            'sma_notice_staffid' => $arr['staffid'],
            'sma_notice_staffname' => $arr['staffname'],
            'sma_notice_date' => $arr['date'],
            'sma_notice_slug' => $arr['slug'],
            'sma_notice_read' => 0,
        );

        if($this->db->insert('sma_notice',$data)) {
            return true;
        }

        return false;
    }

    function getUserWork($id,$time1,$id_book){
        date_default_timezone_set("UTC+08:00");
           $User = $this->site->getUserByID($id);
           if($time1){
               $time = array(
                    'start_time' => date('Y-m-d H:i:s',$time1['start_time']),
                    'end_time'   => date('Y-m-d H:i:s',$time1['end_time']),
               );
           }
       $TimeWork = $this->getDayStatusStartToEnd($id,$time,$id_book);
        $arr = array(
            'status' => $TimeWork['status'],
            'busy'   => $TimeWork['busy'],
            'class'  => $TimeWork['class_1'],
        );
        return $arr;
    }

    public function fsd($inv_date)
    {
        if ($inv_date) {
            $jsd = $this->dateFormats['js_sdate'];
            if ($jsd == 'dd-mm-yyyy' || $jsd == 'dd/mm/yyyy' || $jsd == 'dd.mm.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 3, 2) . "-" . substr($inv_date, 0, 2);
            } elseif ($jsd == 'mm-dd-yyyy' || $jsd == 'mm/dd/yyyy' || $jsd == 'mm.dd.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 0, 2) . "-" . substr($inv_date, 3, 2);
            } else {
                $date = $inv_date;
            }
            return $date;
        } else {
            return '0000-00-00';
        }
    }

    public function fld($ldate)
    {
        
        if ($ldate) {
            $date = explode(' ', $ldate);
            $jsd = $this->dateFormats['js_sdate'];
            $inv_date = $date[0];
            $time = $date[1];
            if ($jsd == 'dd-mm-yyyy' || $jsd == 'dd/mm/yyyy' || $jsd == 'dd.mm.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 3, 2) . "-" . substr($inv_date, 0, 2) . " " . $time;
            } elseif ($jsd == 'mm-dd-yyyy' || $jsd == 'mm/dd/yyyy' || $jsd == 'mm.dd.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 0, 2) . "-" . substr($inv_date, 3, 2) . " " . $time;
            } else {
                $date = $inv_date;
            }
            return $date;
        } else {
            return '0000-00-00 00:00:00';
        }
    }

    public function send_email($to, $subject, $message, $from = NULL, $from_name = NULL, $attachment = NULL, $cc = NULL, $bcc = NULL)
    {
        $this->load->library('email');
        $config['useragent'] = "Stock Manager Advance";
        $config['protocol'] = $this->Settings->protocol;
        $config['mailtype'] = "html";
        $config['crlf'] = "\r\n";
        $config['newline'] = "\r\n";
        if ($this->Settings->protocol == 'sendmail') {
            $config['mailpath'] = $this->Settings->mailpath;
        } elseif ($this->Settings->protocol == 'smtp') {
            $this->load->library('encrypt');
            $config['smtp_host'] = $this->Settings->smtp_host;
            $config['smtp_user'] = $this->Settings->smtp_user;
            $config['smtp_pass'] = $this->encrypt->decode($this->Settings->smtp_pass);
            $config['smtp_port'] = $this->Settings->smtp_port;
            if (!empty($this->Settings->smtp_crypto)) {
                $config['smtp_crypto'] = $this->Settings->smtp_crypto;
            }
        }

        $this->email->initialize($config);

        if ($from && $from_name) {
            $this->email->from($from, $from_name);
        } elseif ($from) {
            $this->email->from($from, $this->Settings->site_name);
        } else {
            $this->email->from($this->Settings->default_email, $this->Settings->site_name);
        }

        $this->email->to($to);
        if ($cc) {
            $this->email->cc($cc);
        }
        if ($bcc) {
            $this->email->bcc($bcc);
        }
        $this->email->subject($subject);
        $this->email->message($message);
        if ($attachment) {
            if (is_array($attachment)) {
                foreach ($attachment as $file) {
                    $this->email->attach($file);
                }
            } else {
                $this->email->attach($attachment);
            }
        }

        if ($this->email->send()) {
            //echo $this->email->print_debugger(); die();
            return TRUE;
        } else {
            //echo $this->email->print_debugger(); die();
            return FALSE;
        }
    }

    public function checkPermissions($action = NULL, $js = NULL, $module = NULL)
    {
        if (!$this->actionPermissions($action, $module)) {
            $this->session->set_flashdata('error', lang("access_denied"));
            if ($js) {
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
            } else {
                redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
            }
        }
    }

    public function actionPermissions($action = NULL, $module = NULL)
    {
        if ($this->Owner || $this->Admin) {
            if ($this->Admin && stripos($action, 'delete') !== false) {
                return FALSE;
            }
            return TRUE;
        } elseif ($this->Customer || $this->Supplier) {
            return false;
        } else {
            if (!$module) {
                $module = $this->m;
            }
            if (!$action) {
                $action = $this->v;
            }
            //$gp = $this->site->checkPermissions();
            if ($this->GP[$module . '-' . $action] == 1) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    public function save_barcode($text = NULL, $bcs = 'code39', $height = 56, $stext = 1, $width = 256)
    {
        $drawText = ($stext != 1) ? FALSE : TRUE;
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcodeOptions = array('text' => $text, 'barHeight' => $height, 'drawText' => $drawText);
        $rendererOptions = array('imageType' => 'png', 'horizontalPosition' => 'center', 'verticalPosition' => 'middle'); //'width' => $width
        $image = Zend_Barcode::draw($bcs, 'image', $barcodeOptions, $rendererOptions);
        if (imagepng($image, 'assets/uploads/barcode' . $this->session->userdata('user_id') . '.png')) {
            imagedestroy($image);
            $bc = file_get_contents('assets/uploads/barcode' . $this->session->userdata('user_id') . '.png');
            $bcimage = base64_encode($bc);
            return $bcimage;
        }
        return FALSE;
    }

    public function qrcode($type = 'text', $text = 'PHP QR Code', $size = 2, $level = 'H', $file_name = NULL)
    {
        $file_name = 'assets/uploads/qrcode' . $this->session->userdata('user_id') . '.png';
        if ($type == 'link') {
            $text = urldecode($text);
        }
        $this->load->library('phpqrcode');
        $config = array('data' => $text, 'size' => $size, 'level' => $level, 'savename' => $file_name);
        $this->phpqrcode->generate($config);
        $qr = file_get_contents('assets/uploads/qrcode' . $this->session->userdata('user_id') . '.png');
        $qrimage = base64_encode($qr);
        return $qrimage;
    }

    public function generate_pdf($content, $name = 'download.pdf', $output_type = NULL, $footer = NULL, $margin_bottom = NULL, $header = NULL, $margin_top = NULL, $orientation = 'P')
    {
        if (!$output_type) {
            $output_type = 'D';
        }
        if (!$margin_bottom) {
            $margin_bottom = 10;
        }
        if (!$margin_top) {
            $margin_top = 10;
        }
        $this->load->library('pdf');
        $pdf = new mPDF('utf-8', 'A4-' . $orientation, '13', '', 10, 10, $margin_top, $margin_bottom, 9, 9);
        $pdf->debug = false;
        $pdf->autoScriptToLang = true;
        $pdf->autoLangToFont = true;
        $pdf->SetProtection(array('print')); // You pass 2nd arg for user password (open) and 3rd for owner password (edit)
        //$pdf->SetProtection(array('print', 'copy')); // Comment above line and uncomment this to allow copying of content
        $pdf->SetTitle($this->Settings->site_name);
        $pdf->SetAuthor($this->Settings->site_name);
        $pdf->SetCreator($this->Settings->site_name);
        $pdf->SetDisplayMode('fullpage');
        $stylesheet = file_get_contents('assets/bs/bootstrap.min.css');
        $pdf->WriteHTML($stylesheet, 1);
        $pdf->WriteHTML($content);
        if ($header != '') {
            $pdf->SetHTMLHeader('<p class="text-center">' . $header . '</p>', '', TRUE);
        }
        if ($footer != '') {
            $pdf->SetHTMLFooter('<p class="text-center">' . $footer . '</p>', '', TRUE);
        }
        //$pdf->SetHeader($this->Settings->site_name.'||{PAGENO}', '', TRUE); // For simple text header
        //$pdf->SetFooter($this->Settings->site_name.'||{PAGENO}', '', TRUE); // For simple text footer
        if ($output_type == 'S') {
            $file_content = $pdf->Output('', 'S');
            write_file('assets/uploads/' . $name, $file_content);
            return 'assets/uploads/' . $name;
        } else {
            $pdf->Output($name, $output_type);
        }
    }

    public function print_arrays()
    {
        $args = func_get_args();
        echo "<pre>";
        foreach ($args as $arg) {
            print_r($arg);
        }
        echo "</pre>";
        die();
    }

    public function logged_in()
    {
        return (bool)$this->session->userdata('identity');
    }

    public function in_group($check_group, $id = false)
    {
        $id || $id = $this->session->userdata('user_id');
        $group = $this->site->getUserGroup($id);
        if ($group->name === $check_group) {
            return TRUE;
        }
        return FALSE;
    }

    public function log_payment($msg, $val = NULL)
    {
        $this->load->library('logs');
        return (bool)$this->logs->write('payments', $msg, $val);
    }

    public function update_award_points($total, $customer, $user, $scope = NULL)
    {
        if ($this->Settings->each_spent && $total >= $this->Settings->each_spent) {
            $company = $this->site->getCompanyByID($customer);
            $points = floor(($total / $this->Settings->each_spent) * $this->Settings->ca_point);
            $total_points = $scope ? $company->award_points - $points : $company->award_points + $points;
            $this->db->update('companies', array('award_points' => $total_points), array('id' => $customer));
        }
        if ($this->Settings->each_sale && !$this->Customer && $total >= $this->Settings->each_sale) {
            $staff = $this->site->getUser($user);
            $points = floor(($total / $this->Settings->each_sale) * $this->Settings->sa_point);
            $total_points = $scope ? $staff->award_points - $points : $staff->award_points + $points;
            $this->db->update('users', array('award_points' => $total_points), array('id' => $user));
        }
        return TRUE;
    }

    public function zip($source = NULL, $destination = "./", $output_name = 'sma', $limit = 5000)
    {
        if (!$destination || trim($destination) == "") {
            $destination = "./";
        }

        $this->_rglobRead($source, $input);
        $maxinput = count($input);
        $splitinto = (($maxinput / $limit) > round($maxinput / $limit, 0)) ? round($maxinput / $limit, 0) + 1 : round($maxinput / $limit, 0);

        for ($i = 0; $i < $splitinto; $i++) {
            $this->_zip(array_slice($input, ($i * $limit), $limit, true), $i, $destination, $output_name);
        }

        unset($input);
        return;
    }

    public function unzip($source, $destination = './')
    {

        // @chmod($destination, 0777);
        $zip = new ZipArchive;
        if ($zip->open(str_replace("//", "/", $source)) === true) {
            $zip->extractTo($destination);
            $zip->close();
        }
        // @chmod($destination,0755);

        return TRUE;
    }

    public function view_rights($check_id, $js = NULL)
    {
        if (!$this->Owner && !$this->Admin) {
            if ($check_id != $this->session->userdata('user_id')) {
                $this->session->set_flashdata('warning', $this->data['access_denied']);
                if ($js) {
                    die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
                } else {
                    redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
                }
            }
        }
        return TRUE;
    }

    function makecomma($input) {
        if(strlen($input)<=2)
        { return $input; }
        $length=substr($input,0,strlen($input)-2);
        $formatted_input = $this->makecomma($length).",".substr($input,-2);
        return $formatted_input;
    }

    public function formatSAC($num) {
        $pos = strpos((string)$num, ".");
        if ($pos === false) { $decimalpart="00";}
        else { $decimalpart= substr($num, $pos+1, 2); $num = substr($num,0,$pos); }

        if(strlen($num)>3 & strlen($num) <= 12){
            $last3digits = substr($num, -3 );
            $numexceptlastdigits = substr($num, 0, -3 );
            $formatted = $this->makecomma($numexceptlastdigits);
            $stringtoreturn = $formatted.",".$last3digits.".".$decimalpart ;
        } elseif(strlen($num)<=3) {
            $stringtoreturn = $num.".".$decimalpart ;
        } elseif(strlen($num)>12) {
            $stringtoreturn = number_format($num, 2);
        }

        if(substr($stringtoreturn,0,2)=="-,"){$stringtoreturn = "-".substr($stringtoreturn,2 );}

        return $stringtoreturn;
    }

    public function md() {
        die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
    }

    /**
     * Convert from Timeout Type to String
     * 0 -> Nghỉ phép
     * 1 -> Nghỉ ko phép
     * v.v
     * @param int $type
     * @return float|int
     */
    public function timeoutTypeText($type=0)
    {
        switch($type) {
            case 0:
                $text = "Nghỉ phép";
                break;
            case 1:
                $text = "Nghỉ không phép";
                break;
            case 2:
                $text = "Nghỉ nửa ngày có phép";
                break;
            case 3:
                $text = "Nghỉ nửa ngày không phép";
                break;
            default:
                $text = "Nghỉ phép";
        }
        return $text;
    }
}
