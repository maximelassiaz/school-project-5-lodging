<?php
    ob_start();
?>

<div class="card" style="width: 18rem;">
    <img src="public/images/<?php // htmlspecialchars($row['gite_image']) ;?>" class="card-img-top" alt="...">
    <div class="card-body">
        <h5 class="card-title"><?php // ucwords(htmlspecialchars($row['gite_name'])) ;?></h5>
        <p class="card-text">
            <strong>Type de logement : </strong>
            <?php // htmlspecialchars($row['category_gite_name']) ?>
        </p>
        <p class="card-text">
            <strong>Localisation : </strong>
            <?php // htmlspecialchars($row['gite_postal']) ?>
            <?php // htmlspecialchars($row['gite_city']) ?>
        </p>
        <p class="card-text">
            <strong>Prix d'une nuitée : </strong>
            <?php // number_format(htmlspecialchars($row['gite_price']), 2, ",", " ") ?> €
        </p>
        <p class="card-text">
            <strong>Nombre de chambres : </strong>
            <?php // htmlspecialchars($row['gite_bedroom']) ?>
        </p>
    </div>
    <div class="card-body text-center">
        <a href="#" class="btn btn-primary">Details</a>
  </div>
</div>

<?php
    $content = ob_get_clean();
    require "../template.php"
?>