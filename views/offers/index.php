<?php if(!isset($_SESSION['is_logged_in'])) {
        die('Sie müssen Registriert und eingeloggt sein um diese Plattform nutzen zu können. </a>');
} ?>


<?php
require 'classes/DB.php';

if (isset($_POST['submitAngebot'])) {
    echo '<div class="alert alert-success">Dein Angebot wurde erfasst!</div>';
}

$database = new DB;

//Alle Artikel Anzeigen

$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
$database->query('SELECT * FROM product order by prodCreateDate DESC');
$rows = $database->resultset();




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



<!-- Stellt eine Eingabemaske zur verfügung zur Eingabe von String für Suche über gesamte Tabelle-->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title">Nachfragen durchsuchen</h3>
                </div>
                    <div class="panel-body">
                        <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
                            <div class="form-group">
                                <input type="text" class="form-control" id="formGroupExampleInput" name="search" placeholder="..." /><br />
                                <input class="btn btn-warning text-center" type="submit" name="submit" value="Suchen" />
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title">Übersicht aller nachgefragten Artikel</h3>
            </div>
                <div class="panel-body">
                    <!--Zeigt alle Artikel die in der Produkttabelle existieren-->
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
                                        <input class="btn btn-primary text-center" type="hidden" name="row_id" value="<?php echo $row['prodID']; ?>">
                                        <input class="btn btn-primary text-center" type="hidden" name="prodDesc" value="<?php echo $row['prodDesc']; ?>">
                                        <input class="btn btn-primary text-center" type="hidden" name="prodAmount" value="<?php echo $row['prodAmount']; ?>">
                                        <input class="btn btn-warning text-center" type="submit" name="offer" value="Angebot erfassen" />
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
        </div>
    </div>

<!-- Angebotsdaten Aufnehmen-->
        <div class="col-md-4">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title">Angebot erfassen zu Nachfrage Nr. <?php if(isset($_POST['row_id'])) {echo $_POST['row_id'];} ?></h3>
                </div>
                    <div class="panel-body">
                        <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
                            <div class="form-group">
                                <label for="formGroupExampleInput">Produktbeschreibung:</label>
                                <input type="text" class="form-control" id="formGroupExampleInput" name="prodID" value="<?php if(isset($_POST['row_id'])) {echo $_POST['prodDesc'];} ?>"readonly="readonly"><br />
                                <label for="formGroupExampleInput">Produkt ID:</label>
                                <input type="text" class="form-control" id="formGroupExampleInput" name="prodID" value="<?php if(isset($_POST['row_id'])) {echo $_POST['row_id'];} ?>"readonly="readonly"><br />
                                <label for="formGroupExampleInput">Email:</label>
                                <input type="text" class="form-control" id="formGroupExampleInput" name="offerEmail" value="<?php if(isset($_POST['row_id'])) {echo $_SESSION['user_data']['email'];} ?>" readonly="readonly" ><br />
                                <label for="formGroupExampleInput">Menge:</label>
                                <input type="text" class="form-control" id="formGroupExampleInput" name="prodAmount" value="<?php if(isset($_POST['row_id'])) {echo $_POST['prodAmount'];} ?>" readonly="readonly" ><br />
                                <label for="formGroupExampleInput">Preis:</label>
                                <input type="text" class="form-control" id="formGroupExampleInput" name="offerPrice" placeholder="Gib den Preis an." /><br />
                                <input class="btn btn-warning text-center" type="submit" name="submitAngebot" value="Angebot abgeben" />
                            </div>
                        </form>
                    </div>
            </div>
        </div>
</div>
