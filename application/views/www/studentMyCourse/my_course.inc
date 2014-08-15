<ul class="cf cListContWrap">
    <?php
        if(!empty($my_course)){
            foreach($my_course as $course){
    ?>
    <li class="cf cItem">
        <a href="#" class="fl">
            <img src="<?=$course['class_img']?>" alt="<?=$course['class_img']?>" class="cImg">
        </a>
        <div class="fl cDesc">
            <h4 >
                <a href="#" class="cTitle" title="<?=$course['title']?>"><?=csubstr($course['title'],0,15,'utf-8','...')?></a>
            </h4>
            <?php if ($course['teach_status'] == ROUND_TEACH_STATUS_STOP){?>
                                <span class="time">
                                    停课
                                </span>
            <?php }elseif ($course['teach_status'] == ROUND_TEACH_STATUS_FINISH){?>
                                <span class="time">
                                    已结课
                                </span>
            <?php }elseif  ($course['teach_status'] == ROUND_TEACH_STATUS_OVER){?>
                                <span class="time" title="已过期：已结课一个月之后">
                                    已过期
                                </span>
            <?php }else{?>
		   						<span class="time">
                                    <?php if($course['next_class_time'] != ''){?>
		   							下节课<strong><?=$course['next_class_time']?></strong>
                                    <?php }?>
		   						</span>
            <?php }?>
            <!-- 进度条开始 -->
            <div class="scheduleWrap">
                <!-- 此处的进度条需要算出百分比 如75%-->
                <div class="scheduleInner" style="width:<?=($course['class']/$course['totle_class'])*100?>%"</div>
            </div>
            <!-- 进度条结束 -->
		   						<span>
		   							<strong>已上<?=$course['class']?>节</strong>/共<?=$course['totle_class']?>节
		   						</span>
        </div>
        <a href="/course/buy_after/<?=$course['round_id']?>" class="cRedBtn enterBtn">进入课程</a>
    </li>
    <?php
            }
        }
    ?>
</ul>
<div>
    全部课程列表分页
</div>