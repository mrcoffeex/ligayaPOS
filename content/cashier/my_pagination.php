<?php  

    $search_data=$link->query($query_one);
    $count_results=$search_data->num_rows;

    $sql = $query_two;
    $query=$link->query($sql);
    $row = mysqli_fetch_row($query);
    $rows = $row[0];
    $page_rows = $my_num_rows;
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
    $sql = $query_three." $limit";
    $query = mysqli_query($link, $sql);
    $textline1 = "Testimonials (<b>$rows</b>)";
    $textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
    $paginationCtrls = '';
    if($last != 1){
        if ($pagenum > 1) {
            $previous = $pagenum - 1;
            $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'" ><i class="fa fa-chevron-circle-left"></i></a></li>';
            for($i = $pagenum-4; $i < $pagenum; $i++){
                if($i > 0){
                    $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'" >'.$i.'</a></li>';
                }
            }
        }
        $paginationCtrls .= '<li class="active"><a>'.$pagenum.'</a></li>';
        for($i = $pagenum+1; $i <= $last; $i++){
            $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'" >'.$i.'</a></li>';
            if($i >= $pagenum+4){
                break;
            }
        }
        if ($pagenum != $last) {
            $next = $pagenum + 1;
            $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'" ><i class="fa fa-chevron-circle-right"></i></a></li>';
        }
    }

?>