<?php
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

function getcountrow($conn, $tablename, $field, $qryfield, $value)
{
    $query = "SELECT COUNT($field) as total FROM $tablename WHERE $qryfield='$value'";
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
