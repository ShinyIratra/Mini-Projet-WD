<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <?php foreach ($articles as $article): ?>
        <?= $article['titre'] ?>
        <?= $article['contenu'] ?>
    <hr>
    <?php endforeach; ?>
</body>
</html>