<?php
    include './includes/db.php';

    if (isset($_POST["kime"]) && isset($_POST["pass"])) {
        $db = new mysqli($servername, $username, $password, $dbname);
        if ($db->connect_error) { 
            die();
        } else {
            $kime = strtolower(mysqli_real_escape_string($db, stripslashes($_POST["kime"])));
            $pass = $_POST["pass"];
            
            $result = $db->query("SELECT * FROM korisnici WHERE LOWER(ime) = '$kime'");

            if( $result->num_rows > 0 ) {
                $korisnik = $result->fetch_assoc();
                if (password_verify($pass, $korisnik["lozinka"])) {
                    session_start();
                    $_SESSION["korisnik"] = $korisnik["ime"];
                    die($korisnik["ime"]);
                } else {
                    die();
                }
            } else {
                die();
            }
        }
    } else {
        die();
    }
?>