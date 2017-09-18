<?php if(!isset($_SESSION['is_logged_in'])) {
    die('Sie müssen Registriert und eingeloggt sein um diese Plattform nutzen zu können. </a>');
} ?>

<?php
require 'classes/DB.php';

$database = new DB;

//Alle Artikel Anzeigen

$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
$database->query('SELECT product.prodID, product.prodDesc, product.prodCat, product.prodAmount, offer.offPrice, offer.offEmail, offer.offID FROM product LEFT JOIN offer ON product.prodID = offer.prodID where offer.offPrice IS NOT NULL');
$rows = $database->resultset();

//Suchfunktion
if (isset($_POST['search'])) {

    if ($_POST['search'] != '') {
        $search = $post['search'];
        $database->query("SELECT product.prodID, product.prodDesc, product.prodCat, product.prodAmount, offer.offPrice, offer.offEmail, offer.offID FROM product LEFT JOIN offer ON product.prodID = offer.prodID where offer.offPrice IS NOT NULL AND product.prodDesc LIKE '%{$search}%' OR prodCat LIKE '%{$search}%'");
        $database->execute();
        $rows = $database->resultset();
    } else {
        echo '<div class="alert alert-danger">Bitte geben Sie einen Suchbegriff ein!</div>';
    }
}

//XML
if(isset($post['submitAngebot'])) {

$xml = new DomDocument('1.0');
$xml->formatOutput = true;

$offID=$_POST['offID'];
$prodID=$_POST['prodID'];
$prodDesc=$_POST['prodDesc'];
$prodAmount=$_POST['prodAmount'];
$offPrice=$_POST['offPrice'];
$offEmail=$_POST['offEmail'];
$orderEmail=$_POST['orderEmail'];
$comment=$_POST['comment'];

$bsts = $xml->createElement("Bestellungen");
$xml->appendChild($bsts);

$bst=$xml->createElement("Bestellung");
$bsts->appendChild($bst);

$Angebot_ID=$xml->createElement("Angebot_ID", "$offID");
$bst->appendChild($Angebot_ID);

$Produkt_ID=$xml->createElement("Produkt_ID", "$prodID");
$bst->appendChild($Produkt_ID);

$Produkt_Bezeichnung=$xml->createElement("Produkt_Bezeichnung", "$prodDesc");
$bst->appendChild($Produkt_Bezeichnung);

$Produkt_Menge=$xml->createElement("Produkt_Menge", "$prodAmount");
$bst->appendChild($Produkt_Menge);

$Preis=$xml->createElement("Preis", "$offPrice");
$bst->appendChild($Preis);

$Anbieter_Email=$xml->createElement("Anbieter_Email", "$offEmail");
$bst->appendChild($Anbieter_Email);

$Käufer_Email=$xml->createElement("Besteller_Email", "$orderEmail");
$bst->appendChild($Käufer_Email);

$Com=$xml->createElement("Bemerkungen", "$comment");
$bst->appendChild($Com);

$xml->save("xml\BST_ProdID-$prodID-AngeID-$offID.xml")or die("error, nicht möglich das xml File zu erzeugen!");
}

?>


<!-- Stellt eine Eingabemaske zur verfügung zur Eingabe von String für Suche über gesamte Tabelle-->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">Angebote durchsuchen</h3>
            </div>
            <div class="panel-body">
                <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <input type="text" class="form-control" id="formGroupExampleInput" name="search" placeholder="..." /><br />
                        <input class="btn btn-success text-center" type="submit" name="submit" value="Suchen" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
        <!--Zeigt nur Artikel, welche auch in ein oder mehrere Angebote hinterlegt haben. -->
        <div class="col-md-8">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Übersicht alle Artikel mit Angebot</h3>
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
                            <?php foreach($rows as $row) : ?>
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

        <div class="col-md-4">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Bestellung erfassen zu Angebot Nr: <?php if(isset($_POST['offID'])) {echo $_POST['offID'];} ?></h3>
                </div>
                    <div class="panel-body">
                        <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
                            <div class="form-group">
                                <label for="formGroupExampleInput">Produktbeschreibung:</label>
                                <input type="text" class="form-control" id="formGroupExampleInput" name="prodDesc" value="<?php if(isset($_POST['offID'])) {echo $_POST['prodDesc'];} ?>"readonly="readonly"><br />
                                <label for="formGroupExampleInput">Angebot ID:</label>
                                <input type="text" class="form-control" id="formGroupExampleInput" name="offID" value="<?php if(isset($_POST['offID'])) {echo $_POST['offID'];} ?>"readonly="readonly"><br />
                                <label for="formGroupExampleInput">Produkt ID:</label>
                                <input type="text" class="form-control" id="formGroupExampleInput" name="prodID" value="<?php if(isset($_POST['offID'])) {echo $_POST['prodID'];} ?>"readonly="readonly"><br />
                                <label for="formGroupExampleInput">Menge:</label>
                                <input type="text" class="form-control" id="formGroupExampleInput" name="prodAmount" value="<?php if(isset($_POST['offID'])) {echo $_POST['prodAmount'];} ?>"readonly="readonly"><br />
                                <label for="formGroupExampleInput">Preis:</label>
                                <input type="text" class="form-control" id="formGroupExampleInput" name="offPrice" value="<?php if(isset($_POST['offID'])) {echo $_POST['offPrice'];} ?>"readonly="readonly"><br />
                                <label for="formGroupExampleInput">Email:</label>
                                <input type="text" class="form-control" id="formGroupExampleInput" name="orderEmail" value="<?php if(isset($_POST['offID'])) {echo $_SESSION['user_data']['email'];} ?>"readonly="readonly"><br />
                                <label for="formGroupExampleInput">Bemerkungen zur Bestellung:</label>
                                <textarea type="text" class="form-control" id="formGroupExampleInput" name="comment"  placeholder="..." ></textarea><br />
                                <input class="btn btn-primary text-center" type="hidden" name="offEmail" value="<?php if(isset($_POST['offID'])) {echo $_POST['offEmail'];} ?>">
                                <input class="btn btn-success text-center" type="submit" name="submitAngebot" value="Bestellung abschicken!" />
                            </div>
                        </form>
                    </div>
            </div>
        </div>
</div>

