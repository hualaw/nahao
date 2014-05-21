<!-- Modal -->
<div class="modal fade" id="admin_create_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">新增管理员</h4>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="form-group">
                        <label for="exampleInputEmail1">用户名(用于登录，建议姓名拼音，如张三的账号是zhangsan)</label>
                        <input type="username" size="10" class="form-control" id="username" placeholder="输入用户名">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">手机号</label>
                        <input type="phone" size="10" class="form-control" id="phone" placeholder="输入手机号">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">邮箱</label>
                        <input type="email" size="10" class="form-control" id="email" placeholder="输入邮箱">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_admin_submit" data-action="/admin/create"> &nbsp;创建 &nbsp;</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->