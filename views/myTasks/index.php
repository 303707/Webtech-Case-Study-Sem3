<?php if(!isset($_SESSION['is_logged_in'])) {
    die('Sie müssen Registriert und eingeloggt sein um diese Plattform nutzen zu können. </a>');
} ?>


<?php
require 'classes/DB.php';

$database = new DB;

//Nur Datensätze von currentUser
$currentUser = $_SESSION['user_data']['email'];

$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

$database->query('SELECT * FROM product WHERE prodEmail = "'.$currentUser.'" order by prodCreateDate DESC');
$rows = $database->resultset();

$database->query('SELECT product.prodID, product.prodDesc, product.prodCat, product.prodAmount, offer.offPrice, offer.offEmail, offer.offID FROM product LEFT JOIN offer ON product.prodID = offer.prodID where offer.offPrice IS NOT NULL AND offer.offEmail = "'.$currentUser.'"' );
$rowsoff = $database->resultset();


// Prüft, ob ein POST gemacht wurde
if (isset($_POST['deletereq'])) {

//Löscht den gewählten Datensatz!

    if ($_POST['deletereq'] = 'Löschen') {
        $prodID = $_POST['prodID'];
        $database->query('DELETE FROM product WHERE prodID = "' . $prodID . '"');
        $database->execute();
        echo "Artikel und alle dazugehörigen Angebote wurden gelöscht!";
    }
}
if (isset($_POST['deleteoff'])) {
    if ($_POST['deleteoff'] = 'Löschen2') {
        $offID = $_POST['offID'];
        $database->query('DELETE FROM offer WHERE offID = "' . $offID . '"');
        $database->execute();
        echo "Das Angebot wurde gelöscht";
    }
}


?>

<div class="row">
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Meine nachgefragten Artikel</h3>
        </div>
        <div class="panel-body">
            <div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Produktbeschreibung</th>
                        <th>Produktkategorie</th>
                        <th>Menge</th>
                        <th>Gewünschter Lieferzeitpunkt</th>
                        <th>Qualität</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($rows as $row) : ?>
                        <tr>
                            <td><?php echo $row['prodDesc']; ?></td>
                            <td><?php echo $row['prodCat']; ?></td>
                            <td><?php echo $row['prodAmount']; ?></td>
                            <td><?php echo $row['prodDelDate']; ?></td>
                            <td><?php echo $row['prodQual']; ?></td>
                           <td><form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
                                   <input class="btn btn-primary text-center" type="hidden" name="prodID" value="<?php echo $row['prodID']; ?>">
                                   <input class="btn btn-success text-center" type="submit" name="deletereq" value="Löschen" />
                            </form>
                           </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>


<div class="row">
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Meine Angebote</h3>
        </div>
        <div class="panel-body">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Produktbeschreibung</th>
                    <th>Produktkategorie</th>
                    <th>Menge</th>
                    <th>Preis</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($rowsoff as $row) : ?>
                    <tr>
                        <td><?php echo $row['prodDesc']; ?></td>
                        <td><?php echo $row['prodCat']; ?></td>
                        <td><?php echo $row['prodAmount']; ?></td>
                        <td><?php echo $row['offPrice']; ?></td>
                        <td><form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
                                <input class="btn btn-primary text-center" type="hidden" name="offID" value="<?php echo $row['offID']; ?>">
                                <input class="btn btn-primary text-center" type="hidden" name="prodDesc" value="<?php echo $row['prodDesc']; ?>">
                                <input class="btn btn-primary text-center" type="hidden" name="prodID" value="<?php echo $row['prodID']; ?>">
                                <input class="btn btn-primary text-center" type="hidden" name="prodAmount" value="<?php echo $row['prodAmount']; ?>">
                                <input class="btn btn-primary text-center" type="hidden" name="offPrice" value="<?php echo $row['offPrice']; ?>">
                                <input class="btn btn-primary text-center" type="hidden" name="offEmail" value="<?php echo $row['offEmail']; ?>">
                                <input class="btn btn-success text-center" type="submit" name="deleteoff" value="Löschen2" />
                            </form>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>



















