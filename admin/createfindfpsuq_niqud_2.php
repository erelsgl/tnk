<?php

require_once 'db_connect_params.php';
require_once '../script/niqud.php';
function create_findpsuq_niqud_table() {
    global $db_host; global $db_name; global $db_pass; global $db_user;
    $time = $_SERVER["REQUEST_TIME"];
    
    $tnkDB = new mysqli ($db_host, $db_user, $db_pass, $db_name);
    
    $tnkDB->query("ALTER TABLE findpsuq CONVERT TO CHARACTER SET utf8");
    $tnkDB->query("DROP TABLE IF EXISTS findpsuq_mnqd CONVERT TO CHARACTER SET utf8");
    $tnkDB->query("ALTER TABLE psuqim_niqud_milim CONVERT TO CHARACTER SET utf8");
    $tnkDB->query("CREATE TABLE IF NOT EXISTS findpsuq_mnqd
                   AS (SELECT * FROM findpsuq)");
    $tnkDB->query("ALTER TABLE findpsuq_mnqd ADD COLUMN id INT NOT NULL AUTO_INCREMENT PRIMARY KEY");
      
      
      $tnkDB->set_charset('utf8');
      $books_res = $tnkDB->query("SELECT DISTINCT KOTRT_SFR FROM findpsuq_mnqd ");
      while ($bookrow = mysqli_fetch_array($books_res)) {
            $niq_res = $tnkDB->query("SELECT * FROM psuqim_niqud_milim WHERE book_name = '$bookrow[0]'");
            $res = $tnkDB->query("SELECT * FROM findpsuq_mnqd WHERE KOTRT_SFR = '$bookrow[0]'");
            $niq_row = mysqli_fetch_array($niq_res);
            while ($row = mysqli_fetch_array($res)) {
                $text_mnqd = "";
                $psuq_num = $niq_row["verse_number"];
                do {
                    $text_mnqd .= $niq_row["word_niqud"];
                    $niq_row = mysqli_fetch_array($niq_res);
                } while ($psuq_num == $niq_row["verse_number"]);
                $id = $row["id"];
                echo $text_mnqd;
                $tnkDB->query("UPDATE findpsuq_mnqd 
                               SET verse_text = '$text_mnqd'
                               WHERE id = $id");


                set_time_limit(30 );
          
      }
      }

    $tnkDB->close();
}

create_findpsuq_niqud_table();