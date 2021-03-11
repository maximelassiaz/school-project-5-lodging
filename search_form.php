<?php
    require_once "classes/Database.php";
    $search = new Database();
    $res = $search->searchForm();
    $giteCategory = $search->displayGiteCategory();
    // TODO : add date picker with bootstrap extension
    // TODO : put default value if isset (from previous query) ?
?>

<form>
    <div class="form-group">
        <label for="booking_date_arrival">Date d'arrivée</label>
        <input type="date" class="form-control" id="booking_date_arrival" name="date_arrival">
    </div>
    <div class="form-group">
        <label for="booking_date_departure">Date de départ</label>
        <input type="date" class="form-control" id="booking_date_departure" name="date_departure">
    </div>
    <div class="form-group">
        <label for="gite-name">Nom du logement</label>
        <input type="text" class="form-control" id="gite-name" name="name">
    </div>
    <div class="form-group">
        <label for="gite-type">Type de logement</label>
        <select class="form-control" id="gite-type" name="type">
            <option value=""></option>
            <?php
                foreach ($giteCategory as $g) {
            ?>
            <option value="<?= htmlspecialchars($g['category_gite_name']) ;?>"><?= ucfirst(htmlspecialchars($g['category_gite_name'])) ;?></option>
            <?php
                }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="gite-city">Ville</label>
        <input type="text" class="form-control" id="gite-city" name="city">
    </div>
    <div class="form-group">
        <label for="gite-postal">Code postal</label>
        <input type="text" class="form-control" id="gite-postal" name="postal">
    </div>
    <div class="form-group">
        <label for="gite-price">Prix par nuit</label>
        <input type="range" min="<?= floor(htmlspecialchars($res['gite_price_min']));?>" max="<?= ceil(htmlspecialchars($res['gite_price_max']));?>" step="1" class="form-control-range" id="gite-price" name="price" value="<?= ceil(htmlspecialchars($res['gite_price_max']));?>">
    </div>
    <div class="form-group">
        <label for="gite-bed">Nombre de chambres</label>
        <input type="range" min="<?= htmlspecialchars($res['gite_bed_min']);?>" max="<?= htmlspecialchars($res['gite_bed_max']);?>" step="1" class="form-control-range" id="gite-bed" name="bed" value="<?= htmlspecialchars($res['gite_bed_max']);?>">
    </div>
    <div class="form-group">
        <label for="gite-bathroom">Nombre de salles de bains</label>
        <input type="range" min="<?= htmlspecialchars($res['gite_bathroom_min']) ;?>" max="<?= htmlspecialchars($res['gite_bathroom_max']);?>" step="1" class="form-control-range" id="gite-bathroom" name="bathroom" value="<?= htmlspecialchars($res['gite_bathroom_max']);?>">
    </div>
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="gite-garden" name="garden">
        <label class="form-check-label" for="gite-garden">Possède un jardin</label>
    </div>
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="gite-pool" name="pool">
        <label class="form-check-label" for="gite-pool">Possède une piscine</label>
    </div>
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="gite-kitchen" name="kitchen">
        <label class="form-check-label" for="gite-kitchen">Possède une cuisine</label>
    </div>
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="gite-available" name="available">
        <label class="form-check-label" for="gite-available">Montrer les logements non disponibles</label>
    </div>
    <button type="submit" class="btn btn-primary" name="query-submit">Rechercher</button>
    <a class="btn btn-primary" href="<?= htmlspecialchars($_SERVER['PHP_SELF']);?>" role="button">Reset</a>
</form>

<?php
    require_once "functions.php";
    require_once "validate_form_input.php";

    // TODO : print errors array
    if (count($errors) > 0) { ?>
        <div class="bg-danger">
    <?php
        foreach ($errors as $err) {
            echo "$err <br>";
        }
    ?>
        </div>
<?php
    }
    
?>