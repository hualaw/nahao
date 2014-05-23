<form class="form-search" role="form" method="post" action="/order/search_order">
    <div class="form-group">
        <label class="sr-only" for="exampleInputEmail2">订单管理</label>
    </div>
    订单总数:<?php echo $order_count['count']; ?>&nbsp;&nbsp;&nbsp;&nbsp;已付款订单:<?php echo $order_count['payment']; ?>&nbsp;&nbsp;&nbsp;&nbsp;待付款订单:<?php echo $order_count['non-payment']; ?>&nbsp;&nbsp;&nbsp;&nbsp;已取消订单:<?php echo $order_count['cancel']; ?>&nbsp;&nbsp;&nbsp;&nbsp;已关闭订单:<?php echo $order_count['close']; ?>&nbsp;&nbsp;&nbsp;&nbsp;退款中:<?php echo $order_count['be_refund']; ?>&nbsp;&nbsp;&nbsp;&nbsp;已退款:<?php echo $order_count['refund']; ?>&nbsp;&nbsp;&nbsp;&nbsp;已完成:<?php echo $order_count['success']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<br /><br />
    订单号<input type="text" placeholder="请输入订单号" name="order_id" class="span2 search-query">&nbsp;&nbsp;
    <select name="name_phone_email">
        <option value=0>搜索条件</option>
        <option value=1>姓名</option>
        <option value=2>手机</option>
        <option value=3>邮箱</option>
    </select>
    <input type="text" placeholder="请输入电话/姓名/邮箱" name="phone_name_email">&nbsp;&nbsp;
    <select name="pay_type">
        <option value=0>全部支付方式</option>
        <option value=1>网银</option>
        <option value=2>信用卡</option>
        <option value=3>支付宝</option>
    </select>&nbsp;&nbsp;
    <select name="status">
        <option value=0>全部订单状态</option>
        <option value=1>未付款</option>
        <option value=2>已付款</option>
        <option value=3>已完成</option>
        <option value=4>已取消</option>
        <option value=5>已关闭</option>
    </select><br />
    下单时间<input type="text" id="order_datetimepicker1" name="create_time1">-<input type="text" id="order_datetimepicker2" name="create_time2">&nbsp;&nbsp;
    付款时间<input type="text" id="order_datetimepicker3" name="confirm_time1">-<input type="text" id="order_datetimepicker4" name="confirm_time2">
    <button type="submit" class="btn">搜索</button>
</form><br />
符合条件的共有<?php echo $sea_total; ?>条数据<br />
<table class="table table-bordered">
    <thead>
    <tr class="active">
        <th>订单号</th>
        <th>用户名</th>
        <th>手机</th>
        <th>用户邮箱</th>
        <th>下单时间</th>
        <th>付款时间</th>
        <th>订单金额</th>
        <th>支付方式</th>
        <th>订单状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($spendata as $row) :?>
        <input type="hidden" value="<?php echo $row['uid'] ?>" class="uid" />
        <input type="hidden" value="<?php echo $row['phone_mask'] ?>" class="p_mask" />
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['nickname']; ?></td>
        <td class="show_phone" width="108"><?php echo $row["phone_mask"]; ?></td>
        <td><?php echo $row["email"]; ?></td>
        <td><?php echo date("Y-m-d H-i-s",$row['create_time']); ?></td>
        <td><?php echo date("Y-m-d H-i-s",$row['confirm_time']); ?></td>
        <td><?php echo $row['spend']; ?></td>
        <td><?php echo $config_pay[$row['pay_type']]; ?></td>
        <td><?php echo $config_status[$row["status"]]; ?></td>
        <td><a href="/order/order_details?id=<?php echo $row['id']; ?>">查看详情</a></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<script>
    $(function(){
        $('.show_phone').mouseover(function(){
            $.ajax({
                type:"post",
                url:"/order/show_phone",
                data:"uid="+$(".uid").val(),
                success:function(msg){
                $(".show_phone").html(msg);
                }
            })
        })

        $('.show_phone').mouseout(function(){
            var p=$(".p_mask").val();
            $(".show_phone").html(p);
        })
    })
</script>
<?php echo $page; ?>
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