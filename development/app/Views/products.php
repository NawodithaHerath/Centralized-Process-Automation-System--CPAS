<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1> product grop</h1>
    <h1><?=$category ?></h1>
    <ul>
        <?php foreach($product_list as $product): ?>
        <li><?=$product ?></li>
        <?php endforeach;?>
    </ul>
</body>
</html>