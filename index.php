<?php
    session_start();
    ob_start();
    require_once "search_form.php";
    require_once "classes/Lodging.php";
    $lodging = new Lodging();
    // Available lodging
    $rows = $lodging->getLodgingList()['rows'];
    $count = $lodging->getLodgingList()['count'];
    $sql = $lodging->getLodgingList()['sql'];
    $parameters = $lodging->getLodgingList()['parameters'];

    // Non available lodging
    $rowsNA = $lodging->getLodgingListNonAvailable($sql, $parameters)['rows'];
    $countNA = $lodging->getLodgingListNonAvailable($sql, $parameters)['count'];    
?>

<p class="text-center text-white m-5"><?= htmlspecialchars($count) ;?> result(s) available<?= $countNA > 0 && isset($_GET['available']) ? ", <br>" . htmlspecialchars($countNA) . " results non available" : "" ;?></p>

<div class="row row-cols-1 row-cols-md-2 mx-5">
  
<?php
    foreach($rows as $row) {
?>
    <div class="col mb-4">
        <div class="card mb-3 bg-dark text-white">
            <div class="row no-gutters">
                <div class="col-md-6">
                    <img src="public/images-property/<?= htmlspecialchars($row['gite_image']) ;?>" class="card-img-top sticky-top" alt="...">
                </div>
                <div class="col-md-6 d-flex flex-column">
                    <div class="card-body">
                        <h4 class="card-title mb-5"><?= ucwords(htmlspecialchars($row['gite_name'])) ;?></h4>
                        <p class="card-text">
                            <strong>Property type : </strong>
                            <span><?= ucwords(htmlspecialchars($row['category_gite_name']));?></span>
                        </p>
                        <p class="card-text">
                            <strong>Location : </strong>
                            <?= htmlspecialchars($row['gite_postal']) ?>
                            <?= strtoupper(htmlspecialchars($row['gite_city'])) ?>
                        </p>
                        <p class="card-text">
                            <strong>Price / night : </strong>
                            <?= number_format(htmlspecialchars($row['gite_price']), 2, ",", " ") ?> €
                        </p>
                        <p class="card-text">
                            <strong>Guest(s) : </strong>
                            <?= htmlspecialchars($row['gite_guest']) ?>
                        </p>
                        <p class="card-text">
                            <strong>Bed(s) : </strong>
                            <?= htmlspecialchars($row['gite_bed']) ?>
                        </p>
                        <p class="card-text">
                            <strong>Bathroom(s) : </strong>
                            <?= htmlspecialchars($row['gite_bathroom']) ?>
                        </p>
                    </div>
                    <div class="card-footer text-center mt-auto w-100 border-top-0 bg-dark">
                        <a class="btn btn-info" target="_blank" href="details.php?id=<?= htmlspecialchars($row['gite_id']) ;?>" role="button">Details</a>
                    </div>
                </div>                
            </div>
        </div>
    </div>
<?php
    } 
    foreach ($rowsNA as $rowNA) {
        if (isset($_GET['available'])) {
?>
    <div class="col mb-4">
        <div class="card card-NA mb-3 bg-dark text-white">
            <div class="row no-gutters">
                <div class="col-md-6">
                    <img src="public/images-property/<?= htmlspecialchars($rowNA['gite_image']) ;?>" class="card-img-top sticky-top" alt="...">
                </div>
                <div class="col-md-6 d-flex flex-column">
                    <div class="card-body">
                        <h4 class="card-title mb-5"><?= ucwords(htmlspecialchars($rowNA['gite_name'])) ;?></h4>
                        <p class="card-text">
                            <strong>Property type : </strong>
                            <span><?= ucwords(htmlspecialchars($rowNA['category_gite_name']));?></span>
                        </p>
                        <p class="card-text">
                            <strong>Location : </strong>
                            <?= htmlspecialchars($rowNA['gite_postal']) ?>
                            <?= strtoupper(htmlspecialchars($rowNA['gite_city'])) ?>
                        </p>
                        <p class="card-text">
                            <strong>Price / night : </strong>
                            <?= number_format(htmlspecialchars($rowNA['gite_price']), 2, ",", " ") ?> €
                        </p>
                        <p class="card-text">
                            <strong>Guest(s) : </strong>
                            <?= htmlspecialchars($rowNA['gite_guest']) ?>
                        </p>
                        <p class="card-text">
                            <strong>Bed(s) : </strong>
                            <?= htmlspecialchars($rowNA['gite_bed']) ?>
                        </p>
                        <p class="card-text">
                            <strong>Bathroom(s) : </strong>
                            <?= htmlspecialchars($rowNA['gite_bathroom']) ?>
                        </p>
                    </div>
                    <div class="card-footer text-center mt-auto w-100 border-top-0 bg-dark">
                        <a class="btn btn-info" target="_blank" href="details.php?id=<?= htmlspecialchars($rowNA['gite_id']) ;?>" role="button">Details</a>
                    </div>
                </div>                
            </div>
        </div>
    </div>
<?php
        }
    }
?>
</div>
<?php
    $content = ob_get_clean();
    require "template.php";
?>