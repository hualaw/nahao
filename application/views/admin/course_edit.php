<form class="form-horizontal" role="form">
    <div class="form-group">
        <label class="col-md-1 control-label">课程名称</label>
        <label class="col-md-1 control-label">教师昵称</label>
        <label class="col-md-1 control-label">教授科目</label>
        <label class="col-md-1 control-label">课程类型</label>
        <label class="col-md-1 control-label">适用年级</label>
        <label class="col-md-1 control-label">讲课课酬</label>
        <label class="col-md-1 control-label">一句话简介</label>
        <label class="col-md-1 control-label">课程简介</label>
        <label class="col-md-1 control-label">适合人群</label>
        <label class="col-md-1 control-label">授课提要</label>
        <label class="col-md-1 control-label">课程安排</label>

        <div class="col-md-2">
            <select class="form-control" name="course_type">
                <option value="">全部课程类型</option>
                {foreach $course_types as $course_type}<option value="{$course_type.id}">{$course_type.name}</option>{/foreach}
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-control" name="subject">
                <option value="">全部学科</option>
                {foreach $subjects as $subject}<option value="{$subject.id}">{$subject.name}</option>{/foreach}
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-control" name="status">
                <option value="">全部状态</option>
                <option value="1">已禁用</option>
                <option value="2">已启用</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-control" name="search_type">
                <option value="1">课程名称</option>
                <option value="2">授课老师</option>
                <option value="3">课程ID</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control col-md-2" id="search_value" name="search_value" size="16" value="{if isset($arr_query_param.search_value)}{$arr_query_param.search_value}{/if}">
        </div>
        <button type="submit" class="btn btn-primary">搜索</button>
    </div>
</form>