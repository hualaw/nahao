<?php
	/**
	 * 今日开课
	 */
	$listStr = '';
	$class_type = config_item('course_type');#课程类型
	$teach_status = config_item('round_teach_status');#授课状态
	$total = 0;
	if($list) foreach ($list as $val){
		if($val['zjArr']) foreach ($val['zjArr'] as $v){
			if($v['son_class']) foreach ($v['son_class'] as $lesson){
				$listStr .= '<tr>
		            <td>'.date('Y-m-d H:i',$lesson['begin_time']).'-'.date('Y-m-d H:i',$lesson['end_time']).'</td>
		            <td>'.$lesson['parent_name'].'</td>
		            <td>'.$lesson['title'].'</td>
		            <td>'.$val['rate'].' / '.$val['total_class'].'</td>
		            <td>'.$class_type[($val['course_type'] ? $val['course_type'] : 0)].'</td>
		            <td>'.$teach_status[($val['teach_status'] ? $val['teach_status'] : 0)].'</td>
		            <td>'.$lesson['coursewareName'].'</td>
		            <td><a href="">试题管理</a></td>
		            <td><a href="">进入教室</a></td>
		          </tr>';
			}
		}
		$total += ( $val['total_class'] > 0 ) ? $val['total_class'] : 0;
	}
?>
<div class="col-md-10 column">
		<span class="badge" style="margin-bottom:10px"><?=date('Y年m月d日 星期w',time())?>    今天要上的班次：<span class="badge" style="color: #428bca;background-color: #ffffff;"><?=$today_total?></span></span>
</div>
<div class="col-md-10 column ui-sortable table-responsive">
	      	<table class="table table-hover table-bordered table-striped" style="border:3px solid #ddd;border-radius:10px;border-collapse: separate;box-shadow: 2px 2px 10px #333;">
	        <thead>
	          <tr class="warning">
	            <th>上课时间</th>
	            <th>课程名称</th>
	            <th>小节名称</th>
	            <th>课程进度</th>
	            <th>类型</th>
	            <th>授课状态</th>
	            <th>课件讲义</th>
	            <th>课堂练习题</th>
	            <th>教室入口</th>
	          </tr>
	        </thead>
	        <tbody>
	          <?=$listStr?>
	        </tbody>
	      </table>
	      </div>