<?php
    // TODO : rewrite with regex
    if(!isset($_GET['id']) || (int)$_GET['id'] < 0 || !preg_match("/\d/", $_GET['id'])) {
        header("Location: index.php");
        exit();
    } else {  
    session_start();  
    ob_start();
    require_once "classes/Lodging.php";
    $lodging = new Lodging();
    $row = $lodging->getLodging((int)$_GET['id']);
?>

<?php
    if (isset($_GET['booking'])) {
        $booking = $_GET['booking'];
?>
        <div class="alert alert-<?= $booking === "failure" ? "danger" : "success" ?> alert-dismissible fade show mx-auto my-4 w-50" role="alert">
            <?= $booking === "notavailable" ? "Sorry the lodging is not available for the date you set" : "" ?>
            <?= $booking === "emptyfields" ? "You must fill both date fields" : "" ?>
            <?= $booking === "success" ? "Congratulation, booking is successful, we sent you a email" : "" ?>
            <?= $booking === "failure" ? "An error occured, please try again or contact support" : "" ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
<?php       
    }
?>

    <div class="card bg-dark text-white m-5">
        <div class="row no-gutters">
            <div class="col-md-6">
                <img src="public/images-property/<?= htmlspecialchars($row['gite_image']) ;?>" class="card-img-top sticky-top" alt="...">
            </div>
            <div class="col-md-6">
                <div class="card-body">
                    <h4 class="card-title mb-5"><?= strtoupper(htmlspecialchars($row['gite_name']))?></h4>
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
                    <table class="table table-dark mt-2">
                        <tbody>
                            <tr>
                                <th scope="row">Property type</th>
                                <td><?= ucwords(htmlspecialchars($row['category_gite_name']));?></td>
                            </tr>
                            <tr>
                                <th scope="row">Location</th>
                                <td>
                                <?= htmlspecialchars($row['gite_postal']) ?>
                                <?= strtoupper(htmlspecialchars($row['gite_city'])) ?>
                            </td>
                            </tr>
                            <tr>
                                <th scope="row">Price / night</th>
                                <td><?= number_format(htmlspecialchars($row['gite_price']), 2, ",", " ") ?> €</td>
                            </tr>
                            <tr>
                                <th scope="row">Guest(s)</th>
                                <td><?= htmlspecialchars($row['gite_guest']) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Bed(s)</th>
                                <td><?= htmlspecialchars($row['gite_bed']) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Bathroom(s)</th>
                                <td><?= htmlspecialchars($row['gite_bathroom']) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">WiFi</th>
                                <td><?= isAvailable($row['gite_wifi']) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Garden</th>
                                <td><?= isAvailable($row['category_gite_garden']) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Pool</th>
                                <td><?= isAvailable($row['category_gite_pool']) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Kitchen</th>
                                <td><?= isAvailable($row['category_gite_kitchen']) ?></td>
                            </tr>
                        </tbody>
                    </table>
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
                                    <form method="POST" action="reserve.php">
                                        <div class="form-group col-md-12">
                                            <label for="booking_date_arrival">Check in</label>
                                            <input type="date" class="form-control" id="booking_date_arrival" name="booking_date_arrival" value="<?= date("Y-m-d")?>" required>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="booking_date_departure">Check out</label>
                                            <input type="date" class="form-control" id="booking_date_departure" name="booking_date_departure" required>
                                        </div>
                                        <input type="hidden" name="booking_gite_id" value="<?= htmlspecialchars($row['gite_id']) ;?>">
                                        <input type="hidden" name="booking_gite_name" value="<?= htmlspecialchars($row['gite_name']) ;?>">
                                        <input type="hidden" name="booking_gite_description" value="<?= htmlspecialchars($row['gite_description']) ;?>">
                                        <input type="hidden" name="booking_gite_type" value="<?= htmlspecialchars($row['category_gite_name']) ;?>">
                                        <input type="hidden" name="booking_gite_street" value="<?= htmlspecialchars($row['gite_street']) ;?>">
                                        <input type="hidden" name="booking_gite_city" value="<?= htmlspecialchars($row['gite_city']) ;?>">
                                        <input type="hidden" name="booking_gite_postal" value="<?= htmlspecialchars($row['gite_postal']) ;?>">
                                        <input type="hidden" name="booking_gite_country" value="<?= htmlspecialchars($row['gite_country']) ;?>">
                                        <input type="hidden" name="booking_gite_price" value="<?= htmlspecialchars($row['gite_price']) ;?>">
                                        <input type="hidden" name="booking_gite_guest" value="<?= htmlspecialchars($row['gite_guest']) ;?>">
                                        <input type="hidden" name="booking_gite_bed" value="<?= htmlspecialchars($row['gite_bed']) ;?>">
                                        <input type="hidden" name="booking_gite_bathroom" value="<?= htmlspecialchars($row['gite_bathroom']) ;?>">
                                        <input type="hidden" name="booking_gite_wifi" value="<?= htmlspecialchars($row['gite_wifi']) ;?>">
                                        <input type="hidden" name="booking_gite_garden" value="<?= htmlspecialchars($row['category_gite_garden']) ;?>">
                                        <input type="hidden" name="booking_gite_kitchen" value="<?= htmlspecialchars($row['category_gite_kitchen']) ;?>">
                                        <input type="hidden" name="booking_gite_pool" value="<?= htmlspecialchars($row['category_gite_pool']) ;?>">

                                        <input type="hidden" name="booking_client_id" value="<?= htmlspecialchars($_SESSION['client-id']) ;?>">
                                        <input type="hidden" name="booking_gite_id" value="<?= htmlspecialchars($row['gite_id']) ;?>">                                          
                                        <?php
                                            if (isset($_SESSION['booking-error']) && count($_SESSION['booking-error']) > 0 ) {
                                                $bookingErrors = $_SESSION['booking-error'];
                                        ?>
                                            <div class="alert alert-warning alert-dismissible fade show mx-auto my-4 w-75" role="alert">
                                            <?php 
                                                foreach($bookingErrors as $e) {
                                                    echo htmlspecialchars($e) . "<br>";
                                                }
                                            ?>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        <?php
                                                unset($_SESSION['booking-error']);   
                                            }
                                        ?>
                                        
                                        <button type="booking-submit" class="btn btn-info" name="booking-submit">Reserve</button>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        } else {
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