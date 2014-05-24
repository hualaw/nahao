<botton class="btn btn-primary btn" data-toggle="modal" data-target="#admin_create_modal">
    新增用户
</botton>
<!--<div class="input-append date form_datetime">
    <input size="16" type="text" value="" readonly>
    <span class="add-on"><i class="icon-th"></i></span>
</div>-->
<br><br>
<form class="form-inline" role="form">
    <div class="form-group">
        <label class="sr-only" for="phone">用户名</label>
        <input type="text" class="form-control" id="nickname" name="nickname" size="16" value="<?php echo isset($arr_query_param['nickname']) ? $arr_query_param['nickname'] : ''; ?>">
        <!--<input type="text" class="form-control"  id="admin_datetimepicker" name="time_select" size="16"  data-date-format="yyyy-mm-dd hh:ii">-->
        <!--        <span class="add-on"><i class="icon-th"></i></span>-->
    </div>
    <button type="submit" class="btn btn-default">搜索</button>
</form>
<table class="table table-hover">
    <thead>
    <tr>
        <th>#</th>
        <th>邮箱</th>
        <th>手机</th>
        <th>昵称</th>
        <th>性别</th>
        <th>年级</th>
        <th>注册时间</th>
        <th>省</th>
        <th>市</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($arr_list as $k => $v){ ?>
        <tr>
            <td><?php echo $v['id']?></td>
            <td><?php echo $v['email']?></td>
            <td><?php echo $v['phone_mask']?></td>
            <td><?php echo $v['nickname']?></td>
            <td><?php echo $v['gender']?></td>
            <td><?php echo $v['grade']?></td>
            <td><?php echo date('Y-md H:i',$v['create_time']); ?></td>
            <td><?php echo $v['province']?></td>
            <td><?php echo $v['city']?></td>
            <td><?php echo $v['status']==1 ? '已启用' : '<font color="red">已禁用</font>'?></td>
            <td><?php ?></td>
            <td>
                <a href="javascript:;" class="user_active" data-action="/user/active" data-user_id="<?php echo $v['id']; ?>" data-status="<?php echo $v['status']==1 ? 0 : 1; ?>"><?php echo $v['status']==1 ? '禁用' : '启用'?></a>
                <a href="javascript:;">修改</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<?php echo '共'.$int_count.'条记录'."&nbsp;&nbsp;".$str_page; ?>

<?php $this->load->view('admin/user_modal_create'); ?>



