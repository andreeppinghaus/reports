<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Livros CNCFlora</title>
  <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
  <style type="text/css">
    ul {
      padding:0;
    }
    li {
      list-style: none;
    }
    .content {
      width: 1024px;
      margin: 0 auto;
    }
  </style>
</head>
<body>
<div class="content">
  <h1><?php echo $db ?></h1>
  <ul>
    <li>
      <a href="<?php echo BASE ?>/book/<?php echo $db; ?>/TODAS">TODAS</a>
    </li>
    <?php foreach($families as $family): ?>
    <br>
    <li>
      <a href="<?php echo BASE ?>/book/<?php echo $db; ?>/<?php echo $family?>"><?php echo $family ?></a>
      <small>
        (<a href="<?php echo BASE ?>/book/<?php echo $db; ?>/<?php echo $family?>?simple">simplificado</a>)
        (<a href="<?php echo BASE ?>/book/<?php echo $db; ?>/<?php echo $family?>?profile">com perfil</a>)
        (<a href="<?php echo BASE ?>/book/<?php echo $db; ?>/<?php echo $family?>?profilepdf">com perfil PDF Normal</a>)
        (<a href="<?php echo BASE ?>/book/<?php echo $db; ?>/<?php echo $family?>?profilepdf&notreferences=1">com perfil PDF sem referencias</a>)
        (<a href="<?php echo BASE ?>/book/<?php echo $db; ?>/<?php echo $family?>?profilepdf&yesreferences=1">com perfil PDF somente as referencias</a>)
    </small>
    </li>
    <?php endforeach; ?>
</ul>
</div>

</body>
</html>
