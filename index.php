<?php
    require_once "public/partials/header.php";
    // TODO change object wether admin or client is connected
    require_once "search_form.php";
    require_once "classes/Database.php";
    $lodging = new Database();
    $rows = $lodging->getLodgingList($query, $parameters)['rows'];
    $count = $lodging->getLodgingList($query, $parameters)['count'];
    $sql = $lodging->getLodgingList($query, $parameters)['sql'];
    $rowsNA = $lodging->getLodgingListNonAvailable($sql, $parameters)['rows'];
    $countNA = $lodging->getLodgingListNonAvailable($sql, $parameters)['count'];    
?>

<p><?= htmlspecialchars($count) ;?> résultat(s)</p>

<p><?= htmlspecialchars($countNA) ;?> résultat(s)</p>

    <?php
        foreach($rows as $row) {
    ?>
<div class="card" style="width: 18rem;">
    <img src="public/images/<?= htmlspecialchars($row['gite_image']) ;?>" class="card-img-top" alt="...">
    <div class="card-body">
        <h5 class="card-title"><?= ucwords(htmlspecialchars($row['gite_name'])) ;?></h5>
        <p class="card-text">
            <strong>Type de logement : </strong>
            <?= ucwords(htmlspecialchars($row['category_gite_name']));?>
        </p>
        <p class="card-text">
            <strong>Localisation : </strong>
            <?= htmlspecialchars($row['gite_postal']) ?>
            <?= htmlspecialchars($row['gite_city']) ?>
        </p>
        <p class="card-text">
            <strong>Prix d'une nuitée : </strong>
            <?= number_format(htmlspecialchars($row['gite_price']), 2, ",", " ") ?> €
        </p>
        <p class="card-text">
            <strong>Nombre de chambres : </strong>
            <?= htmlspecialchars($row['gite_bedroom']) ?>
        </p>
        <?php
            require "display_card_modal.php";
        ?>
    </div>
</div>
<?php
        }
        if($gite_available === "Yes") {
            foreach($rowsNA as $row) {
                ?>
            <div class="card bg-dark" style="width: 18rem;">
                <img src="public/images/<?= htmlspecialchars($row['gite_image']) ;?>" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title"><?= ucwords(htmlspecialchars($row['gite_name'])) ;?></h5>
                    <p class="card-text">
                        <strong>Type de logement : </strong>
                        <?= htmlspecialchars($row['category_gite_name']) ?>
                    </p>
                    <p class="card-text">
                        <strong>Localisation : </strong>
                        <?= htmlspecialchars($row['gite_postal']) ?>
                        <?= htmlspecialchars($row['gite_city']) ?>
                    </p>
                    <p class="card-text">
                        <strong>Prix d'une nuitée : </strong>
                        <?= number_format(htmlspecialchars($row['gite_price']), 2, ",", " ") ?> €
                    </p>
                    <p class="card-text">
                        <strong>Nombre de chambres : </strong>
                        <?= htmlspecialchars($row['gite_bedroom']) ?>
                    </p>
                    <?php
                        require "display_card_modal.php";
                    ?>
                </div>
            </div>
            <?php 
            }
        }   
    require_once "public/partials/footer.php";
?>