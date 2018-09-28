<?php  
require_once '../functions.php';
xiu_get_current_user();
$categories=xiu_fetch("select * from categories;");
//TODO:分类查询
$where='1=1';
$search='';
if (isset($_GET['category'])&&$_GET['category']!='all') {
  $category=$_GET['category'];
  $where='1=1 and c.id='.$category;
  $search.='&category='.$category;
}
//EDN:分类查询
//TODO：按状态查询
if (isset($_GET['status'])&&$_GET['status']!='all') {
  $status=$_GET['status'];
  $where.=" and p.status='{$status}'";
  $search.='&status='.$status;
}
echo $search;
//END：按状态查询
$page=empty($_GET['page'])?1:(int)$_GET['page'];
if ($page<1) {
  header('Location:/admin/posts.php'.$search);
}
$page_size=20;
$max_page=(int)ceil((int)xiu_fetch_single("select count(1) as num from users u,posts p,categories c where p.user_id=u.id and p.category_id=c.id and {$where};")['num']/$page_size);//分页总数
if ($page>$max_page) {
  header('Location:/admin/posts.php?page='.$max_page.$search);
}
//单页展示的数据条数
$visible_pages=5;
$page_start=$page-($visible_pages-1)/2;
$page_start<1&&$page_start=1;
$page_end=$max_page<=$visible_pages?$max_page:$page_start+$visible_pages-1;
if ($page_end>$max_page) {
    $page_end=$max_page;
    $page_start=$max_page-$visible_pages+1;
}
$offset=$page_size*($page-1);
$sql="select p.id as id,p.title as title,u.nickname as `name`,c.`name` as category,p.created as created,p.status as `status` from users u,posts p,categories c where p.user_id=u.id and p.category_id=c.id and {$where} order by p.created desc limit {$offset},{$page_size} ;";
$posts=xiu_fetch($sql);
/**
 * 将英文状态标识转换为中文
 * @param  string $status 英文状态
 * @return string         中文状态
 */
function convert_status($status){
  $all_status = array('drafted' => '草稿','published' => '已发布','trashed' => '回收站');
  return empty($all_status[$status])?'未知状态':$all_status[$status];
}
/**
 * 格式化时间
 * @param  string $date 原始时间
 * @return string       格式化后的时间
 */
function convert_date($date){
  $timestamp=strtotime($date);
  return date('Y年m月d日<b\r> H：m：s a',$timestamp);
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
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
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none" id="delAll">批量删除</a>
        <form class="form-inline" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
          <select name="category" class="form-control input-sm">
            <option value="all">所有分类</option>
            <?php foreach ($categories as $item): ?>
              <option value="<?php echo $item['id'] ?>" <?php echo isset($category)&&($item['id']==$category)?' selected':''; ?>><?php echo $item['name']; ?></option>
            <?php endforeach ?>
          </select>
          <select name="status" class="form-control input-sm">
            <option value="all">所有状态</option>
            <option value="drafted" <?php echo isset($status)&&($status=='drafted')?' selected':'' ?>>草稿</option>
            <option value="published" <?php echo isset($status)&&($status=='published')?'selected':'' ?>>已发布</option>
            <option value="trashed" <?php echo isset($status)&&($status=='trashed')?' selected':'' ?>>回收站</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
          <?php if (($page-1)>0): ?>
            <li><a href="?page=<?php echo ($page-1).$search; ?>">《</a></li>
          <?php endif ?>
          <?php for ($i=$page_start; $i <=$page_end ; $i++):?>
            <li class="<?php echo $i==$page?'active':''; ?>"><a href="?page=<?php echo $i.$search ?>"><?php echo $i ?></a></li>
          <?php endfor ?>
          <?php if (($page+1)<=$max_page): ?>
            <li><a href="?page=<?php echo ($page+1).$search; ?>">》</a></li>
          <?php endif ?>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($posts as $item): ?>
          <tr>
            <td class="text-center"><input type="checkbox" data-id="<?php echo $item['id'] ?>"></td>
            <td><?php echo $item['title'] ?></td>
            <td><?php echo $item['name'] ?></td>
            <td><?php echo $item['category'] ?></td>
            <td class="text-center"><?php echo convert_date($item['created']); ?></td>
            <td class="text-center"><?php echo convert_status($item['status']); ?></td>
            <td class="text-center">
              <a href="/admin/post-add.php?id=<?php echo $item['id'] ?>" class="btn btn-default btn-xs">编辑</a>
              <a href="/admin/delete-posts.php?id=<?php echo $item['id'] ?>" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php $current_page='posts' ?>
<?php include'inc/sidebar.php' ?>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
    <script type="text/javascript">
    //TODO:批量删除
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
        $delAllBtn.attr('href','/admin/delete-posts.php?id='+$delAll);
      });
      $selectAllBtn.on('change',function(){
        var $flag=$(this).prop('checked');
        if ($flag) {
          $delAllBtn.fadeIn();
          $checkbox.prop('checked',$flag);
          $checkbox.each(function () {
          $delAll.push($(this).data('id'));
          });
          $delAllBtn.attr('href','/admin/delete-posts.php?id='+$delAll);
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
