
<script type="text/javascript" src="http://cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo static_url("/admin/js/bootstrap.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo static_url("/admin/js/bootstrap-datetimepicker.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo static_url("/admin/js/mod/admin.js"); ?>"></script>

<script type="text/javascript">
    $(function(){
    $('#datetimepicker').datetimepicker({
        format: "yyyy-MM-dd hh:ii",
        language: 'cn',
        autoclose : true,
//        pickDate: true,
//        pickTime: true,
        hourStep: 1,
        minuteStep: 15,
        secondStep: 30,
        inputMask: true
    });
    });
</script>
