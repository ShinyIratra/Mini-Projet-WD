<?php
require_once __DIR__ . '/../../inc/connexion.php';
require_once __DIR__ . '/../../inc/repository/ArticleRepository.php';
require_once __DIR__ . '/../../inc/services/ArticleService.php';

$db = getConnexion();
$articleRepository = new \inc\repository\ArticleRepository($db);
$articleService = new \inc\services\ArticleService($articleRepository);

$categories = $articleService->getAllCategories();
$uploadDir = __DIR__ . '/../../uploads/articles/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Gestion API recherche auteurs
if ((isset($_GET['action']) && $_GET['action'] === 'search_auteurs') && isset($_GET['q'])) {
    header('Content-Type: application/json');
    $q = strtolower($_GET['q']);
    $utilisateurs = $articleService->getAllUtilisateurs();
    $results = [];

    foreach ($utilisateurs as $user) {
        $nom_complet = $user['nom'] . ' ' . $user['prenom'];
        if (strpos(strtolower($nom_complet), $q) !== false) {
            $results[] = ['id' => $user['id_utilisateur'], 'nom' => $nom_complet];
        }
    }
    echo json_encode(array_slice($results, 0, 10));
    exit;
}

// Soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $contenu = $_POST['contenu'] ?? '';
    $id_categorie = $_POST['id_categorie'] ?? null;
    $auteur_input = $_POST['auteur'] ?? '';

    if ($titre && $contenu && $id_categorie && $auteur_input) {
        // Chercher ou créer l'utilisateur
        $utilisateurs = $articleService->getAllUtilisateurs();
        $id_utilisateur = null;

        foreach ($utilisateurs as $user) {
            if (trim(strtolower($user['nom'] . ' ' . $user['prenom'])) === trim(strtolower($auteur_input))) {
                $id_utilisateur = $user['id_utilisateur'];
                break;
            }
        }

        if (!$id_utilisateur) {
            $parts = explode(' ', trim($auteur_input), 2);
            $nom = $parts[0] ?? '';
            $prenom = $parts[1] ?? '';
            $identifiant = strtolower(str_replace(' ', '.', trim($auteur_input)));

            $stmt = $db->prepare("INSERT INTO utilisateur (nom, prenom, identifiant, mdp) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $identifiant, 'temp123']);
            $id_utilisateur = $db->lastInsertId();
        }

        // Créer l'article
        $id_article = $articleService->insertArticle($titre, $contenu, $id_categorie);
        $articleService->insertAuteur($id_article, $id_utilisateur);

        // Uploader les photos
        if (isset($_FILES['photos'])) {
            for ($i = 0; $i < count($_FILES['photos']['name']); $i++) {
                if ($_FILES['photos']['error'][$i] === UPLOAD_ERR_OK && !empty($_FILES['photos']['name'][$i])) {
                    $alt = $_POST['photos_alt'][$i] ?? '';
                    $filename = time() . '_' . uniqid() . '_' . basename($_FILES['photos']['name'][$i]);
                    $filepath = $uploadDir . $filename;

                    if (move_uploaded_file($_FILES['photos']['tmp_name'][$i], $filepath)) {
                        $articleService->insertPhoto($id_article, 'articles/' . $filename, $alt);
                    }
                }
            }
        }

        header('Location: articles.php?success=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un article</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Ajouter un nouvel article</h1>

        <form method="POST" enctype="multipart/form-data" id="article-form">
            <!-- Infos de base -->
            <div class="form-section">
                <h2>Informations</h2>

                <div class="form-group">
                    <label for="titre">Titre *</label>
                    <input type="text" id="titre" name="titre" required placeholder="Titre de l'article">
                </div>

                <div class="form-group">
                    <label for="id_categorie">Catégorie *</label>
                    <select id="id_categorie" name="id_categorie" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id_categorie']; ?>"><?= htmlspecialchars($cat['rubrique']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group auteur-container">
                    <label for="auteur">Auteur *</label>
                    <input type="text" id="auteur" name="auteur" required placeholder="Nom de l'auteur">
                    <div class="auteur-suggestions" id="auteur-suggestions"></div>
                    <p class="help-text">Tapez un nom pour chercher ou créer</p>
                </div>
            </div>

            <!-- Contenu -->
            <div class="form-section">
                <h2>Contenu</h2>
                <div class="form-group">
                    <textarea id="contenu" name="contenu"></textarea>
                </div>
            </div>

            <!-- Photos -->
            <div class="form-section">
                <h2>Photos</h2>
                <div id="photos-container"></div>
            </div>

            <!-- Boutons -->
            <div class="button-group">
                <button type="submit">Publier</button>
                <a href="articles.php" class="btn-cancel">Annuler</a>
            </div>
        </form>
    </div>

    <script src="../../tinymce/js/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: 'textarea#contenu',
            height: 400,
            plugins: 'lists link image code table',
            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
            language: 'fr_FR',
            branding: false,
            license_key: 'gpl'
        });

        document.getElementById('article-form').addEventListener('submit', function(e) {
            tinymce.triggerSave();
            const contenu = tinymce.get('contenu').getContent().trim();
            if (!contenu || contenu === '') {
                e.preventDefault();
                alert('Veuillez remplir le contenu');
                return false;
            }
        });

        const auteurInput = document.getElementById('auteur');
        const auteurSuggestions = document.getElementById('auteur-suggestions');

        auteurInput.addEventListener('input', async function() {
            if (this.value.length < 2) {
                auteurSuggestions.classList.remove('show');
                return;
            }

            const response = await fetch(`?action=search_auteurs&q=${encodeURIComponent(this.value)}`);
            const results = await response.json();

            if (results.length > 0) {
                auteurSuggestions.innerHTML = results.map(user =>
                    `<div class="suggestion-item" onclick="selectAuteur('${user.nom}')">${user.nom}</div>`
                ).join('');
                auteurSuggestions.classList.add('show');
            } else {
                auteurSuggestions.classList.remove('show');
            }
        });

        function selectAuteur(nom) {
            auteurInput.value = nom;
            auteurSuggestions.classList.remove('show');
        }

        document.addEventListener('click', (e) => {
            if (e.target !== auteurInput && !auteurSuggestions.contains(e.target)) {
                auteurSuggestions.classList.remove('show');
            }
        });

        function addPhotoField() {
            const container = document.getElementById('photos-container');
            const fieldDiv = document.createElement('div');
            fieldDiv.className = 'photo-field';

            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.name = 'photos[]';
            fileInput.accept = 'image/*';

            const altInput = document.createElement('input');
            altInput.type = 'text';
            altInput.name = 'photos_alt[]';
            altInput.placeholder = 'Texte alternatif';

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn-photo';

            if (container.children.length === 0) {
                btn.innerHTML = '+';
                btn.onclick = (e) => {
                    e.preventDefault();
                    addPhotoField();
                };
            } else {
                btn.innerHTML = '−';
                btn.classList.add('delete');
                btn.onclick = (e) => {
                    e.preventDefault();
                    fieldDiv.remove();
                };
            }

            fieldDiv.appendChild(fileInput);
            fieldDiv.appendChild(altInput);
            fieldDiv.appendChild(btn);
            container.appendChild(fieldDiv);
        }

        addPhotoField();
    </script>
</body>
</html>
