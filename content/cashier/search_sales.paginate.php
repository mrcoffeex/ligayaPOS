<?php
    
    $query1 = "SELECT COUNT(gy_transdet_id) From gy_trans_details 
                LEFT JOIN gy_transaction 
                On 
                gy_trans_details.gy_trans_code = gy_transaction.gy_trans_code 
                Where 
                gy_transaction.gy_trans_type = 1 
                AND 
                gy_transaction.gy_trans_status = 1 
                AND 
                gy_transdet_type = 1 
                AND 
                gy_transaction.gy_user_id = '$user_id' 
                AND 
                CONCAT
                (
                    gy_transaction.gy_trans_code, 
                    gy_transaction.gy_trans_date, 
                    gy_transaction.gy_trans_custname
                ) LIKE '%$search_text%'
                Order By gy_transaction.gy_trans_date DESC";
    $query2 = "SELECT * From gy_trans_details 
                LEFT JOIN gy_transaction 
                On 
                gy_trans_details.gy_trans_code = gy_transaction.gy_trans_code 
                Where 
                gy_transaction.gy_trans_type = 1 
                AND 
                gy_transaction.gy_trans_status = 1 
                AND 
                gy_transdet_type = 1 
                AND 
                gy_transaction.gy_user_id = '$user_id' 
                AND 
                CONCAT
                (
                    gy_transaction.gy_trans_code, 
                    gy_transaction.gy_trans_date, 
                    gy_transaction.gy_trans_custname
                ) LIKE '%$search_text%'
                Order By gy_transaction.gy_trans_date DESC";
    
    $page_rows = 20;

    $sql = $query1;
    $query=$link->query($sql);
    $row = mysqli_fetch_row($query);
    $rows = $row[0];
    $last = ceil($rows/$page_rows);

    if($last < 1){
        $last = 1;
    }

    $pagenum = 1;
    if(isset($_GET['pn'])){
        $pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
    }

    if ($pagenum < 1) { 
        $pagenum = 1; 
    } else if ($pagenum > $last) { 
        $pagenum = $last; 
    }
    
    $limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
    $sql = $query2." $limit";
    $query = mysqli_query($link, $sql);

    $countRes = $query->num_rows;

    $paginationCtrls = '';
    if($last != 1){
        if ($pagenum > 1) {
            $previous = $pagenum - 1;
            $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].
            '?pn='.$previous.
            '&search_text='.$search_text.
            '&returndate='.$returndate.
            '" ><i class="fa fa-chevron-circle-left"></i></a></li>';
            for($i = $pagenum-4; $i < $pagenum; $i++){
                if($i > 0){
                    $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].
                    '?pn='.$i.
                    '&search_text='.$search_text.
                    '&returndate='.$returndate.
                    '" >'.$i.'</a></li>';
                }
            }
        }
        $paginationCtrls .= '<li class="active"><a>'.$pagenum.'</a></li>';
        for($i = $pagenum+1; $i <= $last; $i++){
            $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].
            '?pn='.$i.
            '&search_text='.$search_text.
            '&returndate='.$returndate.
            '" >'.$i.'</a></li>';
            if($i >= $pagenum+4){
                break;
            }
        }
        if ($pagenum != $last) {
            $next = $pagenum + 1;
            $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].
            '?pn='.$next.
            '&search_text='.$search_text.
            '&returndate='.$returndate.
            '" ><i class="fa fa-chevron-circle-right"></i></a></li>';
        }
    }

?>