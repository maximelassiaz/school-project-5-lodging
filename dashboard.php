<?php
    // TODO : Check is admin is connected via session variables, redirect if not
    require_once "public/partials/header.php";
    require_once "search_form.php";
    require_once "classes/DatabaseAdmin.php";
    $dashboard = new DatabaseAdmin();
    $rows = $dashboard->getLodgingList($query, $parameters)['rows'];
    $count = $dashboard->getLodgingList($query, $parameters)['count'];
    $sql = $dashboard->getLodgingList($query, $parameters)['sql'];
    $rowsNA = $dashboard->getLodgingListNonAvailable($sql, $parameters)['rows'];
    $countNA = $dashboard->getLodgingListNonAvailable($sql, $parameters)['count'];
?>

<?php // TODO : Create form and modal button to add a lodging ?>
<button>Ajouter un gîte</button>

<p><?= htmlspecialchars($count) ;?> résultat(s)</p>
<p><?= htmlspecialchars($countNA);?> résultat(s)</p>

<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nom du logement</th>
            <th scope="col">Type de logement</th>
            <th scope="col">Adresse</th>
            <th scope="col">Prix d'une nuit</th> 
            <th scope="col">Disponibilité</th>           
            <th scope="col" colspan="3">Gestion</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach($rows as $row) {
        ?>
        <tr>
            <th scope="row"><?= htmlspecialchars($row['gite_id']) ?></th>
            <td><?= ucwords(htmlspecialchars($row['gite_name'])) ?></td>
            <td><?= ucfirst(htmlspecialchars($row['category_gite_name'])) ?></td>
            <td>
                <?= htmlspecialchars($row['gite_street']) ?> <br> 
                <?= htmlspecialchars($row['gite_city']) ?> 
                <?= htmlspecialchars($row['gite_postal']) ?>
            </td>
            <td><?= number_format(htmlspecialchars($row['gite_price']), 2, ",", " ") ?> €</td>
            <td>
                <?php
                    require_once "functions.php";
                    isAvailable($row['gite_maintenance']);
                ?>

            </td>
            <!-- Display more details using a modal button -->
            <td>
                <?php
                    require "display_card_modal.php";
                ?>
            </td>
            
            <?php // TODO : Create form and modal buttons to modify and delete ?>            
            <td>Modifier</td>
            <td>Supprimer</td>
        </tr>
        <?php
            }
            if ($gite_available === "Yes") {
                foreach($rowsNA as $row) {
                    ?>
                    <tr class="bg-dark">
                        <th scope="row"><?= htmlspecialchars($row['gite_id']) ?></th>
                        <td><?= ucwords(htmlspecialchars($row['gite_name'])) ?></td>
                        <td><?= ucfirst(htmlspecialchars($row['category_gite_name'])) ?></td>
                        <td>
                            <?= htmlspecialchars($row['gite_street']) ?> <br> 
                            <?= htmlspecialchars($row['gite_city']) ?> 
                            <?= htmlspecialchars($row['gite_postal']) ?>
                        </td>
                        <td><?= number_format(htmlspecialchars($row['gite_price']), 2, ",", " ") ?> €</td>
                        <td>
                            <?php
                                require_once "functions.php";
                                isAvailable($row['gite_maintenance']);
                            ?>
            
                        </td>
                        <!-- Display more details using a modal button -->
                        <td>
                            <?php
                                require "display_card_modal.php";
                            ?>
                        </td>
                        
                        <?php // TODO : Create form and modal buttons to modify and delete ?>            
                        <td>Modifier</td>
                        <td>Supprimer</td>
                    </tr>
            <?php
                    }
                }
            ?>
            
            
    </tbody>
</table>


<?php
    require_once "public/partials/footer.php";
?>