<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
<link rel="shortcut icon" href="">
<title>那好管理系统</title>

<link href="<?php echo static_url("/admin/css/bootstrap.css"); ?>" rel="stylesheet">
<link href="<?php echo static_url("/admin/css/nav.css"); ?>" rel="stylesheet">
<link href="<?php echo static_url("/admin/css/bootstrap-datetimepicker.min.css"); ?>" rel="stylesheet">
<script type="text/javascript" src="<?php echo static_url('/admin/js/jquery-1.4.4.js'); ?>"></script>

<link href="<?php echo static_url(STATIC_ADMIN_CSS_BOOTSTRAP); ?>" rel="stylesheet">
<link href="<?php echo static_url(STATIC_ADMIN_CSS_NAV); ?>" rel="stylesheet">
<?php
$arr_css_config = config_item('static_admin');
if(isset($arr_css_config['css'][$this->current['controller']])){
    $arr_css = $arr_css_config['css'][$this->current['controller']];
    o($arr_css);
    foreach($arr_css as $k => $v){
        echo '<link href="'.static_url($v).'" rel="stylesheet">';
    }
}
?>

