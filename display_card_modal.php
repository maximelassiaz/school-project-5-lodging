<?php
    require_once "functions.php";
?>

<!-- Button trigger modal to display details -->
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#DetailsModal<?= htmlspecialchars($row['gite_id']) ?>">
Détails
</button>

<!-- Modal to display more details -->
<div class="modal fade" id="DetailsModal<?= htmlspecialchars($row['gite_id']) ?>" tabindex="-1" aria-labelledby="DetailsModalLabel<?= htmlspecialchars($row['gite_id']) ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="DetailsModalLabel<?= htmlspecialchars($row['gite_id']) ?>"><?= ucwords(htmlspecialchars($row['gite_name']))?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <img src="public/images/<?= htmlspecialchars($row['gite_image']) ;?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"><?= ucwords(htmlspecialchars($row['gite_name']))?></h5>
                        <p class="card-text"><?= htmlspecialchars($row['gite_description']) ?></p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <h6 class="card-title">Adresse :</h6>
                            <p class="card-text">
                                <?= htmlspecialchars($row['gite_street']) ?> <br> 
                                <?= htmlspecialchars($row['gite_postal']) ?> 
                                <?= htmlspecialchars($row['gite_city']) ?> <br> 
                                <?= htmlspecialchars($row['gite_country']) ?>
                            </p>
                        </li>
                        <li class="list-group-item">
                            <h6 class="card-title">Informations sur le logement :</h6>
                            <p class="card-text">
                                <strong>Type de logement :</strong>
                                <?= htmlspecialchars($row['category_gite_name']) ;?>
                            </p>
                            <p class="card-text">
                                <strong>Disponibilité : </strong>                                    
                                <?php
                                    isAvailable($row['gite_maintenance']);
                                ?>
                            </p>
                            <p class="card-text">
                                <strong>Prix d'une nuitée :</strong>
                                <?= number_format(htmlspecialchars($row['gite_price']), 2, ",", " ") ;?> €
                            </p>
                            
                            <p class="card-text">
                                <strong>Nombre de chambres :</strong>
                                <?= htmlspecialchars($row['gite_bedroom']) ;?>
                            </p>
                            <p class="card-text">
                                <strong>Nombre de salles de bain :</strong>
                                <?= htmlspecialchars($row['gite_bathroom']) ;?>
                            </p>
                            <p class="card-text">
                                <strong>Jardin :</strong>
                                <?= isAvailable($row['category_gite_garden']) ;?>
                            </p>
                            <p class="card-text">
                                <strong>Piscine :</strong>
                                <?= isAvailable($row['category_gite_pool']) ;?>
                            </p>
                            <p class="card-text">
                                <strong>Cuisine :</strong>
                                <?= isAvailable($row['category_gite_kitchen']) ;?>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <?php 
                    // TODO : create a link to a booking form then do the CRUD / or just create a new booking IF the user is a client 
                    if (empty($_SESSION)) { 
                ?>
                <button type="button" class="btn btn-info" data-dismiss="modal">Réserver</button>
                <?php
                    }                
                ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                
            </div>
        </div>
    </div>
</div>