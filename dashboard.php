<?php
    ob_start();
    $title = "Darkbnb dashboard";
    // TODO : Check is admin is connected via session variables, redirect if not;
    require_once "search_form.php";
    require_once "classes/LodgingManager.php";
    $dashboard = new LodgingManager();
    $rows = $dashboard->getLodgingList($query, $parameters)['rows'];
    $count = $dashboard->getLodgingList($query, $parameters)['count'];
    $sql = $dashboard->getLodgingList($query, $parameters)['sql'];
    $rowsNA = $dashboard->getLodgingListNonAvailable($sql, $parameters)['rows'];
    $countNA = $dashboard->getLodgingListNonAvailable($sql, $parameters)['count'];
    $giteCategory = $dashboard->displayGiteCategory();
?>

<?php // TODO : Create form and modal button to add a lodging ?>

<!-- Button trigger modal for creating a property -->
<button type="button" class="btn btn-info text-white" data-toggle="modal" data-target="#createModal">
  Add a property
</button>

<!-- Modal for creating a property -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createModalLabel">Add a property</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="" enctype='multipart/form-data'>
            <div class="form-group">
                <label for="create-name">Property name</label>
                <input type="text" class="form-control" id="create-name" name="create-name" placeholder="Property name">
            </div>
            <div class="form-group">
                <label for="create-description">Property description</label>
                <textarea class="form-control" id="create-description" name="create-description" rows="3" placeholder="Property description"></textarea>
            </div>
            <div class="form-group">
                <label for="create-type">Property type</label>
                <select class="form-control" id="create-type" name="create-type">
                <option value="">Select</option>
                <?php
                    foreach ($giteCategory as $g) {
                ?>
                <option value="<?= htmlspecialchars($g['category_gite_id']) ;?>"><?= ucfirst(htmlspecialchars($g['category_gite_name'])) ;?></option>
                <?php
                    }
                ?>
                </select>
            </div>
            <div class="form-group">
                <label for="create-image">Choose a property picture</label>
                <input type="file" class="form-control-file" id="create-image" name="create-image">
            </div>
            <div class="form-group">
                <label for="create-street">Property street</label>
                <input type="text" class="form-control" id="create-street" name="create-street" placeholder="Street">
            </div>
            <div class="form-group">
                <label for="create-postal">Property postal code</label>
                <input type="number" class="form-control" id="create-postal" name="create-postal" placeholder="Postal code">
            </div>
            <div class="form-group">
                <label for="create-city">Property city</label>
                <input type="text" class="form-control" id="create-city" name="create-city" placeholder="City">
            </div>
            <div class="form-group">
                <label for="create-country">Property country</label>
                <input type="text" class="form-control" id="create-country" name="create-country" placeholder="Country">
            </div>
            <div class="form-group">
                <label for="create-price">Price per night</label>
                <input type="number" class="form-control" id="create-price" name="create-price" placeholder="Price per night">
            </div>
            <div class="form-group">
                <label for="create-guest">Maximum number of guests</label>
                <input type="number" class="form-control" id="create-guest" name="create-guest" placeholder="Maximum number of guests">
            </div>
            <div class="form-group">
                <label for="create-bed">Number of beds</label>
                <input type="number" class="form-control" id="create-bed" name="create-bed" placeholder="Number of beds">
            </div>
            <div class="form-group">
                <label for="create-bathroom">Number of bathroom</label>
                <input type="number" class="form-control" id="create-bathroom" name="create-bathroom" placeholder="Number of bathroom">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="create-wifi">
                <label class="form-check-label" for="create-wifi" name="create-wifi">WiFi</label>
            </div>
            <button type="submit" class="btn btn-info" name="create-submit">Add new property</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php $dashboard->addLodging(); ?>
          

<p><?= htmlspecialchars($count) ;?> result(s)</p>
<p><?= htmlspecialchars($countNA);?> result(s)</p>

<table class="table table-dark">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Property name</th>
            <th scope="col">Property type</th>
            <th scope="col">Full address</th>
            <th scope="col">Price per night</th>            
            <th scope="col" colspan="3">Management</th>
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
            <!-- Display more details using a modal button -->
            <td>
                <?php
                    require "display_card_modal.php";
                ?>
            </td>          
            <td>
                <!-- Button trigger modal for update modal -->
                <button type="button" class="btn btn-warning text-white" data-toggle="modal" data-target="#staticBackdrop">
                    Update
                </button>

                <!-- Modal for update modal -->
                <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
                </div>
            </td>
            <td>
                <!-- Button trigger modal for delete modal -->
                <button type="button" class="btn btn-danger text-white" data-toggle="modal" data-target="#staticBackdrop">
                    Delete
                </button>

                <!-- Modal for delete modal -->
                <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <?php
            }
        ?>
    </tbody>
</table>

<?php 
    $content = ob_get_clean();
    require "template.php";
?>