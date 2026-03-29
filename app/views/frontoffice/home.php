<?php foreach ($articles as $article): ?>
    <h2><?= $article['titre'] ?></h2>
    <p><?= $article['contenu'] ?></p>
    <hr>
<?php endforeach; ?>
