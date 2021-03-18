<?php
    session_start();
    ob_start();
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

<!-- Show success or failure message for signup -->
<?php
    if (isset($_GET['signup'])) {
        $signup = $_GET['signup'];
?>
        <div class="alert alert-<?= $signup === "failure" ? "danger" : "success" ?> alert-dismissible fade show mx-auto my-4 w-50" role="alert">
            <?= $signup === "failure" ? "An error occured, please try again or contact support" : "" ?>
            <?= $signup === "success" ? "Congratulation, you are now registered, please login now" : "" ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
<?php       
    }
?>

<!-- Show error if login fails -->
<?php
    if (isset($_SESSION['login-error']) && strlen($_SESSION['login-error']) > 0 ) {
?>
    <div class="alert alert-warning alert-dismissible fade show mx-auto my-4 w-75" role="alert">
    <?= htmlspecialchars($_SESSION['login-error']) . "<br>";?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php
    unset($_SESSION['login-error']);   
    }
?>

<!-- Show error(s) if signup fails -->
<?php
    if (isset($_SESSION['signup-error']) && count($_SESSION['signup-error']) > 0 ) {
        $signupErrors = $_SESSION['signup-error'];
?>
    <div class="alert alert-warning alert-dismissible fade show mx-auto my-4 w-75" role="alert">
    <?php 
        foreach($signupErrors as $e) {
            echo htmlspecialchars($e) . "<br>";
        }
    ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php
        unset($_SESSION['signup-error']);   
    }
?>


<!-- Show error(s) if wrong input were inserted in search form -->
<?php
    if (isset($_SESSION['filter-error']) && count($_SESSION['filter-error']) > 0 ) {
        $filterErrors = $_SESSION['filter-error'];
?>
    <div class="alert alert-warning alert-dismissible fade show mx-auto my-4 w-75" role="alert">
    <?php 
        foreach($filterErrors as $e) {
            echo htmlspecialchars($e) . "<br>";
        }
    ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php
        unset($_SESSION['filter-error']);   
    }
?>

<?php 
    require_once "search_form.php";
?>

<!-- Print count of available property, and non available property if checkbox has been checked -->
<p class="text-center text-white m-5">
    <?= htmlspecialchars($count) ;?> lodging(s) available.<?= $countNA > 0 && isset($_GET['available']) ? "<br> " . htmlspecialchars($countNA) . " lodging(s) non available after your search filter." : "" ;?>
</p>



<div class="row row-cols-1 row-cols-xl-2 mx-5">
<?php
    
    foreach($rows as $row) {
?>
    <!-- Available property depending on search results -->
    <div class="col mb-4">
        <div class="card mb-3 bg-dark text-white">
            <div class="row no-gutters">
                <div class="col-md-6">
                    <img src="public/images-property/<?= htmlspecialchars($row['gite_image']) ;?>" class="card-img-top sticky-top" alt="...">
                </div>
                <div class="col-md-6 d-flex flex-column">
                    <div class="card-body">
                        <h4 class="card-title mb-5"><?= strtoupper(htmlspecialchars($row['gite_name'])) ;?></h4>
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
                            </tbody>
                        </table>
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
    if (isset($_GET['available'])) {
        foreach ($rowsNA as $rowNA) {
?>
     <!-- Non available property depending on search results -->
    <div class="col mb-4">
        <div class="card card-NA mb-3 bg-dark text-white">
            <div class="row no-gutters">
                <div class="col-md-6">
                    <img src="public/images-property/<?= htmlspecialchars($rowNA['gite_image']) ;?>" class="card-img-top sticky-top" alt="...">
                </div>
                <div class="col-md-6 d-flex flex-column">
                    <div class="card-body">
                        <h4 class="card-title mb-5">
                            <?= strtoupper(htmlspecialchars($rowNA['gite_name'])) ;?> <br>
                            <span class="text-danger">NON AVAILABLE</span>
                        </h4>
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
                            </tbody>
                        </table>
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
    $title = "Darkbnb - Lodging list";
    $content = ob_get_clean();
    require "template.php";
?>