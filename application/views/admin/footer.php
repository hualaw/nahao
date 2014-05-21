<script type="text/javascript" src="<?php echo STATIC_ADMIN_JS_JQUERY_MIN;?>"></script>
<script type="text/javascript" src="<?php echo static_url(STATIC_ADMIN_JS_BOOTSTRAP_MIN); ?>"></script>

<?php
$arr_js_config = config_item('static_admin');
$arr_js = $arr_js_config['js'][$this->current['controller']];
foreach($arr_js as $k => $v){
    echo '<script type="text/javascript" src="'.static_url($v).'"></script>';
}
?>
