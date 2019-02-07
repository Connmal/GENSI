<?php

  $list = array (
    array(
      $_POST['nomem'],
      $_POST['code'],
      $_POST['age'],
      $_POST['q1'],
      $_POST['q2_1'],
      $_POST['q3_1'],
      $_POST['q4_1'],
      $_POST['q2_2'],
      $_POST['q3_2'],
      $_POST['q4_2'],
      $_POST['q2_3'],
      $_POST['q3_3'],
      $_POST['q4_3'],
      $_POST['q2_4'],
      $_POST['q3_4'],
      $_POST['q4_4'],
      $_POST['q2_5'],
      $_POST['q3_5'],
      $_POST['q4_5'],
      $_POST['q2_6'],
      $_POST['q3_6'],
      $_POST['q4_6'],
      $_POST['q2_7'],
      $_POST['q3_7'],
      $_POST['q4_7'],
      $_POST['q2_8'],
      $_POST['q3_8'],
      $_POST['q4_8'],
      $_POST['q2_9'],
      $_POST['q3_9'],
      $_POST['q4_9'],
      $_POST['q2_10'],
      $_POST['q3_10'],
      $_POST['q4_10'],
      $_POST['q5'],
      $_POST['q7'],
      $_POST['q8'],
      $_POST['q9'],
      $_POST['q10'],
      $_POST['q11'],
      $_POST['q12'],
      $_POST['q13'],
      $_POST['q14'],
      $_POST['q15'],
      $_POST['q16'],
      $_POST['q17'],
      $_POST['q18'],
      $_POST['q19'],
      $_POST['q20'],
      $_POST['q21'],
      $_POST['q22'],
      $_POST['q23'],
      $_POST['q24'],
      $_POST['q25'],
      $_POST['q26'],
      $_POST['q27'],
      $_POST['q28'],
      $_POST['q29'],
      $_POST['q30'],
      $_POST['q31'],
      $_POST['q32'],
      $_POST['q33'],
      $_POST['q34'],
      $_POST['q35'],
      $_POST['q36'],
      $_POST['q37'],
      $_POST['q38'],
      $_POST['q39'],
      $_POST['q40'])
  );

  $fp = fopen('results/data.csv', 'a');

  foreach ($list as $fields) {
    fputcsv($fp, $fields);
  }

  fclose($fp);

?>
