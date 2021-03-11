<?php
    ob_start();
?>

<table class="table table-striped table-dark">
    <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">First</th>
                <th scope="col">Last</th>
                <th scope="col">Handle</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">1</th>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
            </tr>
    </tbody>
</table>

<?php
    $content = ob_get_clean();
    require "../template.php";
?>