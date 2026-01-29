<?php

function escapeString($val)
{
    $ci = & get_instance();
    $driver = $ci->db->dbdriver;

    if( $driver == 'mysql') {
        $val = mysql_real_escape_string($val);
    } elseif($driver == 'mysqli') {
        $db = get_instance()->db->conn_id;
        $val = mysqli_real_escape_string($db, $val);
    }

    return $val;
}

if (!function_exists('dump')) {
    function dump ($var, $label = 'Dump', $echo = TRUE)
    {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';

        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}

function pageStatus($data, $flag = TRUE)
{
    if($flag) {
        $array = array(
            'published'   => 1, 
            'draft'     => 2, 
            'trash'     => 3, 
            'review'    => 4,  
        );

        if(isset($array[$data]))
            return $array[$data];
        else
            return 1;
    }

    if($flag == FALSE) {
        $array = array(
            1 => 'published', 
            2 => 'draft', 
            3 => 'trash',
            4 => 'review'  
        );

        if(isset($array[$data]))
            return $array[$data];
        else
            return 'publish';
    }
}

function pageVisibility($visibility, $flag=TRUE, $send = 1)
{
    $CI = & get_instance();
    $language = $CI->session->userdata('lang');
    $CI->lang->load('page', $language);

    if($flag) {
        $status = FALSE;
        if($visibility == 1 && $send == 1) {
            $status = TRUE;
        } elseif($visibility == 2 && $send == 2) {
            $status = TRUE;
        } elseif($visibility == 3 && $send == 3) {
            $status = TRUE;
        }
        return $status;
    }

    if($flag == FALSE) {
        if($visibility == 1) {
            echo $CI->lang->line('page_public');
        } elseif($visibility == 2) {
            echo $CI->lang->line('page_password_protected');
        } elseif($visibility == 3) {
            echo $CI->lang->line('page_private');
        }
    }
}

if (!function_exists('dump_exit')) {
    function dump_exit($var, $label = 'Dump', $echo = TRUE)
    {
        dump ($var, $label, $echo);
        exit;
    }
}

if (!function_exists('dd')) {
    function dd($var="", $label = 'Dump', $echo = TRUE)
    {
        dump ($var, $label, $echo);
        exit;
    }
}

function pluck($array, $value, $key=NULL)
{
    $returnArray = array();
    if(inicompute($array)) {
        foreach ($array as $item) {
            if($key != NULL) {
                $returnArray[$item->$key] = strtolower($value) == 'obj' ? $item : $item->$value;
            } else {
                $returnArray[] = $item->$value;
            }
        }
    }
    return $returnArray;
}

function pluck_bind($array, $value, $concatFirst, $concatLast, $key=NULL)
{
    $returnArray = array();
    if(inicompute($array)) {
        foreach ($array as $item) {
            if($key != NULL) {
                $returnArray[$item->$key] = $concatFirst.$item->$value.$concatLast;
            } else {
                if($value!=NULL) {
                    $returnArray[] = $concatFirst.$item->$value.$concatLast;
                } else {
                    $returnArray[] = $concatFirst.$item.$concatLast;
                }
            }
        }
    }

    return $returnArray;
}

function pluck_multi_array($arrays, $val, $key = NULL)
{
    $retArray = array();
    if(inicompute($arrays)) {
        $i = 0;
        foreach ($arrays as $array) {
            if(!empty($key)) {
                if(strtolower($val) == 'obj') {
                    $retArray[$array->$key][] = $array;
                } else {
                    $retArray[$array->$key][] = $array->$val;
                }
            } else {
                if(strtolower($val) == 'obj') {
                    $retArray[$i][] = $array;
                } else {
                    $retArray[$i][] = $array->$val;
                }
                $i++;
            }
        }
    }
    return $retArray;
}

function pluck_multi_array_key($arrays, $val, $fstKey = NULL, $sndKey = NULL)
{
    $retArray = array();
    if(inicompute($arrays)) {
        $i = 0;
        foreach ($arrays as $array) {
            if(!empty($fstKey)) {
                if(strtolower($val) == 'obj') {
                    if(!empty($sndKey)) {
                        $retArray[$array->$fstKey][$array->$sndKey] = $array;
                    } else {
                        $retArray[$array->$fstKey][] = $array;
                    }
                } else {
                    if(!empty($sndKey)) {
                        $retArray[$array->$fstKey][$array->$sndKey] = $array->$val;
                    } else {
                        $retArray[$array->$fstKey][] = $array->$val;
                    }
                    
                }
            } else {
                if(strtolower($val) == 'obj') {
                    if(!empty($sndKey)) {
                        $retArray[$i][$array->$sndKey] = $array;
                    } else {
                        $retArray[$i][] = $array;
                    }
                } else {
                    if(!empty($sndKey)) {
                        $retArray[$i][$array->$sndKey] = $array->$val;
                    } else {
                        $retArray[$i][] = $array->$val;
                    }
                }
                $i++;
            }
        }
    }
    return $retArray;
}


function btn_sm_global( $uri, $name, $icon, $color = null )
{
    if ( !$color ) {
        $color = "btn-primary";
    }
    return anchor($uri, "<i class='" . $icon . "'></i>",
        "class='btn " . $color . " btn-custom  mrg' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'");
}
function btn_md_global($uri, $name, $icon, $class = null)
{
    if(!$class) {
        $class = "btn-primary";
    }
    return anchor($uri, $icon, "class='".$class."' data-placement='top' data-toggle='tooltip' title='".$name."'");
}

function namesorting($string, $len = 14)
{
    $return = $string;
    if(isset($string) && $len) {
        if(strlen($string) >  $len) {
            $return = substr($string, 0,$len-2).'..';
        } else {
            $return = $string;
        }
    }

    if(empty($return)) {
        return "&nbsp;";
    } else {
        return $return;
    }
}

function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function spClean($string)
{
    $string = strtolower($string);
    $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function sentenceMap($string, $numberOFWord, $startTag, $closeTag)
{
    $exp = explode(' ', $string);
    $len = 0;
    $expEnd = end($exp);
    $f = TRUE;
    $stringWarp = '';
    foreach ($exp as $key => $sn) {
        $len += strlen($sn);
        $len++;
        
        if($len >= $numberOFWord) {
            if($f) {
                $stringWarp .= $startTag;
                $f = FALSE; 
            }
        }

        $stringWarp .= $sn.' ';

        if($sn == $expEnd) {
            if($f == FALSE) {
                $stringWarp .= $closeTag;
            }
            return $stringWarp;
        }
    }
}

function xssRemove($data) 
{
    $string = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $data);
    return $string;
}

function addOrdinalNumberSuffix($num) 
{
    if (!in_array(($num % 100),array(11,12,13))){
        switch ($num % 10) {
            case 1:  return $num.'st';
            case 2:  return $num.'nd';
            case 3:  return $num.'rd';
        }
    }
    return $num.'th';
}

function getNumberSuffix($num) 
{
    if (!in_array(($num % 100),array(11,12,13))){
        switch ($num % 10) {
            case 1:  return 'st';
            case 2:  return 'nd';
            case 3:  return 'rd';
        }
    }
    return 'th';
}

function get_month_and_year_using_two_date($startdate, $enddate)
{
    $start    = new DateTime($startdate);
    $start->modify('first day of this month');
    $end      = new DateTime($enddate);
    $end->modify('first day of next month');        
    $interval = DateInterval::createFromDateString('1 month');
    $period   = new DatePeriod($start, $interval, $end);

    $monthAndYear = [];
    if(inicompute($period)) {
        foreach ($period as $dt) {
            $monthAndYear[ $dt->format("Y")][] =  $dt->format("m");
        }
    }
    return $monthAndYear;
}

function lzero($num)
{
    $numPadded = sprintf("%02d", $num);
    return $numPadded;    
}

function imageLinkWithDefatulImage($photoName, $defaultPhotoName = 'default.png', $srcpath = NULL)
{
    if($srcpath == NULL) {
        if($photoName != NULL) {
            if(file_exists(FCPATH.'uploads/user/'.$photoName)) {
                $src = base_url('uploads/user/'.$photoName);
            } else {
                $src = base_url('uploads/user/'.$defaultPhotoName);
            }
        } else {
            $src = base_url('uploads/user/'.$defaultPhotoName);
        }
    } else {
        if($photoName != NULL) {
            if(file_exists(FCPATH.$srcpath.'/'.$photoName)) {
                $src = base_url($srcpath.'/'.$photoName);
            } else {
                $src = base_url('uploads/user/'.$defaultPhotoName);
            }
        } else {
            $src = base_url('uploads/user/'.$defaultPhotoName);
        }
    }
    return $src;
}

function imagelink($photoname, $srcpath = NULL)
{
    if($srcpath == NULL) {
        if($photoname != NULL) {
            if(file_exists(FCPATH.'uploads/user/'.$photoname)) {
                $src = base_url('uploads/user/'.$photoname);
            } else {
                $src = base_url('uploads/user/default.png');
            }
        } else {
            $src = base_url('uploads/user/default.png');
        }
    } else {
        if($photoname != NULL) {
            if(file_exists(FCPATH.$srcpath.'/'.$photoname)) {
                $src = base_url($srcpath.'/'.$photoname);
            } else {
                $src = base_url('uploads/user/default.png');
            }
        } else {
            $src = base_url('uploads/user/default.png');
        }
    }
    return $src;
}

function pdfimagelink($photoname, $srcpath = NULL)
{
    if($srcpath == NULL) {
        if($photoname != NULL) {
            if(file_exists(FCPATH.'uploads/user/'.$photoname)) {
                $src = base_url('uploads/user/'.$photoname);
            } else {
                $src = base_url('uploads/user/default.png');
            }
        } else {
            $src = base_url('uploads/user/default.png');
        }
    } else {
        if($photoname != NULL) {
            if(file_exists(FCPATH.$srcpath.'/'.$photoname)) {
                $src = base_url($srcpath.'/'.$photoname);
            } else {
                $src = base_url('uploads/user/default.png');
            }
        } else {
            $src = base_url('uploads/user/default.png');
        }
    }
    return $src;
}

function profileimage($photoname, $srcpath = NULL)
{
    if($srcpath == NULL) {
        if($photoname != NULL) {
            if(file_exists(FCPATH.'uploads/user/'.$photoname)) {
                $src = base_url('uploads/user/'.$photoname);
            } else {
                $src = base_url('uploads/user/default.png');
            }
        } else {
            $src = base_url('uploads/user/default.png');
        }
    } else {
        if($photoname != NULL) {
            if(file_exists(FCPATH.$srcpath.'/'.$photoname)) {
                $src = base_url($srcpath.'/'.$photoname);
            } else {
                $src = base_url('uploads/user/default.png');
            }
        } else {
            $src = base_url('uploads/user/default.png');
        }
    }

    $array = array(
        "src" => $src,
        'width' => '35px',
        'height' => '35px',
        'class' => 'img-rounded'
    );
    return img($array);
}

function profileviewimage($photoname, $srcpath = NULL)
{
    if($srcpath == NULL) {
        if($photoname != NULL) {
            if(file_exists(FCPATH.'uploads/user/'.$photoname)) {
                $src = base_url('uploads/user/'.$photoname);
            } else {
                $src = base_url('uploads/user/default.png');
            }
        } else {
            $src = base_url('uploads/user/default.png');
        }
    } else {
        if($photoname != NULL) {
            if(file_exists(FCPATH.$srcpath.'/'.$photoname)) {
                $src = base_url($srcpath.'/'.$photoname);
            } else {
                $src = base_url('uploads/user/default.png');
            }
        } else {
            $src = base_url('uploads/user/default.png');
        }
    }

    $array = array(
        "src" => $src,
        'class' => 'profile-user-img img-responsive img-circle'
    );
    return img($array);
}

function profileproimage($photoname, $srcpath = NULL)
{
    if($srcpath == NULL) {
        if($photoname != NULL) {
            if(file_exists(FCPATH.'uploads/user/'.$photoname)) {
                $src = base_url('uploads/user/'.$photoname);
            } else {
                $src = base_url('uploads/user/default.png');
            }
        } else {
            $src = base_url('uploads/user/default.png');
        }
    } else {
        if($photoname != NULL) {
            if(file_exists(FCPATH.$srcpath.'/'.$photoname)) {
                $src = base_url($srcpath.'/'.$photoname);
            } else {
                $src = base_url('uploads/user/default.png');
            }
        } else {
            $src = base_url('uploads/user/default.png');
        }
    }

    $string = '<a width="35px" height="35px" class="card-image img-rounded" href="#" style="background-image: url('.base_url("uploads/user/default.png").');" data-image-full="'.$src.'"><img class="img-rounded" width="35px" height="35px" src="'.$src.'" alt="Psychopomp" /></a>';
    return $string;

    // $array = array(
    //     "src" => $src,
    //     'width' => '35px',
    //     'height' => '35px',
    //     'class' => 'img-rounded'
    // );
    // return img($array);
}

function get_day_using_two_date($fromdate, $todate)
{
    $oneday    = 60*60*24;
    
    $alldays = [];
    for($i=$fromdate; $i<= $todate; $i= $i+$oneday) {
        $alldays[] = date('d-m-Y', $i);
    } 
    return $alldays;
}

function random19()
{
    $number = "";
    for($i=0; $i<19; $i++) {
        $min = ($i == 0) ? 1:0;
        $number .= mt_rand($min,9);
    }
    return $number;
}

function timelefter($dafstdate)
{
    $pdate = date("Y-m-d H:i:s");
    $first_date = new DateTime($dafstdate);
    $second_date = new DateTime($pdate);
    $difference = $first_date->diff($second_date);
    if($difference->y >= 1) {
        $format = 'Y-m-d H:i:s';
        $date = DateTime::createFromFormat($format, $dafstdate);
        return $date->format('M d Y');
    } elseif($difference->m ==1 && $difference->m !=0) {
        return $difference->m . " month ago";
    } elseif($difference->m <=12 && $difference->m !=0) {
        return $difference->m . " months ago";
    } elseif($difference->d == 1 && $difference->d != 0) {
        return "Yesterday";
    } elseif($difference->d <= 31 && $difference->d != 0) {
        return $difference->d . " days ago";
    } else if($difference->h ==1 && $difference->h !=0) {
        return $difference->h . " hr ago";
    } else if($difference->h <=24 && $difference->h !=0) {
        return $difference->h . " hrs ago";
    } elseif($difference->i <= 60 && $difference->i !=0) {
        return $difference->i . " mins ago";
    } elseif($difference->s <= 10) {
        return "Just Now";
    } elseif($difference->s <= 60 && $difference->s !=0) {
        return $difference->s . " sec ago";
    }
}

function btn_add($uri, $name)
{
    if(visibleButton($uri)) {
        return anchor($uri, "<i class='fa fa-plus'></i>", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' title='".$name."'");
    }
    return '';
}

function btn_add_show($uri, $name)
{
    return anchor($uri, "<i class='fa fa-plus'></i>", "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' title='".$name."'");
}

function btn_view($uri, $name)
{
    if(visibleButton($uri)) {
        return anchor($uri, "<i class='fa fa-check-square-o'></i>", "class='btn btn-success btn-custom mrg' data-placement='top' data-toggle='tooltip' title='".$name."'");
    }
    return '';
}

function btn_view_show($uri, $name)
{
    return anchor($uri, "<i class='fa fa-check-square-o'></i>", "class='btn btn-success btn-custom mrg' data-placement='top' data-toggle='tooltip' title='".$name."'");
}

function btn_edit($uri, $name)
{
    if(visibleButton($uri)) {
        return anchor($uri, "<i class='fa fa-edit'></i>", "class='btn btn-warning btn-custom mrg' data-placement='top' data-toggle='tooltip' title='".$name."'");
    }
    return '';
}

function btn_edit_show($uri, $name)
{
    return anchor($uri, "<i class='fa fa-edit'></i>", "class='btn btn-warning btn-custom mrg ' data-placement='top' data-toggle='tooltip' title='".$name."'");
}

function btn_modal_add($uri, $id = 0, $lang)
{
    if(visibleButton($uri)) {
        echo  "<button data-placement='top' data-toggle='tooltip' title='".$lang."' id='".$id."' class='btn btn-primary btn-custom mrg addModalBtn'><span class='fa fa-plus-square-o' data-toggle='modal' data-target='#addModal'></span></button>";
    } 
    return '';
}

function btn_modal_edit($uri, $id = 0, $lang)
{
    if(visibleButton($uri)) {
        echo  "<button data-placement='top' data-toggle='tooltip' title='".$lang."' id='".$id."' class='btn btn-warning btn-custom mrg editModalBtn'><span class='fa fa-edit' data-toggle='modal' data-target='#editModal'></span></button>";
    }
    return '';
}

function btn_modal_view($uri, $id = 0, $lang)
{
    if(visibleButton($uri)) {
        return "<button data-toggle='modal' data-target='#viewModal' id='".$id."'  class='btn btn-success btn-custom mrg viewModalBtn'><span class='fa fa-check-square-o' data-placement='top' data-toggle='tooltip' title='".$lang."'></span></button>";
    }
    return '';
}

function btn_modal_view_show( $id = 0, $lang)
{
    return "<button data-toggle='modal' data-target='#viewModal'  id='".$id."'  class='btn btn-success btn-custom mrg viewModalBtn'><span class='fa fa-check-square-o' data-placement='top' data-toggle='tooltip' title='".$lang."'></span></button>";
}

function btn_delete($uri, $name)
{
    if(visibleButton($uri)) {
        return anchor($uri, "<i class='fa fa-trash-o'></i>",
            array(
                'onclick' => "return confirm('you are about to delete a record. This cannot be undone. are you sure?')",
                'class' => 'btn btn-danger btn-custom mrg',
                'data-placement' => 'top',
                'data-toggle' => 'tooltip',
                'title' => $name
            )
        );
    }
    return '';
}

function btn_custom($permission, $uri, $name, $icon = 'fa fa-file-text-o', $button = 'btn-primary', $confirm = FALSE, $message='you are about to cancel the record. This cannot be undone. are you sure?', $newtab = false)
{
    if(permissionChecker($permission)) {
        $retArray =  array(
            'class' => 'btn '.$button.' btn-custom mrg',
            'data-placement' => 'top',
            'data-toggle' => 'tooltip',
            'data-original-title' => $name,
        );

        if($newtab) {
            $retArray['target'] = '_blank';
        }

        if($confirm) {
            $retArray['onclick'] = "return confirm('".$message."')";
        }

        return anchor($uri, "<i class='".$icon."'></i>", $retArray);
    }
    return '';
}

function btn_custom_show($uri, $name, $icon = 'fa fa-file-text-o', $button = 'btn-primary', $confirm = FALSE, $message='you are about to cancel the record. This cannot be undone. are you sure?', $newtab = false)
{
    $retArray = array(
        'class' => 'btn ' . $button . ' btn-custom mrg',
        'data-placement' => 'top',
        'data-toggle' => 'tooltip',
        'data-original-title' => $name,
    );

    if ($newtab) {
        $retArray['target'] = '_blank';
    }

    if ($confirm) {
        $retArray['onclick'] = "return confirm('" . $message . "')";
    }

    return anchor($uri, "<i class='" . $icon . "'></i>", $retArray);
}

function btn_modal_status($uri, $id = 0, $lang)
{
    if (visibleButton($uri)) {
        return "<button data-placement='top' data-toggle='tooltip' title='" . $lang . "' id='" . $id . "'  class='btn btn-success btn-custom mrg viewModalBtn'><span class='fa fa-circle-o' data-toggle='modal' data-target='#viewModal'></span></button>";
    }
    return '';
}

function btn_cancel($uri, $name)
{
    return anchor($uri, "<i class='fa fa-close'></i>",
        array(
            'onclick' => "return confirm('you are about to cancel the record. This cannot be undone. are you sure?')",
            'class' => 'btn btn-danger btn-custom mrg',
            'data-placement' => 'top',
            'data-toggle' => 'tooltip',
            'data-original-title' => $name
        )
    );
}

function btn_delete_show($uri, $name)
{
    return anchor($uri, "<i class='fa fa-trash-o'></i>",
        array(
            'onclick' => "return confirm('you are about to delete a record. This cannot be undone. are you sure?')",
            'class' => 'btn btn-danger btn-custom mrg',
            'data-placement' => 'top',
            'data-toggle' => 'tooltip',
            'title' => $name
        )
    );
}

function blood_group()
{
    $retArray = array(
        'A+'  => 'A+',
        'A-'  => 'A-',
        'B+'  => 'B+',
        'B-'  => 'B-',
        'AB+' => 'AB+',
        'AB-' => 'AB-',
        'O+'  => 'O+',
        'O-'  => 'O-',
    ); 
    return $retArray;
}

function btn_download($uri, $name)
{
    return anchor($uri, "<i class='fa fa-download'></i>", "class='btn btn-success btn-custom mrg' data-placement='top' data-toggle='tooltip' title='".$name."'");
}

function app_date($date, $format=TRUE)
{
    if($date) {
        if($format) {
            return date('d-M-Y', strtotime($date)); 
        } else {
            return date('d M Y', strtotime($date)); 
        }
    }
    return '';
}

function app_time($time)
{
    if($time) {
        return date('h:i A', strtotime($time));
    }
    return '';
}

function app_datetime($datetime)
{
    if($datetime) {
        return date('d-M-Y h:i A', strtotime($datetime));
    }
    return '';
}

function app_date_time($date, $time)
{
    if($date && $time) {
        $datetime = $date.' '. $time;
        return date('d-M-Y h:i A', strtotime($datetime));
    }
    return '';
}

function btn_attendance_radio($id, $method, $class, $name, $title, $value)
{
    return '<div class="form-check form-check-inline">
        <input class="'.$class.' form-check-input" '.$method.' name="'.$name.'" type="radio" id="'.$id.'" value="'.$value.'">
        <label class="form-check-label" for="'.$id.'">'.$title.'</label>
    </div>';
}

function btn_download_file($uri, $name, $lang)
{
    return anchor($uri, $name, "class='btn btn-success btn-custom mrg' data-placement='top' data-toggle='tooltip' title='".$lang."'");
}

function btn_download_file_only($uri, $name)
{
    return anchor($uri, $name, 'class="td-download-style"');
}

function permissionChecker($data)
{
    $CI = & get_instance();
    $sessionPermission = $CI->session->userdata('master_permission_set');
    if(isset($sessionPermission[$data]) && $sessionPermission[$data] == 'yes') {
        return true;
    }
    return false;
}

function visibleButton($uri)
{
    $explodeUri = explode('/', $uri);
    $permission = $explodeUri[0].'_'.$explodeUri[1];
    if(permissionChecker($permission)) {
        return TRUE;
    }
    return false;
}

function display_menu($nodes, &$menu)
{
    $CI = & get_instance();

    foreach ($nodes as $key => $node) {
        $leftIcon = '<i class="fa arrow"></i>';

        $f = 0;
        if(isset($node['child'])) {
            $f = 1;
        }

        if(permissionChecker($node['link']) || ($node['link'] == '#' && $f) ) {
            if($f && inicompute($node['child']) == 1) {
                $f = 0;
                $node = current($node['child']);
            }

            $active = '';
            if(site_url($node['link']) == site_url($CI->uri->segment(1))) {
                $active = 'active';
            }

            $menu .= '<li class="'.$active.'">';
            $menu .= anchor($node['link'], '<i class="fa '.($node['icon'] != NULL ? $node['icon'] : 'fa-home').'"></i> '. ($CI->lang->line('menu_'.$node['name']) != NULL ? $CI->lang->line('menu_'.$node['name']) : $node['name']).($f ? $leftIcon : ''));
                if ($f) {
                    $menu .= '<ul class="sidebar-nav">';
                        display_menu($node['child'],$menu);
                    $menu .= "</ul>";
                }
            $menu .= "</li>";
        }
    }
}

function btn_sm_print($name, $divName = 'printablediv')
{
    return '<button class="btn btn-primary" onclick="javascript:printDiv(\''.$divName.'\')"><span class="fa fa-print"></span> '.$name.'</button>';
}

function btn_sm_pdf($uri, $name)
{
    return anchor($uri, '<i class="fa fa-file"></i> '.$name, 'class="btn btn-primary view-btn pdfurl"  role="button" target="_blank"');
}

function btn_sm_edit($permission, $uri, $name)
{
    if(permissionChecker($permission)) {
        return anchor($uri, '<i class="fa fa-edit"></i> '.$name, 'class="btn btn-primary view-btn" role="button"');
    }
    return '';
}

function btn_sm_modal_edit($permission, $id = 0, $name)
{
    if(permissionChecker($permission)) {
        echo  "<button id='".$id."' class='btn btn-primary editModalBtn' data-toggle='modal' data-target='#editModal'><span class='fa fa-edit' ></span> ".$name."</button>";
    }
    return '';
}

function btn_sm_mail($name, $target = 'mail')
{
    return '<button class="btn btn-primary" data-toggle="modal" data-target="#'.$target.'"><span class="fa fa-envelope-o"></span> '.$name.'</button>';
}

function btn_sm_delete($uri, $name)
{
    return anchor($uri, "<i class='fa fa-trash-o'></i> ".$name,
        array(
            'onclick' => "return confirm('you are about to delete a record. This cannot be undone. are you sure?')",
            'class' => 'btn btn-maroon mrg bg-maroon-light',
            'data-placement' => 'top',
            'data-toggle' => 'tooltip',
            'title' => $name
        )
    );
}

function btn_sm_download($permission, $uri, $filename, $name)
{
    if(permissionChecker($permission)) {
        if($filename != '') {
            return anchor($uri, "<i class='fa fa-download'></i> ".$name, "class='btn btn-primary' style='text-decoration: none;' role='button'");
        }
        return '';
    }
    return '';
}

function btn_invoice($uri, $name)
{
    return anchor($uri, "<i class='fa fa-credit-card'></i>", "class='btn btn-primary btn-custom mrg' data-placement='top' data-toggle='tooltip' data-original-title='".$name."'");
}

function featureheader($generalsettings)
{
    $CI = & get_instance();
    echo '<div class="view-header-area">';
        echo '<div class="view-header-area-site-logo">';
            echo '<img class="view-header-area-site-logo-img" src="'.base_url('uploads/general/'.$generalsettings->logo).'" alt="">';
        echo '</div>';
        echo '<div class="view-header-area-site-title">';
            echo '<h2>'.$generalsettings->system_name.'</h2>';
            echo '<address>';
                echo $generalsettings->address.'<br/>';
                echo '<b>'.$CI->lang->line('topbar_email').':</b>'.$generalsettings->email.'<br/>';
                echo '<b>'.$CI->lang->line('topbar_phone').':</b>'.$generalsettings->phone;
            echo '</address>';
        echo '</div>';
    echo '</div>';
}

function featurefooter($generalsettings) {
    $CI = & get_instance();
    echo '<div class="view-footer-area">';
        echo '<img class="view-footer-area-logo" src="'.base_url('uploads/general/'.$generalsettings->logo).'" alt="">';
        echo '<p class="view-footer-area-copyright">'.$generalsettings->footer_text.' | '.$CI->lang->line('topbar_hotline').'<b> : </b>'.$generalsettings->phone.'</p>';
    echo '</div>';
}

function reportheader($generalsettings)
{
    $CI = & get_instance();
    echo '<div class="report-header-area">';
        echo '<div class="report-logo-area">';
            echo '<img class="report-logo-img" src="'.base_url('uploads/general/'.$generalsettings->logo).'" alt="">';
        echo '</div>';
        echo '<div class="report-title-area">';
            echo '<h2>'.$generalsettings->system_name.'</h2>';
            echo '<address>';
                echo $generalsettings->address.'<br/>';
                echo '<b>'.$CI->lang->line('topbar_email').':</b> '.$generalsettings->email.'<br/>';
                echo '<b>'.$CI->lang->line('topbar_phone').':</b> '.$generalsettings->phone;
            echo '</address>';
        echo '</div>';
    echo '</div>';
}

function reportfooter($generalsettings)
{
    $CI = & get_instance();
    echo '<div class="report-footer-area">';
        echo '<img class="report-footer-img" src="'.base_url('uploads/general/'.$generalsettings->logo).'" alt="">';
        echo '<p class="report-footer-copyright">'.$generalsettings->footer_text.' | '.$CI->lang->line('topbar_hotline').'<b> : </b>'.$generalsettings->phone.'</p>';
    echo '</div>';
}

function agetobirthday($day, $month, $year)
{
    $date = date('Y-m-d');
    if(!empty($day) || !empty($month) || !empty($year)) {
        $stringDate = '';
        if(!empty($day)) {
            $stringDate .= '- '.$day.' day ';
        }

        if(!empty($month)) {
            $stringDate .= '- '.$month.' month ';
        }

        if(!empty($year)) {
            $stringDate .= '- '.$year.' year';
        }

        $date = date('Y-m-d', strtotime($stringDate, strtotime(date('Y-m-d'))));
    }

    return $date;
}

function stringtoage($day, $month, $year)
{
    if(!empty($day) || !empty($month) || !empty($year)) {
        $string = '';
        $CI = & get_instance();

        if(!empty($year)) {
            $string .= $year.(($year > 1) ? ' '.$CI->lang->line('topbar_years').' ' : ' '.$CI->lang->line('topbar_year').' ');
        }

        if(!empty($month)) {
            $string .= $month.(($month > 1) ? ' '.$CI->lang->line('topbar_months').' ' : ' '.$CI->lang->line('topbar_month').' ');
        }

        if(!empty($day)) {
            $string .= $day.(($day > 1) ? ' '.$CI->lang->line('topbar_days') : ' '.$CI->lang->line('topbar_day'));
        }

        return $string;
    }
    return '';
}

function app_currency_format($number)
{
    return number_format($number, 2, '.', '');
}

function btn_printReport($permission, $name, $DivID = 'printablediv') 
{
    if(permissionChecker($permission)) {
        return '<button class="btn btn-ini-default" onclick="javascript:printDiv'."('".$DivID."')".'"><span class="fa fa-print"></span> '. $name . '</button>';
    }
    return '';
}

function btn_sentToMailReport($permission, $name) 
{
    if(permissionChecker($permission)) {
        return '<button class="btn btn-ini-default" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> '. $name . '</button>';
    }
    return '';
}

function btn_pdfPreviewReport($permission, $uri, $name)
{
    if(permissionChecker($permission)) {
        return anchor($uri, "<i class='fa fa-file'></i> ".$name, 'class="btn btn-ini-default pdfurl" target="_blank"');
    }
    return '';   
}

function btn_xlsxReport($permission, $uri, $name)
{
    if(permissionChecker($permission)) {
        return anchor($uri, "<i class='fa fa-file'></i> ".$name, 'class="btn btn-ini-default xmlurl" target="_blank"');
    }
    return '';   
} 

function app_year_lists()
{
    return range(date('Y'), 2000);
}   

function table_row_bgcolor($item_expire_date, $setting_expire_date)
{
    $item_expire_date  = strtotime($item_expire_date);
    $current_date      = strtotime(date('d-m-Y'));

    if($item_expire_date > $setting_expire_date) {
        return 'bg-success';
    } elseif ($item_expire_date > $current_date) {
        return 'bg-warning';
    } elseif ($item_expire_date < $current_date) {
        return 'bg-danger';
    }
}

function generate_qrcode($text = "Hi", $filename = "default", $folder="idQRcode")
{
    $CI = & get_instance();
    $CI->load->library('qrcodegenerator');
    $CI->qrcodegenerator->generate_qrcode($text,$filename, $folder);
}

function decrement_letter($alphabet)
{
    return chr(ord($alphabet) - 1);
}

function getUser($userID)
{
    if((int)$userID) {
        $CI =& get_instance();
        $CI->load->model('user_m');
        $user = $CI->user_m->get_single_user(['userID' => $userID]);
        if(inicompute($user)) {
            return $user;
        }
        return [];
    }
    return [];
}

function multiArraySortForIntData($arrays, $name)
{
    $generateArray = [];
    if(inicompute($arrays)) {
        $i = 0;
        foreach ($arrays as $array) {
            if((int) $array->$name) {
                $generateArray[$i] = $array->$name;
                $i++;
            }
        }
    }
    return $generateArray;
}

function generateUsernameForPatient($user, $patientID)
{
    if(in_array($patientID, $user)) {
        $patientID++;
        return generateUsernameForPatient($user, $patientID);
    }
    return (int) $patientID;
}

function headerAssets($headerassets) {
    if(isset($headerassets)) {
        foreach ($headerassets as $assetstype => $headerasset) {
            if($assetstype == 'css') {
                if(inicompute($headerasset)) {
                    foreach ($headerasset as $keycss => $css) {
                        echo '<link rel="stylesheet" href="'.base_url($css).'">'."\n";
                    }
                }
            } elseif($assetstype == 'js') {
                if(inicompute($headerasset)) {
                    foreach ($headerasset as $keyjs => $js) {
                        echo '<script type="text/javascript" src="'.base_url($js).'"></script>'."\n";
                    }
                }
            }
        }
    }
}



function jsonChecker($data)
{
    return ((json_decode($data) !== null) ? true : false);
}

function jsStack($array = [])
{
    $object = & get_instance();
    $jsList = ['THEMEBASEURL' => base_url('/'), 'CSRFNAME' => $object->security->get_csrf_token_name(), 'CSRFHASH' => $object->security->get_csrf_hash()];
    if(array_keys($array) !== range(0, count($array) - 1)) {
        $jsList = array_merge($jsList, $array);
    } else {
        echo "The array is not associative \n";
    }

    if(inicompute($jsList)) {
        foreach ($jsList as $jsKey => $js) {
            if(jsonChecker($js)) {
                echo 'const '.escapeString($jsKey) ." = ".$js."; ";
            } elseif(is_numeric($js)) {
                echo 'const '.escapeString($jsKey) ." = ".escapeString($js)."; ";
            } else {
                echo 'const '.escapeString($jsKey) ." = '".escapeString($js)."'; ";
            }
        }
    }
}
