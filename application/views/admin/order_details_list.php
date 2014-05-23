<h3>订单信息</h3>
<hr>
订单号:<?php echo $details['id']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;订单状态:<?php echo $config_status[$details['status']]; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;下单时间:<?php echo date("Y-m-d H:i:s",$details['create_time']); ?><br />
<h4>收货人信息</h4>
姓名:<?php echo $details['nickname']; ?><br />
手机:<input type="text" value="<?php echo $details['phone_mask']; ?>" id="show" readonly /> <br />
邮箱:<?php echo $details['email']; ?><br />
<hr>
<h5>付款方式:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $config_pay[$details['pay_type']]; ?></h5><br />
<h5>订单金额:&nbsp;&nbsp;￥<?php echo $details['spend']; ?>&nbsp;元</h5><br />
<table class="table table-bordered">
    <tr class="active">
        <td>序号</td>
        <td>班次名称</td>
        <td>上课日期</td>
        <td>原价</td>
        <td>促销价</td>
        <td>操作时间</td>
        <td>退款状态</td>
    </tr>
    <tr>
        <td><?php echo $details['round_id']; ?></td>
        <td><?php echo $details['title']; ?></td>
        <td><?php echo date('Y-m-d H:i:s',$details['start_time']); ?>到<?php echo date('Y-m-d H:i:s',$details['end_time']); ?></td>
        <td><?php echo $details['price']; ?></td>
        <td><?php echo $details['sale_price']; ?></td>
        <td><?php echo date('Y-m-d H:i:s',$details['create_time']); ?></td>
        <td><?php
            if(3<=$details['refund_status'] && $details['refund_status']<6)
                echo "退款中","<button type='button' class='btn btn-primary btn-xs' id='button'>成功</button>";
            elseif($details['refund_status']==6)
                echo "已退款";
            else
                echo "——";
            ?></td>
    </tr>
</table>
<input type="hidden" value="<?php echo $details['student_id']; ?>" id="student_id" />
<input type="hidden" value="<?php echo $details['id']; ?>" id="order_id" />
<input type="hidden" value="<?php echo $details['phone_mask']; ?>" id="mask" />
<hr>
<h4>备注信息</h4>
<?php foreach($note as $row)
    echo date('Y-m-d H:i:s',$row['create_time']),'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$row['note'],'<br /><br />';
?>
<textarea class="form-control" rows="3" placeholder="在此添加备注信息" id="note_content"></textarea>
<br />
<?php
    for($i=0;$i<270;$i++)
    {
        echo "&nbsp;";
    }
?>
<button type="button" class="btn btn-info" id="memory">保存</button>