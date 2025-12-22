<?php
function get_term($gaweek)
{
    $term = "";
    if ($gaweek < 33) {
        $term = "Very pre-term";
    } else if ($gaweek >= 33 && $gaweek < 37) {
        $term = "Pre-term";
    } else if ($gaweek >= 37 && $gaweek < 39) {
        $term = "Early-term";
    } else if ($gaweek >= 39 && $gaweek < 41) {
        $term = "Full-term";
    } else if ($gaweek >= 41) {
        $term = "Late-term";
    } else {
        $term = "";
    }
    return $term;
}

function getcountdis($conn, $tablename, $field, $qryfield, $value)
{
    $query = "SELECT  COUNT(DISTINCT($field)) as total FROM $tablename WHERE $qryfield='$value' AND progress_of_child IN ('Discharge','Referred','LAMA','Death')";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_object($result);

    return $row->total;
}

function getcount($conn, $tablename, $field, $qryfield, $value, $qryfield1 = '', $value1 = '')
{
    $qryv = '';
    if ($qryfield1 != '') {
        $qryv = " AND $qryfield1='$value1'";
    }
    $query = "SELECT  COUNT(DISTINCT($field)) as total FROM $tablename WHERE $qryfield='$value' $qryv";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_object($result);

    return $row->total;
}

function getcountrow($conn, $tablename, $field, $qryfield, $value, $ectr = '')
{
    $query = "SELECT COUNT($field) as total FROM $tablename WHERE $qryfield '$value' $ectr";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_object($result);

    return $row->total;
}

function getcountrowid($conn, $tablename, $field, $qryfield, $value)
{
    $safe_value = mysqli_real_escape_string($conn, $value);
    $query = "SELECT COUNT(m.$field) AS total FROM $tablename AS m JOIN registration_form AS r ON m.registration_id = r.id WHERE m.$qryfield = '$safe_value'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_object($result);
    return $row->total;
}


function getone($conn, $tablename, $field, $qryfield, $value)
{
    $query = "SELECT $field as total FROM $tablename WHERE $qryfield='$value'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_object($result);

    return $row->total;
}

function getoneless($conn, $tablename, $field, $qryfield, $value)
{
    $query = "SELECT COUNT($field) as total FROM $tablename WHERE $qryfield $value";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_object($result);

    return $row->total;
}

// function getcounttotal($conn, $tablename, $field)
// {
//     $query = "SELECT COUNT($field) as total FROM $tablename WHERE status='1'";
//     $result = mysqli_query($conn, $query);
//     $row = mysqli_fetch_object($result);

//     return $row->total;
// }

function getcounttotal($conn, $tablename, $field)
{
    $query = "SELECT COUNT($field) as total FROM $tablename WHERE status='1'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_object($result);

    return $row->total;
}

function getcountforToday($conn, $tablename, $field, $qryfield, $value, $datecolumn)
{
    $today = date('Y-m-d');
    $query = "SELECT COUNT($field) as total FROM $tablename WHERE $qryfield='$value' and date($datecolumn)='$today'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_object($result);

    return $row->total;
}
function Multicolumns($conn, $tablename, $fields, $qryfeild1, $value1, $qryfeild2, $value2, $qryfeild3, $value3)
{
    //echo "select $fields from $tablename where $qryfeild1='".$value1."' and $qryfeild2='".$value2."'and $qryfeild3='".$value3."'";
    $sn = mysqli_query($conn, "select $fields from $tablename where $qryfeild1='" . $value1 . "' and $qryfeild2='" . $value2 . "'and $qryfeild3='" . $value3 . "'") or die(mysqli_error());
    $dn = mysqli_fetch_object($sn);
    return ($dn);
}

function getdatatwocondition($conn, $tablename, $field, $query_field, $val, $fval)
{
    $query = "SELECT $field FROM $tablename where $query_field = $val and type_of_time = '$fval'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_object($result);

    return $row->$field;
}


