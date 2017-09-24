<?php if(!isset($_SESSION['is_logged_in'])) {
    die('Sie müssen Registriert und eingeloggt sein um diese Plattform nutzen zu können. </a>');
} ?>


<?php
require 'classes/DB.php';

$database = new DB;

$ok = false;
if (isset($_POST['submit'])) {
    $ok = true;
    if (!isset($_POST["prodDesc"]) ||
        !is_string($_POST["prodDesc"]) ||
        trim($_POST["prodDesc"]) == "") {
        $ok = false;
        echo '<div class="alert alert-danger">Bitte tragen Sie eine Beschreibung ein!</div>';
    }

    if (!isset($_POST["prodCat"]) ||
        !is_string($_POST["prodCat"]) ||
        trim($_POST["prodCat"]) == "0" ){
        $ok = false;
        echo '<div class="alert alert-danger">Bitte wählen Sie eine Kategorie aus!</div>';
    }

    if (!isset($_POST["prodAmount"]) ||
        !preg_match('/^[0-9]*$/', $_POST["prodAmount"]) ||
        $_POST["prodAmount"] == "" ){
        $ok = false;
        echo '<div class="alert alert-danger">Bitte geben Sie die gewünschte Menge an!</div>';
    }

    if ($ok) {
        echo '<div class="alert alert-success">Deine Nachfrage wurde erfasst!</div>';
    }
}



$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

if ($ok) {
    $prodDesc = $post['prodDesc'];
    $prodCat = $post['prodCat'];
    $prodAmount = $post['prodAmount'];
    $prodDelDate = $post['prodDelDate'];
    $prodQual = $post['prodQual'];
    $prodEmail = $post['prodEmail'];

    $database->query('INSERT INTO product (prodDesc, prodCat, prodAmount, prodDelDate, prodQual, prodEmail) VALUES(:prodDesc, :prodCat, :prodAmount, :prodDelDate, :prodQual, :prodEmail)');

    $database->bind(':prodDesc', $prodDesc);
    $database->bind(':prodCat', $prodCat);
    $database->bind(':prodAmount', $prodAmount);
    $database->bind(':prodDelDate', $prodDelDate);
    $database->bind(':prodQual', $prodQual);
    $database->bind(':prodEmail', $prodEmail);

    $database->execute();

    if($database->lastInsertId()){
        echo '<p>Nachfrage Erfasst!</p>';
    }
}

$database->query('SELECT * FROM product order by prodCreateDate DESC');

$rows = $database->resultset();

?>



                            <!--Stellt eine Eingabemaske zur verfügung zum Erfassen von neuen Artikeln-->

                        <div class="row" >
                            <div class="col-md-4" >
                            <div class="panel panel-primary" >
                            <div class="panel-heading" >
                                <h3 class="panel-title" > Neue Nachfrage erfassen </h3 >
                            </div >
                            <div class="panel-body" >
                                <form method = "post" action = "<?php $_SERVER['PHP_SELF']; ?>" >
                                    <div class="form-group" >
                                        <label for="formGroupExampleInput" > Artikel Bezeichnung </label ><br />
                                        <input type = "text" class="form-control" id = "formGroupExampleInput" name = "prodDesc" placeholder = "Gib eine Bezeichnung ein" /><br />
                                        <label for="formGroupExampleInput" > Artikel Kategorie </label ><br />
                                        <select class="form-control" id = "inlineFormCustomSelect" name = "prodCat" >
                                            <option value = "0" selected > Bitte auswählen </option >
                                            <option value = "Innensechskant- und Innensechsrund-Schrauben" > Innensechskant - und Innensechsrund - Schrauben </option >
                                            <option value = "Sechskantschrauben" > Sechskantschrauben</option >
                                            <option value = "Muttern, Gewindeeinsätze" > Muttern, Gewindeeinsätze </option >
                                            <option value = "Grobschrauben, Dübel" > Grobschrauben, Dübel </option >
                                            <option value = "Schlitz-, Kreuzschlitz-, Kombischrauben" > Schlitz -, Kreuzschlitz -, Kombischrauben </option >
                                            <option value = "Gewindestangen, Gewindestifte, Stiftschrauben" > Gewindestangen, Gewindestifte, Stiftschrauben </option >
                                            <option value = "Scheiben, Sicherungselemente" > Scheiben, Sicherungselemente </option >
                                            <option value = "Blechschrauben, Bohrschrauben, gewindeformende Schrauben" > Blechschrauben, Bohrschrauben, gewindeformende Schrauben </option >
                                            <option value = "Holzschrauben" > Holzschrauben</option >
                                            <option value = "Stifte, Keile, Niete" > Stifte, Keile, Niete </option >
                                        </select ><br />

                                        <label for="formGroupExampleInput" > Gewünschte Menge </label ><br />
                                        <input type = "text" class="form-control" id = "formGroupExampleInput" name = "prodAmount" placeholder = "Gib die gewünschte Menge ein" /><br />

                                        <label for="formGroupExampleInput" > Gewünschter Lieferzeitpunkt </label ><br />
                                        <input type = "date" class="form-control" id = "formGroupExampleInput" name = "prodDelDate" placeholder = "Gib den gewünschten Lieferzeitpunkt an" /><br />

                                        <label for="formGroupExampleInput" > Qualität</label ><br />
                                        <select class="form-control" id = "inlineFormCustomSelect" name = "prodQual" >
                                            <option selected > Bitte Qualität auswählen(Q1 = höchste Qualität)</option >
                                            <option value = "Q1" > Q1</option >
                                            <option value = "Q2" > Q2</option >
                                            <option value = "Q3" > Q3</option >
                                            <option value = "Q4" > Q4</option >
                                        </select ><br />

                                        <label for="formGroupExampleInput" > Email</label ><br />
                                        <input type = "text" class="form-control" id = "formGroupExampleInput" name = "prodEmail" value = "<?php echo $_SESSION['user_data']['email'] ?>" readonly = "readonly" ><br />

                                        <input class="btn btn-primary text-center" type = "submit" name = "submit" value = "Bestätigen" />
                                    </div >
                                </form >
                            </div >
                        </div >
                        </div >




<!--Zeigt alle Artikel die in der Produkttabelle existieren-->

    <div class="col-md-8">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Übersicht aller nachgefragten Artikel</h3>
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