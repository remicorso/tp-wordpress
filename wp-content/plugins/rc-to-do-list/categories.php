<?php
global $wpdb;

if (
    isset($_POST['nom_categorie']) &&
    !empty($_POST['nom_categorie'])
) {
    $nom_categorie = $_POST['nom_categorie'];

    $wpdb->insert(
        "{$wpdb->prefix}to_do_categorie",
        array('nom' => $nom_categorie)
    );
}

?>

<div class="wrap">
    <h1>Les catégories</h1>
    <form action="?page=to-do-categorie" method="post">
        <label for="nom_categorie">Ajouter une catégorie :
        </label><input type="text" name="nom_categorie" />
        <input type="submit" value="Ajouter" />
    </form>
</div>

<h2>Liste des catégories : </h2>

<?php
$liste_categories = $wpdb->get_results("SELECT
nom FROM {$wpdb->prefix}to_do_categorie");
if ($liste_categories) {
    echo "<ul>";
    foreach ($liste_categories as $categorie) {
        echo "<li>" . $categorie->nom . "</li>";
    }
    echo "</ul>";
}
?>