<?php 
include_once '../functions.php';
  xiu_get_current_user();
  $posts=xiu_fetch('select count(1) as num from posts;');
  $posts_drafted=xiu_fetch("select count(1) as drafted_num from posts where status='drafted';");
  $categories=xiu_fetch('select count(1) as num from categories;');
  $comments=xiu_fetch('select count(1) as num from comments;');
  $comments_held=xiu_fetch("select count(1) as comments_held from comments where status='held';");
 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>
  <div class="main">
    <?php include 'inc/navbar.php'; ?>
    <div class="container-fluid">
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="post-add.html" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><strong><?php echo $posts[0]['num'] ?></strong>篇文章（<strong><?php echo $posts_drafted[0]['drafted_num'] ?></strong>篇草稿）</li>
              <li class="list-group-item"><strong><?php echo $categories[0]['num']; ?></strong>个分类</li>
              <li class="list-group-item"><strong><?php echo $comments[0]['num']; ?></strong>条评论（<strong><?php echo $comments_held[0]['comments_held'] ?></strong>条待审核）</li>
            </ul>
          </div>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>
  <?php $current_page='index' ?>
<?php include'inc/sidebar.php' ?>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
