<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>SmmView.Pro Official</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
<link rel="stylesheet" type="text/css" href="style.css">
<style>
@charset "utf-8";
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}
html {
  font-size: 16px;
}
body {
  font-family: Arial, sans-serif;
}
#container {
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  width: 100%;
  height: 100vh;
  padding: 15px;
  background-image: url("/img/admin/background.jpg");
  background-size: cover;
  background-repeat: no-repeat;
  overflow: hidden scroll;
}
.box {
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  width: 100%;
  max-width: 300px;
  height: auto;
  margin: auto;
}
.form-box {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  position: relative;
  z-index: 999;
  width: 100%;
  padding: 30px 20px;
  border: solid 1px rgba(255, 255, 255, .5);
  border-radius: 16px;
  box-shadow: 4px 4px 10px rgba(0, 0, 0, .1),
             -4px -4px 10px rgba(0, 0, 0, .1);
  background-color: rgba(255, 255, 255, .1);
  -webkit-backdrop-filter: blur(5px);
  backdrop-filter: blur(5px);
}
.ic-account {
  width: 60px;
  height: 60px;
  margin-bottom: 10px;
  border: solid 1px rgba(255, 255, 255, .5);
  border-radius: 50%;
  background-color: rgba(255, 255, 255, .2);
  background-image: url("https://www.profelar.com/storage/images/axies_logo.png");
  background-size: cover;
  background-position: center;
  background-size: 40px;
  background-repeat: none;
}
.login-form-input {
  width: 100%;
  height: 50px;
  margin: 10px auto;
  padding: 15px 20px;
  border: solid 1px rgba(0, 0, 0, 1);
  border-radius: 25px;
  background-color: rgba(0, 0, 0, 1);
  color: #fff;
  font-size: 1rem;
  outline: none;
}
.login-form-input::placeholder {
  color: rgba(255, 255, 255, .8);
  
}
.two_factor_input::placeholder {
  font-size: 10px;
}
.login-form-btn {
  width: 100%;
  height: 50px;
  margin: 20px auto 10px;
  border: none;
  border-radius: 25px;
  background-color: #fff;
  color: #3d3935;
  font-size: 1.25rem;
  outline: none;
  cursor: pointer;
}
.text {
  margin: 0;
  padding: 0;
  color: #fff;
  font-size: 14px;
  text-align: center;
}
.text a {
  color: #fff;
}

#containe2 {
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;

}

#puffx {
    display: inline;
    color: black;
    text-align: center;
}

#puffx1{
    display: inline;
    color: black;
    text-align: center;
}

#heart {
    color: red;
    display: inline;
    text-align: center;
}

a {
    color: black;
    text-decoration: underline;
}

.login-form-btn:hover,
.text a:hover {
  opacity: .8;
}
</style>
  </head>
  <body>
    <div id="container">
      <div class="box">
        <div class="form-box">
            <a target="_main" href="">
<div class="ic-account"></div>
</a>
<?php if( $success ): ?>
<div class="alert alert-success"><?php echo $successText; ?></div>

<?php endif; ?>
<?php if( $error ): ?>
<div class="alert alert-danger"><?php echo $errorText; ?></div>
<?php endif; ?>
<form name="login-form" action="#" method="post">
<input class="login-form-input" type="username" name="username" placeholder="Nhập tên người dùng..." required>
<input class="login-form-input" type="password" name="password"  placeholder="Mật khẩu" required>
<input class="login-form-input two_factor_input" type="number" name="two_factor_code"  placeholder="Nhập mã từ ứng dụng Authenticator (nếu đã thiết lập)">
<div class="form-check">
<input type="checkbox" class="form-check-input" name="remember"id="exampleCheck1">
<label class="form-check-label" for="exampleCheck1" >Ghi nhớ đăng nhập</label>
</div>
<button class="login-form-btn" type="submit" name="submit">Đăng nhập</button>
<br>
<br>
<div id="containe2">
<pre id="puffx">Được thực hiện với </pre><pre id="heart">&#10084;</pre><pre id="puffx1"><a href="https://smmview.pro" target="_main">SmmView.Pro</pre>
</div>

          </form>
        </div>
      </div>
    </div>
  </body>
</html>