function pegination2($currentPage, $totalPages, $extraParams = [], $pageParam = 'page')
{
    // Start output buffer to capture HTML
    ob_start();

    // Build base query string with extra parameters
    $queryStr = function ($page) use ($extraParams, $pageParam) {
        $params = array_merge($extraParams, [$pageParam => $page]);
        return '?' . http_build_query($params);
    };

    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);

    return ob_get_clean();
}

function pagination1($currentPage, $totalPages, $pageParam = 'page', $extraParams = [])
{
    if ($totalPages <= 1) return '';

    $queryStr = function ($page) use ($pageParam, $extraParams) {
        $params = array_merge($extraParams, [$pageParam => $page]);
        return '?' . http_build_query($params);
    };

    $html = '<div class="pagination d-flex justify-content-end mt-2"><ul class="pagination">';

    // Previous
    $html .= '<li class="page-item ' . ($currentPage <= 1 ? 'disabled' : '') . '">
                <a class="page-link" href="' . $queryStr(max(1, $currentPage - 1)) . '">Previous</a>
              </li>';

    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);

    // Show "1" and "..." if needed
    if ($start > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $queryStr(1) . '">1</a></li>';
        if ($start > 2) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }

    // Loop through visible page numbers
    for ($i = $start; $i <= $end; $i++) {
        $active = $i == $currentPage ? 'active' : '';
        $html .= '<li class="page-item ' . $active . '"><a class="page-link" href="' . $queryStr($i) . '">' . $i . '</a></li>';
    }

    // Show "..." and last page if needed
    if ($end < $totalPages) {
        if ($end < $totalPages - 1) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        $html .= '<li class="page-item"><a class="page-link" href="' . $queryStr($totalPages) . '">' . $totalPages . '</a></li>';
    }

    // Next
    $html .= '<li class="page-item ' . ($currentPage >= $totalPages ? 'disabled' : '') . '">
                <a class="page-link" href="' . $queryStr(min($totalPages, $currentPage + 1)) . '">Next</a>
              </li>';

    $html .= '</ul></div>';

    return $html;
}

