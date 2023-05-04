<?php
    include './includes/db.php';

    $err = "";
    session_start();

    $db = new mysqli($servername, $username, $password, $dbname);
    if ($db->connect_error) { 
        die("Povezivanje sa bazom podataka nije moguće.");
    }

    if (isset($_SESSION["korisnik"])) {
        $lckime = strtolower($_SESSION["korisnik"]);
        $result = $db->query("SELECT * FROM korisnici WHERE LOWER(ime) = '$lckime'");
        if(!($result->num_rows > 0)) {
            session_destroy();
            header("location: /");
            die();
        }
    }

    if (isset($_POST["komentar"]) && isset($_POST["font"]) && isset($_POST["color"]) && isset($_SESSION["korisnik"])) {
        $ime = $_SESSION["korisnik"];
        $poruka = mysqli_real_escape_string($db, stripslashes($_POST["komentar"]));
        $datum = date("d.m.Y.");
        $font = mysqli_real_escape_string($db, stripslashes($_POST["font"]));
        $boja = mysqli_real_escape_string($db, stripslashes($_POST["color"]));

        if(!empty($poruka) && !empty($font) && !empty($boja)) {
            if (!$db->query("INSERT INTO poruke (ime, poruka, datum, font, boja) VALUES ('$ime', '$poruka', '$datum', '$font', '$boja')")) {
                $err = "Greška pri slanju poruke!";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="hr">

    <head>
        <meta charset="utf-8">
        <title>JavaScript Ajax Aplikacija</title>
        <link rel="stylesheet" href="stil.css">
        <script src="app.js" defer></script>
    </head>

    <body>

        <header>
            <h1><a href="/">Grafitti Wall</a></h1>
        </header>

        <main>

<?php
    $result = $db->query("SELECT * FROM poruke");
    
    if ($result->num_rows > 0) {
?>
            <table id="poruke">
                <tr>
                    <th>Datum</th>
                    <th>Korisnik</th>
                    <th>Poruka</th>
                </tr>
<?php
        while($row = $result->fetch_assoc()) {
?>
                <tr>
                    <td><?php echo $row["datum"]; ?></td>
                    <td><?php echo htmlspecialchars($row["ime"]); ?></td>
                    <td style="color: <?php echo $row["boja"]; ?>; font-family: <?php echo $row["font"]; ?>;"><?php echo htmlspecialchars($row["poruka"]); ?></td>
                </tr>
<?php
        }
?>
            </table>
<?php
    } else {
        echo "Još nema poruka!";
    }

    $db->close();
?>

            <form id="prijava" <?php if(isset($_SESSION["korisnik"])){ echo 'style="display: none;"'; } ?> >
                <h2>Prijava</h2>
                <p>Niste prijavljeni u aplikaciju, ne možete pisati poruke.</p>
                <div>
                    <label>Korisničko ime:</label>
                    <input type="text" name="kime" />
                </div>
                <div>
                    <label>Lozinka:</label>
                    <input type="password" name="pass" />
                </div>
                <p class="response" id="prijavaresponse"></p>
                <div>
                    <button type="submit">Prijava</button>
                </div>
                <p>Ako još niste registrirani, možete se <a href="registracija.php">registrirati ovdje</a>. </p>
            </form>

            <form id="odjava" <?php if(!isset($_SESSION["korisnik"])){ echo 'style="display: none;"'; } ?> >
                <h2>Prijavljeni ste kao <span id="korisnik"><?php if(isset($_SESSION["korisnik"])){ echo htmlspecialchars($_SESSION["korisnik"]); } ?></span>.</h2>
                <p>Možete se odjaviti <a id="odjavabutt" href>ovdje</a>, a možete obrisati račun <a href="brisanje.php">ovdje</a>.</p>
            </form>

            <form id="komentiranje" method="post" <?php if(!isset($_SESSION["korisnik"])){ echo 'style="display: none;"'; } ?> >
                <h2>Napišite nešto:</h2>
                <div>
                    <label>Komentar:</label>
                    <input type="text" name="komentar" placeholder="Napiši komentar" />
                </div>
                <div>
                    <label>Font:</label>
                    <select name="font">
                        <option value="Arial">Arial</option>
                        <option value="Courier New">Courier New</option>
                        <option value="Times New Roman">Times New Roman</option>
                    </select>
                </div>
                <div>
                    <label>Boja:</label>
                    <input type="color" name="color" value="#ff0000" />
                </div>
                <div>
                    <button type="submit">Zapiši komentar</button>
                </div>
                <p class="response" id="komentarresponse"><?php echo $err; ?></p>
            </form>

        </main>

        <footer>
            <p>Datoteka za vježbu iz predmeta Skriptni jezici i web programiranje</p>
            <p>&copy; Tehnička škola Požega</p>
        </footer>

    </body>

</html>