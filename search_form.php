<?php
    require_once "classes/Lodging.php";
    $search = new Lodging();
    $res = $search->searchForm();
    $giteCategorySearch = $search->displayGiteCategorySearch();
    // TODO : add date picker with bootstrap extension
    // TODO : put default value if isset (from previous query) ?
?>

<button class="btn btn-secondary ml-5 m-5" type="button" data-toggle="collapse" data-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">
    Show filter form
</button>

<div class="collapse" id="collapseForm">
    <form>
        <div class="form-row col-md-8 mx-auto p-3 text-white bg-dark">
            <div class="form-group col-md-6">
                <label for="booking_date_arrival">Check in</label>
                <input type="date" class="form-control" id="booking_date_arrival" name="date_arrival">
            </div>
            <div class="form-group col-md-6">
                <label for="booking_date_departure">Check out</label>
                <input type="date" class="form-control" id="booking_date_departure" name="date_departure">
            </div>
            <div class="form-group col-md-6">
                <label for="gite-name">Property info</label>
                <input type="text" class="form-control" id="gite-name" name="name">
            </div>
            <div class="form-group col-md-6">
                <label for="gite-type">Property type</label>
                <select class="form-control" id="gite-type" name="type">
                    <option value=""></option>
                    <?php
                        foreach ($giteCategorySearch as $g) {
                    ?>
                    <option value="<?= htmlspecialchars($g['category_gite_name']) ;?>"><?= ucfirst(htmlspecialchars($g['category_gite_name'])) ;?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="gite-city">City</label>
                <input type="text" class="form-control" id="gite-city" name="city">
            </div>
            <div class="form-group col-md-6">
                <label for="gite-postal">Postal code</label>
                <input type="text" class="form-control" id="gite-postal" name="postal">
            </div>
            <div class="form-group col-md-6">
                <label for="gite-price">Price / night</label>
                <input type="range" min="<?= floor(htmlspecialchars($res['gite_price_min']));?>" max="<?= ceil(htmlspecialchars($res['gite_price_max']));?>" step="1" class="form-control-range" id="gite-price" name="price" value="<?= ceil(htmlspecialchars($res['gite_price_max']));?>">
            </div> 
            <div class="form-group col-md-6">
                <label for="gite-guest">Guest(s)</label>
                <input type="range" min="<?= htmlspecialchars($res['gite_guest_min']);?>" max="<?= htmlspecialchars($res['gite_guest_max']);?>" step="1" class="form-control-range" id="gite-guest" name="guest" value="<?= htmlspecialchars($res['gite_guest_max']);?>">
            </div> 
            <div class="form-group col-md-6">
                <label for="gite-bed">Bed(s)</label>
                <input type="range" min="<?= htmlspecialchars($res['gite_bed_min']);?>" max="<?= htmlspecialchars($res['gite_bed_max']);?>" step="1" class="form-control-range" id="gite-bed" name="bed" value="<?= htmlspecialchars($res['gite_bed_max']);?>">
            </div>
           <div class="form-group col-md-6">
                <label for="gite-bathroom">Bathroom(s)</label>
                <input type="range" min="<?= htmlspecialchars($res['gite_bathroom_min']) ;?>" max="<?= htmlspecialchars($res['gite_bathroom_max']);?>" step="1" class="form-control-range" id="gite-bathroom" name="bathroom" value="<?= htmlspecialchars($res['gite_bathroom_max']);?>">
            </div> 
            <div class="form-group form-check col-md-3">
                <input type="checkbox" class="form-check-input" id="gite-wifi" name="wifi">
                <label class="form-check-label" for="gite-wifi">Wifi</label>
            </div>
            <div class="form-group form-check col-md-3">
                <input type="checkbox" class="form-check-input" id="gite-garden" name="garden">
                <label class="form-check-label" for="gite-garden">Garden</label>
            </div>
            <div class="form-group form-check col-md-3">
                <input type="checkbox" class="form-check-input" id="gite-pool" name="pool">
                <label class="form-check-label" for="gite-pool">Pool</label>
            </div>
            <div class="form-group form-check col-md-3">
                <input type="checkbox" class="form-check-input" id="gite-kitchen" name="kitchen">
                <label class="form-check-label" for="gite-kitchen">Kitchen</label>
            </div>
            <div class="form-group form-check col-md-12">
                <input type="checkbox" class="form-check-input" id="gite-available" name="available">
                <label class="form-check-label" for="gite-available">Show non available</label>
            </div>
            <div class="col-md-12 mx-auto text-center">
                <button type="submit" class="btn btn-primary" name="query-submit">Filter</button>
                <a class="btn btn-primary" href="<?= htmlspecialchars($_SERVER['PHP_SELF']);?>" role="button">Reset</a>
            </div>        
        </div>
    </form>
</div>