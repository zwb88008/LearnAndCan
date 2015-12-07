thinkphp 模板使用后的代码：
CommonAction.class.php：
<?php
		class CommonAction extends Action{
			public function _initialize(){
				if(!isset($_SESSION['uid'])||!isset($_SESSION['username'])){
					$this->redirect('Admin/Login/index');
				}
			}
			
		}
IndexAction.class.php：

<?php
		//后台首页控制器
		class IndexAction extends CommonAction{
			public function index(){
					$this->display();
			}
			public function logout(){
				session_unset();
				session_destroy();
				$this->redirect('Admin/Index/index');
			}
		}
?>
LoginAction.class.php：

<?php
		//登陆控制器
		class LoginAction extends Action{
				public function index(){
					$this->display();
				}
				public function verify(){
					import('ORG.Util.Image');
					Image::buildImageVerify(1,1,'png',80,25);
				}
				public function login(){
					if(!IS_POST) halt('页面不存在');
					if(I('code','','md5')!=session('verify')){
						$this->error('验证码错误');
					}
					$username = I('username');
					$user = M('user')->where(array('username'=>$username))->find();
					if(!$user||$user['password'!=$pwd])
					{
						$this->error('账号或者密码错误');
					}
					session('uid',$user['id']);
					session('username',$user['username']);
					$this->redirect('Admin/Index/index');
				}

		}

index.html:
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<head>
</head>
<body>
		<div>
				<dl>
					<h1>主界面</h1>
					<a href='courseList.php'>管理课程</a><br/>
					<a href='addcourse.php'>添加课程</a><br/>
					<a href='#'>查询课程</a><br/>
					<a href="{:U('Admin/Index/logout')}">退出系统</a><br/>
				</dl>
		</div>
</body>
</html>

Login_index.html:

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<link rel="stylesheet" href="__PUBLIC__/Css/login.css" />
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<script type="text/javascript" src="__PUBLIC__/Js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="__PUBLIC__/Js/login.js"></script>
		<script type="text/javascript">
			verifyURL = '{:U("Admin/Login/verify",'','')}';
		</script>
	</head>
	<body>
		<div id="top">

		</div>
		<div class="login">	
			<form action="{:U('Admin/Login/login')}" method="post" id="login">
			<div class="title">
				你学我会
			</div>
			<table border="1" width="100%">
				<tr>
					<th>帐号:</th>
					<td>
						<input type="username" name="username" class="len250"/>
					</td>
				</tr>
				<tr>
					<th>密码:</th>
					<td>
						<input type="password" class="len250" name="password"/>
					</td>
				</tr>
				<tr>
					<th>验证码:</th>
					<td>
							<input type="code" class="len250" name="code"/> <img src="{:U('Admin/Login/verify','','')}" id="code"/> <a href="javascript:void(change_code(this));">看不清</a>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding-left:160px;"> <input type="submit" class="submit" value="登录"/></td>
				</tr>
			</table>
		</form>
	</div>
	</body>
</html>


以前的代码：
/*<!DOCTYPE HTML> 
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body> 

<?php
// 定义变量并设置为空值
$nameErr = $emailErr = $genderErr = $websiteErr = "";
$name = $email = $gender = $comment = $website = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   if (empty($_POST["name"])) {
     $nameErr = "姓名是必填的";
   } else {
     $name = test_input($_POST["name"]);
     // 检查姓名是否包含字母和空白字符
     if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
       $nameErr = "只允许字母和空格"; 
     }
   }
   
   if (empty($_POST["email"])) {
     $emailErr = "电邮是必填的";
   } else {
     $email = test_input($_POST["email"]);
     // 检查电子邮件地址语法是否有效
     if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
       $emailErr = "无效的 email 格式"; 
     }
   }
     
   if (empty($_POST["website"])) {
     $website = "";
   } else {
     $website = test_input($_POST["website"]);
     // 检查 URL 地址语法是否有效（正则表达式也允许 URL 中的斜杠）
     if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
       $websiteErr = "无效的 URL"; 
     }
   }

   if (empty($_POST["comment"])) {
     $comment = "";
   } else {
     $comment = test_input($_POST["comment"]);
   }

   if (empty($_POST["gender"])) {
     $genderErr = "性别是必选的";
   } else {
     $gender = test_input($_POST["gender"]);
   }
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>

<h2>PHP 验证实例</h2>
<p><span class="error">* 必需的字段</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
   姓名：<input type="text" name="name">
   <span class="error">* <?php echo $nameErr;?></span>
   <br><br>
   电邮：<input type="text" name="email">
   <span class="error">* <?php echo $emailErr;?></span>
   <br><br>
   网址：<input type="text" name="website">
   <span class="error"><?php echo $websiteErr;?></span>
   <br><br>
   评论：<textarea name="comment" rows="5" cols="40"></textarea>
   <br><br>
   性别：
   <input type="radio" name="gender" value="female">女性
   <input type="radio" name="gender" value="male">男性
   <span class="error">* <?php echo $genderErr;?></span>
   <br><br>
   <input type="submit" name="submit" value="提交"> 
</form>

<?php
echo "<h2>您的输入：</h2>";
echo $name;
echo "<br>";
echo $email;
echo "<br>";
echo $website;
echo "<br>";
echo $comment;
echo "<br>";
echo $gender;
?>

</body>
</html>
*/
