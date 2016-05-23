<!DOCTYPE html>
<html>
<head>
<meta charset=utf-8 />
<title>easyTrack - Galileo 2.1.1</title>
<meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
<style>
  body { margin:0; padding:0; }
  #head{
	position: absolute;
	top: 0;
	bottom: 0;
	height: 74px;
	width: 100%;
	font-family: "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", "DejaVu Sans", Verdana, sans-serif;
	font-size: normal;
	font-weight: bold;
}

  #loginBox {
  position:fixed;
  z-index: 100;  
  top:50%;  
  left:50%;  
  margin:-150px 0 0 -150px;  
  width:200px;  
  height:200px;
}


</style>
</head>
<body>

<link rel="stylesheet" href="css/style.css">
<div id='head'><table width="100%"><tr><td width="96" height="80" bgcolor="#FFFFFF"><img src="images/ITAU_logo_small.png" width="64" height="64" alt=""/></td><td width="100%" align="center" bgcolor="#FFFFFF"><div class="information-box round">Galileo easyTrack 2.1.1 <br>by BCF Solutions S.A. </div></td><td width="416" bgcolor="#FFFFFF"></td><td width="80" height="55" bgcolor="#FFFFFF"><img src="images/logoBCF.gif" width="96" height="55" alt=""/></td></tr></table></div>
<div id='loginBox'>
		<form action="index.php" method="POST" id="login-form">
		
		  <fieldset>

				<p>
					<label for="login-username">username</label>
					<input type="text" id="utente" name="usuario" class="round full-width-input" autofocus />
				</p>

				<p>
					<label for="login-password">password</label>
					<input type="password" id="pasa" name="password" class="round full-width-input" />
				</p>
			  <input type="submit" class="button round blue image-right ic-right-arrow" value="LOG IN">

			</fieldset>

			<br/><div class="information-box round"><select><option id=1>English</option><option id=2>Italiano</option><option id=3>Portugues</option><option id=4>Castellano</option></select></div>
		</form>
		
</div>


</body>
</html>
