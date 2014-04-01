<?php

require_once 'db_connect_params.php';
require_once '../script/niqud.php';
function create_findpsuq_niqud_table() {
    global $db_host; global $db_name; global $db_pass; global $db_user;
    $time = $_SERVER["REQUEST_TIME"];
    
    $tnkDB = new mysqli ($db_host, $db_user, $db_pass, $db_name);

    $tnkDB->query("ALTER TABLE findpsuq CONVERT TO CHARACTER SET utf8");
    $tnkDB->query("ALTER TABLE psuqim_niqud_milim CONVERT TO CHARACTER SET utf8");
 
    $tnkDB->query("DROP TABLE IF EXISTS findpsuq_mnqd");
    $tnkDB->query("CREATE TABLE findpsuq_mnqd AS (SELECT * FROM findpsuq)");
    $tnkDB->query("ALTER TABLE findpsuq_mnqd ENGINE=MyISAM, ADD COLUMN id MEDIUMINT NOT NULL AUTO_INCREMENT primary key");

      
      $tnkDB->set_charset('utf8');
      $res = $tnkDB->query("SELECT * FROM findpsuq_mnqd ");
      $niq_res = $tnkDB->query("SELECT * FROM psuqim_niqud_milim");
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

    $tnkDB->close();
}

create_findpsuq_niqud_table();