function getfallowup($conn, $tablename, $reg_id, $field1, $field2, $val)
{
    $query = "SELECT type_of_time, schedule_follow_up_date, date_of_visit, baby_weight, baby_length, baby_head_circumference, immunization_status, type_of_feed, tof_Breast_milk, tof_Animal_milk, tof_Formula_milk, tof_Water, tof_Others, other_feed, times_baby_breastfed, mode_of_feeding, check_health_examination, asha_check_following_infant, check_Weight, check_Temparature, check_Respiratory_rate, check_Pus_on_umbilicus, check_Eyes, check_Urine_frequency, check_none, who_wtage, who_lenage, who_wtlen, who_head_circum, who_per_wtage, who_per_lenage, who_per_wtlen, who_head_circum_per, fenton_wtage, fenton_lenage, fenton_head_circum, fenton_per_wtage, fenton_per_lenage, fenton_head_circum_per, intergrowth_wtage, intergrowth_lenage, intergrowth_head_circum, intergrowth_per_wtage, intergrowth_per_lenage, intergrowth_head_circum_per, intergrowth_classification, fenton_growth_classification, who_classification,DATEDIFF(date_of_visit, '" . $field1 . "') AS age_in_days,($field2 + FLOOR(DATEDIFF(date_of_visit, '" . $field1 . "') / 7)) AS total_weeks FROM $tablename WHERE registration_id = $reg_id and type_of_time = '$val'";
    $result = mysqli_query($conn, $query);
    $nomrows = mysqli_num_rows($result);
    $rowdatas = '';
    $datafoll = mysqli_fetch_object($result);
    if ($nomrows > 0) {

        $rowdatas = "<td>" . $datafoll->type_of_time . "</td>
<td>" . $datafoll->schedule_follow_up_date . "</td>
<td>" . $datafoll->date_of_visit . "</td>
<td>" . $datafoll->age_in_days . "</td>
<td>" . $datafoll->total_weeks . "</td>
<td>" . $datafoll->baby_weight . "</td>
<td>" . $datafoll->baby_length . "</td>
<td>" . $datafoll->baby_head_circumference . "</td>
<td>" . $datafoll->immunization_status . "</td>
<td>" . $datafoll->type_of_feed . "</td>
<td>" . $datafoll->tof_Breast_milk . "</td>
<td>" . $datafoll->tof_Animal_milk . "</td>
<td>" . $datafoll->tof_Formula_milk . "</td>
<td>" . $datafoll->tof_Water . "</td>
<td>" . $datafoll->tof_Others . "</td>
<td>" . $datafoll->other_feed . "</td>
<td>" . $datafoll->times_baby_breastfed . "</td>
<td>" . $datafoll->mode_of_feeding . "</td>
<td>" . $datafoll->check_health_examination . "</td>
<td>" . $datafoll->asha_check_following_infant . "</td>
<td>" . $datafoll->check_Weight . "</td>
<td>" . $datafoll->check_Temparature . "</td>
<td>" . $datafoll->check_Respiratory_rate . "</td>
<td>" . $datafoll->check_Pus_on_umbilicus . "</td>
<td>" . $datafoll->check_Eyes . "</td>
<td>" . $datafoll->check_Urine_frequency . "</td>
<td>" . $datafoll->check_none . "</td>";
        if ($val == '6 Month' || $val == '1 Year') {
            $rowdatas .= "<td>" . $datafoll->started_feeding_at_home . "</td>
<td>" . $datafoll->offered_food_items . "</td>
<td>" . $datafoll->cereals . "</td>
<td>" . $datafoll->legume_and_nuts . "</td>
<td>" . $datafoll->vitamin_a_fruits_and_vegetables . "</td>
<td>" . $datafoll->other_fruits_and_vegitables . "</td>
<td>" . $datafoll->milk_and_milk_product . "</td>
<td>" . $datafoll->egg . "</td>
<td>" . $datafoll->meat_or_poultry_or_fish . "</td>
<td>" . $datafoll->junk_items . "</td>";
        }

        $rowdatas .= "<td>" . $datafoll->who_wtage . "</td>
<td>" . $datafoll->who_lenage . "</td>
<td>" . $datafoll->who_wtlen . "</td>
<td>" . $datafoll->who_head_circum . "</td>
<td>" . $datafoll->who_per_wtage . "</td>
<td>" . $datafoll->who_per_lenage . "</td>
<td>" . $datafoll->who_per_wtlen . "</td>
<td>" . $datafoll->who_head_circum_per . "</td>
<td>" . $datafoll->fenton_wtage . "</td>
<td>" . $datafoll->fenton_lenage . "</td>
<td>" . $datafoll->fenton_head_circum . "</td>
<td>" . $datafoll->fenton_per_wtage . "</td>
<td>" . $datafoll->fenton_per_lenage . "</td>
<td>" . $datafoll->fenton_head_circum_per . "</td>
<td>" . $datafoll->intergrowth_wtage . "</td>
<td>" . $datafoll->intergrowth_lenage . "</td>
<td>" . $datafoll->intergrowth_head_circum . "</td>
<td>" . $datafoll->intergrowth_per_wtage . "</td>
<td>" . $datafoll->intergrowth_per_lenage . "</td>
<td>" . $datafoll->intergrowth_head_circum_per . "</td>
<td>" . $datafoll->intergrowth_classification . "</td>
<td>" . $datafoll->fenton_growth_classification . "</td>
<td>" . $datafoll->who_classification . "</td>";
    } else {
        $rowdatas = "<td>" . $val . "</td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>";
        if ($val == '6 Month' || $val == '1 Year') {
            $rowdatas .= "<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>";
        }
        $rowdatas .= "<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>";
    }

    return $rowdatas;
}

function getname($conn, $tablename, $field, $w_field, $val)
{
    $query = "SELECT $field FROM $tablename WHERE $w_field = $val";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_object($result);
    return $row->$field;
}
