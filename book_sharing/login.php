<?php
include 'server.php';

$sql = 'SELECT * FROM informatii WHERE id_info=1';
$informatii = mysqli_query($con, $sql);
$informatie = mysqli_fetch_assoc($informatii);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $informatie["titlu_site"]; ?> - Panoul control</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kaushan+Script" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<header>
    <div class="container">
            <a href= "index.php">
                <div class="logo"><?php echo $informatie["titlu_site"]; ?>
                </div></a>

        <nav>
            <ul>
                <li class="active"><a href="index.php"><i class="fa fa-home" aria-hidden="true"></i>Acasa</a></li>
                <li><a href="carti.php"><i class="fa fa-book" aria-hidden="true"></i>Carti</a></li>logo
                <li><a href="panou.php" class="link-panou"><i class="fa fa-cog fa-spin fa-x fa-fw"></i><span class="sr-only">Loading...</span>Panoul de control</a></li>
                <li><a href="index_form.php"><i class="fa fa-sign-out" aria-hidden="true"></i>Sign-in/out</a></li>
                <li>
                    <a href="#" id="search"><i class="fa fa-search" aria-hidden="true"></i>Search</a>

                    <form action="carti.php" method="post" class="search">
                        <input type="text" name="nume_carte" placeholder="Cauta dupa titlul cartii"/>
                        <input type="submit" name="cautare" value="Cauta"/>
                    </form>
                </li>
            </ul>
        </nav>

        <a href="#" class="navToggle"><i class="fa fa-bars" aria-hidden="true"></i> Meniu</a>
    </div>
</header>


<div class="container continut panou">
    <section>
        <?php

        session_start();

        if ( isset( $_POST['autentificare'] ) ) {
            $email_utilizator = mysqli_real_escape_string( $con, $_POST['email_utilizator'] );
            $parola_utilizator = mysqli_real_escape_string( $con, $_POST['parola_utilizator'] );
            $sql = "SELECT id, password FROM accounts WHERE email='$email_utilizator'";
            $utilizatori_logati = $con->query($sql);
            if ( $utilizatori_logati->num_rows == 1 ) {
                $utilizator_logat = $utilizatori_logati->fetch_array();
                if ( password_verify( $parola_utilizator, $utilizator_logat['password'] ) ) {
                    $_SESSION['id'] = $utilizator_logat['id'];
                    header("Location: index.php");
                } else {
                    echo "<div class='mesaj eroare'>Parola introdusa e incorecta</div>";
                }
            } else {
                echo "Eroare.";
            }
        }

        ?>

        <form method="post" action="">

            <fieldset>
                <label>Username</label>
                <input type="text" name="email_utilizator" >
            </fieldset>

            <fieldset>
                <label>Password</label>
                <input type="password" name="parola_utilizator">
            </fieldset>

            <fieldset>
                <button type="submit" class="btn" name="autentificare">Login</button>
            </fieldset>
        </form>
    </section>
</div>


</body>
</html>

