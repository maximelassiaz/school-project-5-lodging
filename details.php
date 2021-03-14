<?php
    session_start();
    if(!isset($_GET['id']) || (int)$_GET['id'] < 0 || !is_int((int)$_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: index.php");
        exit();
    } else {    
    ob_start();
    require_once "classes/Lodging.php";
    $lodging = new Lodging();
    $row = $lodging->getLodging($_GET['id']);
?>

    <div class="card bg-dark text-white m-5">
        <div class="row no-gutters">
            <div class="col-md-6">
                <img src="public/images-property/<?= htmlspecialchars($row['gite_image']) ;?>" class="card-img-top sticky-top" alt="...">
            </div>
            <div class="col-md-6">
                <div class="card-body">
                    <h4 class="card-title mb-5"><?= ucwords(htmlspecialchars($row['gite_name']))?></h4>
                    <p class="card-text"><?= htmlspecialchars($row['gite_description']) ?></p>
                    <h6 class="card-title border-top border-white p-2 text-center">AVAILABILTY</h6>
                    <?php // TODO : calendar ?>
                    <h6 class="card-title border-top border-white p-2 text-center">LOCATION</h6>
                    <p class="card-text">
                        <?= htmlspecialchars($row['gite_postal']) ?> 
                        <?= strtoupper(htmlspecialchars($row['gite_city'])) ?> <br> 
                        <?= strtoupper(htmlspecialchars($row['gite_country'])) ?>
                    </p>
                    <h6 class="card-title border-top border-white p-2 text-center">PROPERTY INFORMATIONS</h6>
                    <p class="card-text">
                        <strong>Property type :</strong>
                        <?= ucfirst(htmlspecialchars($row['category_gite_name'])) ;?>
                    </p>
                    <p class="card-text">
                        <strong>Price / night :</strong>
                        <?= number_format(htmlspecialchars($row['gite_price']), 2, ",", " ") ;?> €
                    </p>
                    <p class="card-text">
                        <strong>Guest(s) :</strong>
                        <?= htmlspecialchars($row['gite_guest']) ;?>
                    </p>
                    <p class="card-text">
                        <strong>Bed(s) :</strong>
                        <?= htmlspecialchars($row['gite_bed']) ;?>
                    </p>
                    <p class="card-text">
                        <strong>Bathroom(s) :</strong>
                        <?= htmlspecialchars($row['gite_bathroom']) ;?>
                    </p>
                    <p class="card-text">
                        <strong>WiFi :</strong>
                        <?= isAvailable($row['gite_wifi']) ;?>
                    </p>
                    <p class="card-text">
                        <strong>Garden :</strong>
                        <?= isAvailable($row['category_gite_garden']) ;?>
                    </p>
                    <p class="card-text">
                        <strong>Pool :</strong>
                        <?= isAvailable($row['category_gite_pool']) ;?>
                    </p>
                    <p class="card-text">
                        <strong>Kitchen :</strong>
                        <?= isAvailable($row['category_gite_kitchen']) ;?>
                    </p>
                </div>
                <div class="card-footer text-center mt-auto w-100 border-top-0 bg-dark">
                    <?php 
                        if(isset($_SESSION['client-connected']) && isset($_SESSION['client-email'])) {
                    ?>
                    <!-- Button trigger modal for reservation -->
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#reserveModal">
                        Reserve
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content bg-dark">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="reserveModalLabel"><?= ucwords(htmlspecialchars($row['gite_name'])) ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <?php // TODO : booking form and class ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        } else {
                            echo $_SESSION['admin-email'];
                    ?>
                    <p class="text-center">Sign in or sign up to make a reservation</p>
                    <?php
                        }
                    ?>
                </div>
                    
            </div>
        </div>
    </div>

<?php
    $title = "Darkbnb - " . ucwords($row['gite_name']);
    $content = ob_get_clean();
    require "template.php";
    }
?>