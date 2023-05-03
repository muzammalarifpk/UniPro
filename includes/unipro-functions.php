<?php


function unipro_program_area_of_study($s = '')
{
  $area_of_study = ['Applied Sciences','Archaeologists','Architecture &amp; Design','Arts, Communications &amp; Media','Aviation','Business, Economics &amp; Administrative Sciences','Conservatory','Engineering &amp; Technology','English for Academic Studies','Languages','Law, Politics, Social, Community Service &amp; Teach','Medicine, Dentistry &amp; Health Sciences','Philosophy','Sciences, Humanities &amp; Social Sciences'];
  ob_start();
  ?>
  <label for="area_of_study">Area of Study:</label> <br />
  <select class="form-control" name="program_area_of_study" id="program_area_of_study">
    <option value="">Area of Study</option>
    <?php

      foreach ($area_of_study as $key => $value) {
        // code...


    ?>
    <option value="<?=$value?>" <?php if($value == $s){ echo 'selected="selected"';}?>><?=$value?></option>
    <?php
      }
    ?>
    </select>

  <?php
  return ob_get_clean();
}


function unipro_program_degree($d = '')
{
  $degree = ['Associate','Bachelor','Doctorate','Foundation Year','Language Course','Master','Training Course'];
  ob_start();
  ?>
  <label for="degree">Degree:</label> <br />
  <select class="form-control" name="program_degree" id="program_degree">
    <option value=""> -- Select Degree -- </option>
    <?php

      foreach ($degree as $key => $value) {
        // code...


    ?>
    <option value="<?=$value?>" <?php if($value == $d){ echo 'selected="selected"';}?>><?=$value?></option>
    <?php
      }
    ?>
    </select>

  <?php
  return ob_get_clean();
}
