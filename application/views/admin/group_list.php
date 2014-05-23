<botton class="btn btn-primary btn" data-toggle="modal" data-target="#group_create_modal">
    新增组
</botton>
<br><br>
<form class="form-inline" role="form">
    <div class="form-group">
        <label class="sr-only" for="group_name">组名</label>
        <input type="text" class="form-control" id="group_name" name="group_name" size="16" value="<?php echo isset($arr_query_param['group_name']) ? $arr_query_param['group_name'] : ''; ?>">
    </div>
    <button type="submit" class="btn btn-default">搜索</button>
</form>
<table class="table table-hover">
    <thead>
    <tr>
        <th>#</th>
        <th>组名</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($arr_list as $k => $v){ ?>
        <tr>
            <td><?php echo $v['id']?></td>
            <td><?php echo $v['name']?></td>
            <td><?php echo $v['status']==1 ? '已启用' : '<font color="red">已禁用</font>'?></td>
            <td>
                <a href="javascript:;" class="group_active" data-action="/group/active" data-group_id="<?php echo $v['id']; ?>" data-status="<?php echo $v['status']==1 ? 0 : 1; ?>"><?php echo $v['status']==1 ? '禁用' : '启用'?></a>
                <a href="javascript:;">修改</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<?php echo '共'.$int_count.'条记录'."&nbsp;&nbsp;".$str_page; ?>

<?php $this->load->view('admin/group_modal_create'); ?>



