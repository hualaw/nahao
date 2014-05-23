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
        <label class="sr-only" for="username">用户名</label>
        <input type="text" class="form-control" id="username" name="username" size="16" value="<?php echo isset($arr_query_param['username']) ? $arr_query_param['username'] : ''; ?>">
        <!--<input type="text" class="form-control"  id="admin_datetimepicker" name="time_select" size="16"  data-date-format="yyyy-mm-dd hh:ii">-->
<!--        <span class="add-on"><i class="icon-th"></i></span>-->
    </div>
    <button type="submit" class="btn btn-default">搜索</button>
</form>
<table class="table table-hover">
    <thead>
    <tr>
        <th>#</th>
        <th>用户名</th>
        <th>真名</th>
        <th>手机</th>
        <th>部门</th>
        <th>状态</th>
        <th>最后登录时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($arr_list as $k => $v){ ?>
        <tr>
            <td><?php echo $v['id']?></td>
            <td><?php echo $v['username']?></td>
            <td><?php echo $v['realname']?></td>
            <td><?php echo $v['phone']?></td>
            <td><?php echo $v['group_name']?></td>
            <td><?php echo $v['status']==1 ? '已启用' : '<font color="red">已禁用</font>'?></td>
            <td><?php ?></td>
            <td>
                <a href="javascript:;" class="admin_active" data-action="/admin/active" data-admin_id="<?php echo $v['id']; ?>" data-status="<?php echo $v['status']==1 ? 0 : 1; ?>"><?php echo $v['status']==1 ? '禁用' : '启用'?></a>
                <a href="javascript:;">修改</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<?php echo '共'.$int_count.'条记录'."&nbsp;&nbsp;".$str_page; ?>

<?php $this->load->view('admin/admin_modal_create'); ?>



