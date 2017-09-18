<?php if(!isset($_SESSION['is_logged_in'])) {
    die('Sie müssen Registriert und eingeloggt sein um diese Plattform nutzen zu können. </a>');
} ?>


<?php
require 'classes/DB.php';

$database = new DB;

//Alle Artikel Anzeigen
$currentUser = $_SESSION['user_data']['email'];

$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

$database->query('SELECT * FROM product WHERE prodEmail = "'.$currentUser.'" order by prodCreateDate DESC');
$rows = $database->resultset();

$database->query('SELECT product.prodID, product.prodDesc, product.prodCat, product.prodAmount, offer.offPrice, offer.offEmail, offer.offID FROM product LEFT JOIN offer ON product.prodID = offer.prodID where offer.offPrice IS NOT NULL AND offer.offEmail = "'.$currentUser.'"' );
$rowsoff = $database->resultset();


// Prüft, ob ein POST gemacht wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

//Suchfunktion
    if (isset($_POST['search'])) {

        if ($_POST['search'] != '') {
            $search = $post['search'];
            $database->query("SELECT * FROM product WHERE prodDesc LIKE '%{$search}%' OR prodCat LIKE '%{$search}%'");
            $database->execute();
            $rows = $database->resultset();
        } else {
            echo '<div class="alert alert-danger">Bitte geben Sie einen Suchbegriff ein!</div>';
        }
    }


    //Angebotsdaten an die Datenbank senden.

    if (isset($_POST['prodID'])){

        if ($_POST['prodID'] != ''){
            $prodID = $_POST['prodID'];
            $offEmail = $post['offerEmail'];
            $offPrice = $post['offerPrice'];

            $database->query('INSERT INTO offer (prodID, offEmail, offPrice) VALUES(:prodID, :offEmail, :offPrice)');

            $database->bind(':prodID', $prodID);
            $database->bind(':offEmail', $offEmail);
            $database->bind(':offPrice', $offPrice);

            $database->execute();

            if ($database->lastInsertId()) {
                echo '<p>Angebot Erfasst!</p>';
            }

        } else {
            echo 'Angebot konnte nicht gespeichert werden';
        }
    }

}
?>

<div class="row">
<div class="col-md-12">
    <div class="panel panel-warning">
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
    <div class="panel panel-success">
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
                                <input class="btn btn-success text-center" type="submit" name="offer" value="Bestellung erfassen" />
                            </form>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>



















