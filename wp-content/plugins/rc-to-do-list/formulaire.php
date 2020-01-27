<?php
global $wpdb;

if (
    isset($_POST['nom_tache']) &&
    !empty($_POST['nom_tache'])
) {
    $nom_tache = $_POST['nom_tache'];
    $categorie = $_POST['nom_categorie'];
    $description = $_POST['description'];


    $wpdb->insert(
        "{$wpdb->prefix}to_do_list",
        array('nom_tache' => $nom_tache, 'categorie_id' => $categorie, 'description' => $description, 'state' => 'todo')
    );
}

?>

<div id="to-do-list" class="wrap">
    <h1>Mes taches à faire <button class="btn btn-success rounded-0" data-toggle="modal" data-target="#exampleModalCenter">Ajouter</button></h1>

</div>
<div class="wrap">
    <?php
    $liste_taches = $wpdb->get_results("SELECT tdl.id as id_tache, tdl.nom_tache, tdl.categorie_id, tdl.description, tdl.state, cat.id, cat.nom  FROM {$wpdb->prefix}to_do_list tdl INNER JOIN {$wpdb->prefix}to_do_categorie cat ON tdl.categorie_id = cat.id");
    if ($liste_taches) {
        echo "
    <table id='to-do-table' class='w-100'><thead class='bg-dark'>
    <tr>
    <th>Nom</th>
    <th>Catégorie</th>
    <th>État</th>
    <th style='width: 10%'>Action</th>
    </tr>
    </thead>
    <tbody>
    ";
        foreach ($liste_taches as $tache) {
            echo "<tr><td>" . $tache->nom_tache . "</td><td>" . $tache->nom . "</td><td>" . $tache->state . "</td><td><button class='btn btn-primary w-50 rounded-0'><i class='fas fa-wrench'></i></button><a href='" . plugin_dir_url(__FILE__) . "action/supprimer-tache.php?id=" . $tache->id_tache . "' class='btn btn-danger w-50 rounded-0'><i class='fas fa-trash'></i></a></td></tr>";
        }
        echo "</tbody></table>";
    }
    ?>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

    <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
    <div class="modal-dialog modal-dialog-centered" role="document">


        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Nouvelle Tâche</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="?page=gestion-taches" method="post">
                    <label for="nom_tache">Ajouter une tâche :</label>
                    <input type="text" name="nom_tache" required />
                    <label for="description">Ajouter une description :</label>
                    <input type="text" name="description" style="width: 50%" required />
                    <label for="nom_tache">Choisir la Catégorie :</label>
                    <?php
                    $liste_categories = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}to_do_categorie");
                    if ($liste_categories) {
                        echo "<select name='nom_categorie'>";
                        foreach ($liste_categories as $categorie) { ?>
                            <option value="<?php echo $categorie->id; ?>"><?php echo $categorie->nom; ?></option>";
                    <?php }
                        echo "</select>";
                    }
                    ?>
                    </select>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>