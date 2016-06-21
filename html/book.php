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
  <h1>Gerar PDF formato Livro</h1>
  <ul>
<?php foreach($dbs as $db): ?>
    <li>
    <a href="<?php echo BASE ?>/book/<?php echo $db; ?>"><?php echo $db ?></a>
</li>
<?php endforeach; ?>
  </ul>
</div>
</body>
</html>
