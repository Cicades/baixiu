<?php
require_once '../functions.php';
xiu_get_current_user();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
  <style type="text/css">
    #loading{
      display: flex;
      position: absolute;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      background-color: rgba(0,0,0,.3);
      z-index: 99;
      align-items: center;
      justify-content: center;
    }
   .lds-ripple {
  display: inline-block;
  position: relative;
  width: 128px;
  height: 128px;
}
.lds-ripple div {
  position: absolute;
  border: 4px solid #fff;
  opacity: 1;
  border-radius: 50%;
  animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
}
.lds-ripple div:nth-child(2) {
  animation-delay: -0.5s;
}
@keyframes lds-ripple {
  0% {
    top: 56px;
    left: 56px;
    width: 0;
    height: 0;
    opacity: 1;
  }
  100% {
    top: -1px;
    left: -1px;
    width: 116px;
    height: 116px;
    opacity: 0;
  }
}
</style>
</head>
<body>
  <script>NProgress.start()</script>
  <div class="main">
  <?php include 'inc/navbar.php';?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch" style="display: none">
          <button class="btn btn-info btn-sm">批量批准</button>
          <button class="btn btn-warning btn-sm">批量拒绝</button>
          <button class="btn btn-danger btn-sm">批量删除</button>
        </div>
        <ul class="pagination pagination-sm pull-right">
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th width="70"  class="text-center">作者</th>
            <th class="text-center">评论</th>
            <th width="120"  class="text-center">评论在</th>
            <th width="130"  class="text-center">提交于</th>
            <th width="50"  class="text-center">状态</th>
            <th class="text-center" width="150">操作</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
  <?php $current_page = 'comments'?>
  <?php include 'inc/sidebar.php'?>
  <div id="loading" style="display: none;"><div class="lds-ripple"><div></div><div></div></div></div>
  <script type="text/x-jsrender" id="template">
    {{for}}
      <tr {{if status=='rejected'}}class="warning"{{else status=='held'}}class="danger"{{/if}} data-id={{:id}}>
        <td class="text-center"><input type="checkbox"></td>
        <td class="text-center">{{:author}}</td>
        <td>{{:content}}</td>
        <td>{{:parent_title}}</td>
        <td  class="text-center">{{:~dateFormat(created)}}</td>
        <td>{{if status=='rejected'}}拒绝{{else status=='held'}}待审{{else}}准许{{/if}}</td>
        <td class="text-center">
          {{if status=='held'}}
            <a href="javascript:void(0)" class="btn btn-info btn-xs">批准</a>
            <a href="javascript:void(0)" class="btn btn-warning btn-xs">拒绝</a>
          {{/if}}
          <a href="javascript:void(0)" class="btn btn-danger btn-xs del-btn">删除</a>
        </td>
      </tr>
    {{/for}}
  </script>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/jsrender/jsrender.min.js"></script>
  <script src="/static/assets/vendors/twbs-pagination/jquery.twbsPagination.js"></script>
  <script>
    $(document).ajaxStart(function(){
      $('#loading').fadeIn();
    })
    $(document).ajaxStop(function(){
      $('#loading').fadeOut();
    })
    var currentPage=1;
    //TODO:删除
    $('tbody').on('click','.del-btn',function(){
      $tr=$(this).parent().parent();
      $.getJSON('/admin/api/delete-comments.php',{id:$tr.data('id')},function(res){
        if (res) {
          getData(currentPage);
        }else return;
      })
    })
    function getDate(dateString){
          var date=new Date(dateString);
          var mydate="";
          var year=date.getFullYear();
          var month=date.getMonth()+1;
          var day=date.getDate();
          var hours=date.getHours();
          var minutes=date.getMinutes();
          var seconds=date.getSeconds();
          month=month>=10?month:"0"+month;
          day=day>=10?day:"0"+day;
          hours=hours>=10?hours:"0"+hours;
          minutes=minutes>=10?minutes:"0"+minutes;
          seconds=seconds>=10?seconds:"0"+seconds;
          mydate=year+"年"+month+"月"+day+"日"+hours+":"+minutes+":"+seconds;
          return mydate;
        }//格式化时间
    function getData(page){
      $.getJSON('/admin/api/comments.php',{page:page},function(res){
        $('.pagination').twbsPagination('destroy');//让页码动态显示，在每次初始化之前先动态先销毁原有分页
        $('.pagination').twbsPagination({
            totalPages:res.total_pages,
            visiblePages:5,
            startPage:page>res.total_pages?res.total_pages:page,
            initiateStartPageClick:false,
            first:'首页',
            last:'尾页',
            next:'>>',
            prev:'<<',
            onPageClick:function(e,page){
              getData(page);
              currentPage=page;
            }
          });
          $.views.helpers({dateFormat:getDate
          })
          var html=$('#template').render(res.comments);
          $('tbody').html(html);
      });
    }
    getData(currentPage);
  </script>
  <script>NProgress.done()</script>
</body>
</html>
