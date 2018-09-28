<?php  
require_once '../functions.php';
xiu_get_current_user();
//TODO:添加类别
function categories_add(){
  global $success_flag;
  if (empty($_POST['name'])) {
    $GLOBALS['erro_message'] = '名字不能为空！';
    return;
  }
  if (empty($_POST['slug'])) {
    $GLOBALS['erro_message'] = '别名不能为空！';
    return;
  }  
  $name=$_POST['name'];
  $slug=$_POST['slug'];
  $affected_rows=xiu_excute("insert into categories VALUES(null,'{$slug}','{$name}');");
  if ($affected_rows>0 ) {
    $success_flag=true;
    $GLOBALS['erro_message'] = '添加成功！';
  }else{
    $success_flag=false;
    $GLOBALS['erro_message'] = '添加失败！';
  }
}
function categories_edit(){
  global $success_flag;
  if (empty($_POST['name'])) {
    $GLOBALS['erro_message'] = '名字不能为空！';
    return;
  }
  if (empty($_POST['slug'])) {
    $GLOBALS['erro_message'] = '别名不能为空！';
    return;
  }  
  $name=$_POST['name'];
  $slug=$_POST['slug'];
  $affected_rows=xiu_excute("update categories set name='{$name}',slug='{$slug}' where id={$_GET['id']};");
  if ($affected_rows>0 ) {
    $success_flag=true;
    $GLOBALS['erro_message'] = '修改成功！';
  }else{
    $success_flag=false;
    $GLOBALS['erro_message'] = '修改失败！';
  }
}
if ($_SERVER['REQUEST_METHOD']==='POST') {
  if (!empty($_GET['id'])) {
    categories_edit();
  }else{
    categories_add();
  }
}
if (!empty($_GET['id'])) {
  $category_edit;
  $id=$_GET['id'];
  $category_edit=xiu_fetch_single("select * from categories where id ='{$id}'");
}
//END：添加类别
$categories=xiu_fetch('select * from categories;');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
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
      <div class="page-title">
        <h1>分类目录</h1>
      </div>
      <?php if (isset($erro_message)): ?>
        <?php if ($success_flag): ?>
          <div class="alert alert-success">
            <strong>成功！</strong><?php echo $erro_message; ?>
          </div>          
        <?php else: ?>
          <div class="alert alert-danger">
            <strong>错误！</strong> <?php echo $erro_message; ?>
          </div>
        <?php endif ?>
      <?php endif ?>
      <div class="row">
        <div class="col-md-4">
          <!-- TODO:展示编辑信息 -->
          <?php if (isset($category_edit)): ?>
          <form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$category_edit['id'] ?>">
            <h2>编辑分类目录《 <?php echo $category_edit['name'] ?> 》</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称" value="<?php echo $category_edit['name']; ?>">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo $category_edit['slug']; ?>">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">保存</button>
            </div>
          </form>
          <!-- END:展示编辑信息 -->
          <?php else: ?>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
          <?php endif ?>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none" id="delAll">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categories as $item): ?>
                <tr>
                  <td class="text-center"><input type="checkbox" data-id='<?php echo $item['id'] ?>'></td>
                  <td><?php echo $item['name'] ?></td>
                  <td><?php echo $item['slug'] ?></td>
                  <td class="text-center">
                    <a href="<?php echo $_SERVER['PHP_SELF'].'?id='.$item['id'] ?>" class="btn btn-info btn-xs">编辑</a>
                    <a href="<?php echo '/admin/delete-categories.php'.'?id='.$item['id'] ?>" class="btn btn-danger btn-xs">删除</a>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php $current_page='categories' ?>
  <?php include'inc/sidebar.php' ?>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
  <script type="text/javascript">
    $(function ($) {
      var $delAll=[];
      var $checkbox=$('tbody input');
      var $delAllBtn=$('#delAll');
      var $selectAllBtn=$('thead input');
      //prop 获取的是dom封装的属性
      //attr 获取的是元素属性
      //jQuery中data方法可以获取自定义属性的值，前提是添加自定义属性时要符合html5的命名规范：data-name
      $checkbox.on('change',function () {
        var $id=$(this).data('id');
        if ($(this).prop('checked')) {
          $delAll.push($id);
        }else{
          $delAll.splice($delAll.indexOf($id),1);
        }
        if ($delAll.length) {
            $delAllBtn.fadeIn();
            $delAll.length==$checkbox.length?$selectAllBtn.prop('checked',true):null;
        }else{
          $delAllBtn.fadeOut();
          $selectAllBtn.prop('checked',false);
        }
        $delAllBtn.attr('href','/admin/delete-categories.php?id='+$delAll);
      });
      $selectAllBtn.on('change',function(){
        var $flag=$(this).prop('checked');
        if ($flag) {
          $delAllBtn.fadeIn();
          $checkbox.prop('checked',$flag);
          $checkbox.each(function () {
          $delAll.push($(this).data('id'));
          });
          $delAllBtn.attr('href','/admin/delete-categories.php?id='+$delAll);
        }else{
          $delAllBtn.fadeOut();
          $checkbox.prop('checked',$flag);
          $delAll.splice(0);
        }
      });
    })
  </script>
</body>
</html>
