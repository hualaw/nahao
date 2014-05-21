<botton class="btn btn-primary btn" data-toggle="modal" data-target="#admin_create_modal">
    新增管理员
</botton>
<!--<div class="input-append date form_datetime">
    <input size="16" type="text" value="" readonly>
    <span class="add-on"><i class="icon-th"></i></span>
</div>-->
<br><br>
<form class="form-inline" role="form">
    <div class="form-group">
        <label class="sr-only" for="exampleInputEmail2">用户名</label>
        <input type="text" class="form-control"  id="datetimepicker" name="time_select" size="16"  data-date-format="yyyy-mm-dd hh:ii">
<!--        <span class="add-on"><i class="icon-th"></i></span>-->
    </div>
    <button type="submit" class="btn btn-default">搜索</button>
</form>
<table class="table table-hover">
    <thead>
    <tr>
        <th>#</th>
        <th>用户名</th>
        <th>部门</th>
        <th>状态</th>
        <th>最后登录时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($arr_list as $k => $v){ ?>

    <?php } ?>
    <tr>
        <td>1</td>
        <td>asdfasdf</td>
        <td>12341234</td>
        <td>wertwret</td>
        <td>修改</td>
    </tr>
    </tbody>
</table>

<?php echo '共'.$int_count.'条记录'."&nbsp;&nbsp;".$str_page; ?>

<?php $this->load->view('admin/admin_modal_create'); ?>



