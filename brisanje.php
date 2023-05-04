<?php
    include './includes/db.php';

    session_start();

    if(isset($_SESSION["korisnik"])) {
        $db = new mysqli($servername, $username, $password, $dbname);
        if ($db->connect_error) { 
            die("Povezivanje sa bazom podataka nije moguće.");
        }
        $lckime = strtolower($_SESSION["korisnik"]);
        $result = $db->query("SELECT * FROM korisnici WHERE LOWER(ime) = '$lckime'");
                        
        if( $result->num_rows > 0 ) {
            $db->query("DELETE FROM korisnici WHERE id=" . $result->fetch_assoc()["id"]);
        }
    }

    session_destroy();

    header("location: /");
?>