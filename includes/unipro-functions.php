<?php


function unipro_program_area_of_study($s = '')
{
  $area_of_study = ['Applied Sciences','Archaeologists','Architecture &amp; Design','Arts, Communications &amp; Media','Aviation','Business, Economics &amp; Administrative Sciences','Conservatory','Engineering &amp; Technology','English for Academic Studies','Languages','Law, Politics, Social, Community Service &amp; Teach','Medicine, Dentistry &amp; Health Sciences','Philosophy','Sciences, Humanities &amp; Social Sciences'];
  ob_start();
  ?>
  <label for="area_of_study"><?=_tr('Area of Study')?>:</label>
  <select class="form-control" name="program_area_of_study" id="program_area_of_study">
    <option value=""><?=_tr('Area of Study')?></option>
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
  <label for="degree"><?=_tr('Degree')?>:</label>
  <select class="form-control" name="program_degree" id="program_degree">
    <option value=""> -- <?=_tr('Select Degree')?> -- </option>
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

register_activation_hook(__FILE__, 'unipro_security_check');

function enqueue_external_css() {
  wp_enqueue_style('external-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css', array(), '1.0');
}
add_action('wp_enqueue_scripts', 'enqueue_external_css');

function enqueue_external_js() {
  wp_enqueue_script('external-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_external_js');


function enqueue_cdn_js() {
  wp_enqueue_script('cdn-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_cdn_js');

function _tr($str)
{
  if (function_exists('icl_object_id') && function_exists('icl_get_languages')) {
    $current_lang = ICL_LANGUAGE_CODE;

      $trans=array();

      $trans['lang']=array('en'=>'English','ar'=>'Arabic');
      $trans['Keyword']=array('en'=>'Keyword','ar'=>'الكلمة الرئيسية');

      $trans['Area of Study']=array('en'=>'Area of Study','ar'=>'مجال التعليم');
      $trans['Degree']=array('en'=>'Degree','ar'=>'درجة');
      $trans['Select Degree']=array('en'=>'Select Degree','ar'=>'حدد الدرجة');

      $trans['Program Name']=array('en'=>'Program Name','ar'=>'إسم البرنامج');
      $trans['Fee']=array('en'=>'Fee','ar'=>'مصاريف');
      $trans['Language']=array('en'=>'Language','ar'=>'لغة');
      $trans['Duration']=array('en'=>'Duration','ar'=>'مدة');

      $trans['No universities found']=array('en'=>'No universities found','ar'=>'لم يتم العثور على جامعات');
      $trans['Region']=array('en'=>'Region','ar'=>'منطقة');
      $trans['University']=array('en'=>'University','ar'=>'جامعة');
      $trans['Language']=array('en'=>'Language','ar'=>'لغة');
      $trans['Min Fee']=array('en'=>'Min Fee','ar'=>'الحد الأدنى للرسوم');
      $trans['Max Fee']=array('en'=>'Max Fee','ar'=>'الحد الأقصى للرسوم');
      $trans['Sort by']=array('en'=>'Sort by','ar'=>'ترتيب حسب');
      $trans['Program Name']=array('en'=>'Program Name','ar'=>'إسم البرنامج');
      $trans['Search']=array('en'=>'Search','ar'=>'يبحث');
      $trans['Reset']=array('en'=>'Reset','ar'=>'إعادة ضبط');
      $trans['No programs found']=array('en'=>'No programs found','ar'=>'لم يتم العثور على برامج');
      $trans['Standard Fee']=array('en'=>'Standard Fee','ar'=>'الرسوم القياسية');
      $trans['Discounted Fee']=array('en'=>'Discounted Fee','ar'=>'رسوم مخفضة');
      $trans['View School']=array('en'=>'View School','ar'=>'مشاهدة المدرسة');
      $trans['All Universities']=array('en'=>'All Universities','ar'=>'جميع الجامعات');

      $trans['All regions']=array('en'=>'All regions','ar'=>'كل المناطق');
      $trans['Searching']=array('en'=>'Searching','ar'=>'يبحث');
      $trans['View Program']=array('en'=>'View Program','ar'=>'مشاهدة البرنامج');

      $trans['Thesis']=array('en'=>'Thesis','ar'=>'أُطرُوحَة');

      $trans['Select a university']=array('en'=>'Select a university','ar'=>'اختر جامعة');
      $trans['Address']=array('en'=>'Address','ar'=>'عنوان');
      $trans['Website']=array('en'=>'Website','ar'=>'موقع إلكتروني');
      $trans['Location']=array('en'=>'Location','ar'=>'موقع');
      $trans['Associate']=array('en'=>'Associate','ar'=>'شريك');
      $trans['Bachelor']=array('en'=>'Bachelor','ar'=>'بكالوريوس');
      $trans['Doctorate']=array('en'=>'Doctorate','ar'=>'دكتوراه');
      $trans['Foundation Year']=array('en'=>'Foundation Year','ar'=>'سنة التأسيس');
      $trans['Language Course']=array('en'=>'Language Course','ar'=>'دورة لغة');
      $trans['Master']=array('en'=>'Master','ar'=>'يتقن');
      $trans['Training Course']=array('en'=>'Training Course','ar'=>'دورة تدريبية');
      $trans['aaa']=array('en'=>'aaa','ar'=>'aaa');



      $str_t= $trans[$str][$current_lang];

  }else{
    $str_t = $str;
  }



  return $str_t;
}
