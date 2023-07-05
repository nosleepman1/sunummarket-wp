<?php
// Etape 21 - PHP / Bootstrap
require_once 'config.php';
$titre = 'Exercice PHP #21';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h2 class="text-primary"><?= htmlspecialchars($titre) ?></h2>
  <p>Test requête et affichage Bootstrap étape 21.</p>
</body>
</html>
