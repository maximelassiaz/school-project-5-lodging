<?php
    session_start();
    ob_start();
    $title = "Darkbnb dashboard";
    if (!isset($_SESSION['admin-connected']) && !isset($_SESSION['admin-email']) && !isset($_SESSION['admin_id'])) {
        // TODO : proper redirection
        header("Location: index.php");
        exit();
    } else {
    require_once "search_form.php";
    require_once "classes/LodgingManager.php";
    $dashboard = new LodgingManager();
    // Available property
    $rows = $dashboard->getLodgingList()['rows'];
    $count = $dashboard->getLodgingList()['count'];
    $sql = $dashboard->getLodgingList()['sql'];
    $parameters = $dashboard->getLodgingList()['parameters'];

    // Non available property
    $rowsNA = $dashboard->getLodgingListNonAvailable($sql, $parameters)['rows'];
    $countNA = $dashboard->getLodgingListNonAvailable($sql, $parameters)['count'];
    $giteCategory = $dashboard->displayGiteCategory();
?>

<!-- Show error(s) if wrong inputs were used when trying to update lodging -->
<?php
    if (isset($_SESSION['update-error']) && count($_SESSION['update-error']) > 0 ) {
        $updateErrors = $_SESSION['update-error'];
?>
    <div class="alert alert-warning alert-dismissible fade show mx-auto my-4 w-75" role="alert">
    <?php 
        foreach($updateErrors as $e) {
            echo htmlspecialchars($e) . "<br>";
        }
    ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php
        unset($_SESSION['update-error']);   
    }
?>

<!-- Show success or failure message for delete property -->
<?php
    if (isset($_GET['delete'])) {
        $delete = $_GET['delete'];
?>
        <div class="alert alert-<?= $delete === "failure" ? "danger" : "success" ?> alert-dismissible fade show mx-auto my-4 w-50" role="alert">
            <?= $delete === "failure" ? "An error occured while deleting a property, please try again or contact support." : "" ?>
            <?= $delete === "success" ? "Property has been deleted." : "" ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
<?php       
    }
?>

<!-- Show success or failure message for create property -->
<?php
    if (isset($_GET['create'])) {
        $create = $_GET['create'];
?>
        <div class="alert alert-<?= $create === "success" ? "success" : "danger" ?> alert-dismissible fade show mx-auto my-4 w-50" role="alert">
            <?= $create === "emptyfields" ? "All fields must be filled in the add property form." : "" ?>
            <?= $create === "failure" ? "An error occured while creating a new property, please try again or contact support." : "" ?>
            <?= $create === "success" ? "Property has been successfully created." : "" ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
<?php       
    }
?>

<!-- Show success or failure message for updating property -->
<?php
    if (isset($_GET['update'])) {
        $update = $_GET['update'];
?>
        <div class="alert alert-<?= $update === "failure" ? "danger" : "success" ?> alert-dismissible fade show mx-auto my-4 w-50" role="alert">
            <?= $update === "emptyfields" ? "All fields must be filled in the update property form." : "" ?>
            <?= $update === "failure" ? "An error occured while updating a property, please try again or contact support." : "" ?>
            <?= $update === "success" ? "Property has been updated." : "" ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
<?php       
    }
?>


<!-- Button trigger modal for creating a property -->
<button type="button" class="btn btn-info text-white m-5" data-toggle="modal" data-target="#createModal">
  Add a property
</button>

<!-- Modal for creating a property -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title" id="createModalLabel">Add a property</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="addProperty.php" enctype='multipart/form-data'>
            <div class="form-group">
                <label for="create-name">Property name</label>
                <input type="text" class="form-control" id="create-name" name="create-name" placeholder="Property name" required>
            </div>
            <div class="form-group">
                <label for="create-description">Property description</label>
                <textarea class="form-control" id="create-description" name="create-description" rows="3" placeholder="Property description" required></textarea>
            </div>
            <div class="form-group">
                <label for="create-type">Property type</label>
                <select class="form-control" id="create-type" name="create-type" required>
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
                <input type="file" class="form-control-file" id="create-image" name="create-image" required>
            </div>
            <div class="form-group">
                <label for="create-street">Property street</label>
                <input type="text" class="form-control" id="create-street" name="create-street" placeholder="Street" required>
            </div>
            <div class="form-group">
                <label for="create-postal">Property postal code</label>
                <input type="number" class="form-control" id="create-postal" name="create-postal" placeholder="Postal code" required>
            </div>
            <div class="form-group">
                <label for="create-city">Property city</label>
                <input type="text" class="form-control" id="create-city" name="create-city" placeholder="City" required>
            </div>
            <div class="form-group">
                <label for="create-country">Property country</label>
                <input type="text" class="form-control" id="create-country" name="create-country" placeholder="Country" required>
            </div>
            <div class="form-group">
                <label for="create-price">Price per night</label>
                <input type="number" class="form-control" id="create-price" name="create-price" placeholder="Price per night" required>
            </div>
            <div class="form-group">
                <label for="create-guest">Maximum number of guests</label>
                <input type="number" class="form-control" id="create-guest" name="create-guest" placeholder="Maximum number of guests" required>
            </div>
            <div class="form-group">
                <label for="create-bed">Number of beds</label>
                <input type="number" class="form-control" id="create-bed" name="create-bed" placeholder="Number of beds" required>
            </div>
            <div class="form-group">
                <label for="create-bathroom">Number of bathroom</label>
                <input type="number" class="form-control" id="create-bathroom" name="create-bathroom" placeholder="Number of bathroom" required>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="create-wifi">
                <label class="form-check-label" for="create-wifi" name="create-wifi">WiFi</label>
            </div>
            <!-- Show error(s) if wrong inputs were used when trying to create new lodging -->
            <?php
                if (isset($_SESSION['create-error']) && count($_SESSION['create-error']) > 0 ) {
                    $createErrors = $_SESSION['create-error'];
            ?>
                <div class="alert alert-warning alert-dismissible fade show mx-auto my-4 w-75" role="alert">
                <?php 
                    foreach($createErrors as $e) {
                        echo htmlspecialchars($e) . "<br>";
                    }
                ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php
                    unset($_SESSION['create-error']);   
                }
            ?>
            <button type="submit" class="btn btn-info" name="create-submit">Add new property</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<p class="text-center text-white m-5"><?= htmlspecialchars($count) ;?> result(s) available<?= $countNA > 0 && isset($_GET['available']) ? ", <br>" . htmlspecialchars($countNA) . " results non available" : "" ;?></p>

<!-- Property dashboard -->
<table class="table table-dark">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Property name</th>
            <th scope="col">Property type</th>
            <th scope="col">Full address</th>
            <th scope="col">Price / night</th>            
            <th scope="col" colspan="3">Management</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach($rows as $row) {
        ?>
        <tr>
            <th scope="row">
                <?= htmlspecialchars($row['gite_id']) ?>
            </th>
            <td>
                <?= mb_strtoupper(htmlspecialchars($row['gite_name']), "utf-8") ?>
            </td>
            <td>
                <?= mb_strtoupper(htmlspecialchars($row['category_gite_name']), "utf-8") ?>
            </td>
            <td>
                <?= mb_strtoupper(htmlspecialchars($row['gite_street']), "utf-8") ?> <br> 
                <?= mb_strtoupper(htmlspecialchars($row['gite_city']), "utf-8") ?> 
                <?= htmlspecialchars($row['gite_postal']) ?>
            </td>
            <td>
                <?= number_format(htmlspecialchars($row['gite_price']), 2, ",", " ") ?> €
            </td>
            <td>
                <a class="btn btn-info" target="_blank" href="details.php?id=<?= htmlspecialchars($row['gite_id']) ;?>" role="button">Details</a>
            </td>          
            <td>
                <!-- Button trigger modal for updating a property -->
                <button type="button" class="btn btn-warning text-white" data-toggle="modal" data-target="#updateModal<?= $row['gite_id'] ?>">
                    Update
                </button>

                <!-- Modal for updating a property -->
                <div class="modal fade" id="updateModal<?= $row['gite_id'] ?>" tabindex="-1" aria-labelledby="updateModalLabel<?= $row['gite_id'] ?>" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content bg-dark text-white">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel<?= $row['gite_id'] ?>">Update a property</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="updateProperty.php" enctype='multipart/form-data'>
                                    <div class="form-group">
                                        <label for="update-name">Property name</label>
                                        <input type="text" class="form-control" id="update-name" name="update-name" value="<?= $row['gite_name'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="update-description">Property description</label>
                                        <textarea class="form-control" id="update-description" name="update-description" rows="3"><?= $row['gite_description'] ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="update-type">Property type</label>
                                        <select class="form-control" id="update-type" name="update-type">
                                        <option value="<?= $row['category_gite_id'] ?>"><?= strtoupper($row['category_gite_name']) ?></option>
                                        <?php
                                            foreach ($giteCategory as $g) {
                                        ?>
                                        <option value="<?= htmlspecialchars($g['category_gite_id']) ;?>"><?= strtoupper(htmlspecialchars($g['category_gite_name'])) ;?></option>
                                        <?php
                                            }
                                        ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="update-image">Choose a property picture</label>
                                        <input type="file" class="form-control-file" id="update-image" name="update-image">
                                    </div>
                                    <div class="form-group">
                                        <label for="update-street">Property street</label>
                                        <input type="text" class="form-control" id="update-street" name="update-street" value="<?= $row['gite_street'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="update-postal">Property postal code</label>
                                        <input type="number" class="form-control" id="update-postal" name="update-postal" value="<?= $row['gite_postal'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="update-city">Property city</label>
                                        <input type="text" class="form-control" id="update-city" name="update-city" value="<?= $row['gite_city'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="update-country">Property country</label>
                                        <input type="text" class="form-control" id="update-country" name="update-country" value="<?= $row['gite_country'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="update-price">Price per night</label>
                                        <input type="number" class="form-control" id="update-price" name="update-price" value="<?= $row['gite_price'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="update-guest">Maximum number of guests</label>
                                        <input type="number" class="form-control" id="update-guest" name="update-guest" value="<?= $row['gite_guest'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="update-bed">Number of beds</label>
                                        <input type="number" class="form-control" id="update-bed" name="update-bed" value="<?= $row['gite_bed'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="update-bathroom">Number of bathroom</label>
                                        <input type="number" class="form-control" id="update-bathroom" name="update-bathroom" value="<?= $row['gite_bathroom'] ?>">
                                    </div>
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" id="update-wifi" <?= (int)$row['gite_wifi'] === 1 ? "checked" : "" ?>>
                                        <label class="form-check-label" for="update-wifi" name="update-wifi">WiFi</label>
                                    </div>
                                    <input type="hidden" name="update-id" value="<?= $row['gite_id'] ?>">
                                    <button type="submit" class="btn btn-warning" name="update-submit">Update</button>
                                </form>
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
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal<?= $row['gite_id'] ?>">
                    Delete
                </button>

                <!-- Modal for delete modal -->
                <div class="modal fade" id="deleteModal<?= $row['gite_id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $row['gite_id'] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content bg-dark">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel<?= $row['gite_id'] ?>"><?= strtoupper($row['gite_name']) ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h6 class="text-center">Warning !</h6>
                                <p class="text-center">Deleting a property is a permanent action.</p>
                                <form method="POST" action="deleteProperty.php" class="text-center">
                                    <input type="hidden" class="form-check-input" name="delete-gite-id" value="<?= $row['gite_id'] ?>">                            
                                    <button type="submit" class="btn btn-danger mx-auto" name="delete-gite-submit">Delete</button>
                                </form>
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
    }
    $content = ob_get_clean();
    require "template.php";
?>