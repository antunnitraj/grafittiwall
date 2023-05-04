<?php
    include './includes/db.php';

    $err = "";
    if (isset($_POST["kime"]) && isset($_POST["pass"]) && isset($_POST["email"])) {
        if (!empty($_POST["kime"]) && !empty($_POST["pass"]) && !empty($_POST["email"])) {
            if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $err = "Vaša e-mail adresa je neispravna!";
            } else {
                $db = new mysqli($servername, $username, $password, $dbname);
                if ($db->connect_error) { 
                    $err = "Povezivanje sa bazom podataka nije moguće!";
                } else {
                    $kime = mysqli_real_escape_string($db, stripslashes($_POST["kime"]));
                    $lckime = strtolower($kime);
                    $result = $db->query("SELECT * FROM korisnici WHERE LOWER(ime) = '$lckime'");
                    
                    if( $result->num_rows > 0 ) {
                        $ime = $result->fetch_assoc()["ime"];
                        $err = "Korisnik '$ime' već postoji!";
                    } else {
                        $email = mysqli_real_escape_string($db, stripslashes($_POST["email"]));
                        $pass = password_hash($_POST["pass"], PASSWORD_DEFAULT);

                        if ($db->query("INSERT INTO korisnici (ime, lozinka, email) VALUES ('$kime', '$pass', '$email')")) {
                            session_start();
                            $_SESSION["korisnik"] = $kime;
                            header("location: /");
                        } else {
                            $err = "Greška pri registraciji!";
                        }
                    }   
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="hr">

    <head>
        <meta charset="utf-8">
        <title>Grafitti Wall</title>
        <link rel="stylesheet" href="stil.css">
    </head>

    <body>

        <header>
            <h1><a href="/">Grafitti Wall</a></h1>
        </header>

        <main>

            <form method="post" id="registracija">
                <h2>Registracija</h2>
                <p>Registrirajte se za korištenje aplikacije.</p>
                <div>
                    <label>Korisničko ime:</label>
                    <input type="text" name="kime" />
                </div>
                <div>
                    <label>Lozinka:</label>
                    <input type="password" name="pass" />
                </div>
                <div>
                    <label>E-mail adresa:</label>
                    <input type="email" name="email" />
                </div>
                <p class="response"><?php echo $err; ?></p>
                <div>
                    <button type="submit">Registracija</button>
                </div>
            </form>

        </main>

        <footer>
            <p>Datoteka za vježbu iz predmeta Skriptni jezici i web programiranje</p>
            <p>&copy; Tehnička škola Požega</p>
        </footer>

    </body>

</html>