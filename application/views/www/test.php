
<a class="btn btn-primary btn" data-toggle="modal" data-target="#myModal">
    新增课程
</a>
<br>
<br>
<form class="form-inline" role="form">
    <div class="form-group">
        <label class="sr-only" for="exampleInputEmail2">课程名称</label>
        <input type="email" class="form-control" id="exampleInputEmail2" placeholder="Enter email">
    </div>
    <button type="submit" class="btn btn-default">搜索</button>
</form>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Operation</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>asdfasdf</td>
            <td>12341234</td>
            <td>wertwret</td>
            <td>修改</td>
        </tr>
        <tr>
            <td>2</td>
            <td>asdfasdf</td>
            <td>12341234</td>
            <td>wertwret</td>
            <td>删除</td>
        </tr>
        <tr>
            <td>3</td>
            <td>asdfasdf</td>
            <td>12341234</td>
            <td>wertwret</td>
            <td>操作</td>
        </tr>
        </tbody>
    </table>
<ul class="pagination">
    <li><a href="#">&laquo;</a></li>
    <li class="active"><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">5</a></li>
    <li><a href="#">&raquo;</a></li>
</ul>



<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">File input</label>
                        <input type="file" id="exampleInputFile">
                        <p class="help-block">Example block-level help text here.</p>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"> Check me out
                        </label>
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->