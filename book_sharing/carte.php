<?php
include 'server.php';

$sql = 'SELECT * FROM informatii WHERE id_info=1';
$informatii = mysqli_query($con, $sql);
$informatie = mysqli_fetch_assoc($informatii);

$sql = 'SELECT * FROM categorii';
$categorii = mysqli_query($con, $sql);

if( isset( $_GET['id'] ) ) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM carti WHERE id_produs = $id";
    $carti = mysqli_query($con, $sql);
    $carte = mysqli_fetch_assoc($carti);
}
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
                <li><a href="index.php"><i class="fa fa-home" aria-hidden="true"></i>Acasa</a></li>
                <li class="active"><a href="carti.php"><i class="fa fa-book" aria-hidden="true"></i>Carti</a></li>
                <?php if ( isset( $_SESSION['id'] ) && $utilizator_logat['status'] == 'Admin' ) { ?>
                    <li><a href="panou.php" class="link-panou"><i class="fa fa-cog fa-spin fa-x fa-fw"></i><span class="sr-only">Loading...</span>Panoul de control</a></li>
                <?php } ?>
                <?php if( !isset( $_SESSION['id'] ) ) { ?>
                    <li><a href="login.php"><i class="fa fa-sign-out" aria-hidden="true"></i>Sign-in</a></li>
                <?php } else { ?>
                    <li><a href="index.php?delogare=1"><i class="fa fa-sign-out" aria-hidden="true"></i>Sign out</a></li>
                <?php } ?>
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
<body>
    <div class="container continut carte-pagina">
        <section>
            <article>
                <?php
                    if (isset($_POST["rezerva-carte"])) {
                        $id_carte =  mysqli_real_escape_string($con, $_REQUEST['id_carte']);
                        $nr_rezervari =  mysqli_real_escape_string($con, $_REQUEST['nr_rezervari']);
                        $nume =  mysqli_real_escape_string($con, $_REQUEST['nume']);
                        $telefon =  mysqli_real_escape_string($con, $_REQUEST['telefon']);
                        $mail = mysqli_real_escape_string($con, $_REQUEST['mail']);

                        $sql = "INSERT INTO rezervari (id_produs, nume, telefon, nr_rezervari, mail) VALUES ('$id_carte', '$nume', '$telefon', '$nr_rezervari', '$mail')";
                        if(mysqli_query($con, $sql)) {
                            $stoc_nou = $carte['stoc'] - $nr_rezervari;
                            $sql = "UPDATE carti SET stoc='$stoc_nou' WHERE id_produs=$id_carte";
                            mysqli_query($con, $sql);

                            echo "<div class='mesaj succes'>Cartea a fost rezervata cu succes.</div>";
                        } else {
                            echo "<div class='mesaj eroare'>Cartea nu a putut fi rezervata.</div>";
                        }
                    }
                ?>

                <div class="carte-img" style="background-image: url(<?php echo 'img/carti/' . $carte["poza"] ?>)"></div>

                <div class="carte-info">
                    <h2><?php echo $carte["titlu_carte"] ?></h2>
                    <p>Autor: <?php echo $carte["autor_carte"] ?></p>
                    <?php
                    $id = $carte['id_categorie'];
                    $sql = "SELECT * FROM categorii WHERE categorii.id_categorie = $id ";
                    $categorii_carte = mysqli_query($con, $sql);
                    $categorie_carte = mysqli_fetch_assoc($categorii_carte);
                    ?>
                    <p>Categorie: <?php echo $categorie_carte["nume_categorie"] ?></p>
                    <p>Editura: <?php echo $carte["editura_carte"] ?></p>
                    <p>Nr pagini: <?php echo $carte["numar_pagini"] ?></p>
                    <p>Anul aparitiei: <?php echo $carte ["anul_aparitiei"] ?></p>
                    <p>Stoc disponibil: <?php echo $carte["stoc"] ?></p>
                    <h4>Descriere:</h4> <br><h6> <?php echo $carte["descriere"] ?></h6>

                    <?php
                        if( $carte['stoc'] > 0 ) {
                    ?>
                        <form action="rezerva-carte.php" method="post">
                            <input type="hidden" name="id_carte" value="<?php echo $carte['id_produs'] ?>">

                            <fieldset>
                                <select name="nr_rezervari">
                                    <?php
                                        for( $i = 1; $i <= $carte['stoc']; $i++ ) {
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                        }
                                    ?>
                                </select>
                            </fieldset>
                            <fieldset>
                                <button class="action-button animate blue" type="submit" name="rezerva">Rezerva</button>
                            </fieldset>
                        </form>
                    <?php } elseif ( $carte['stoc'] == 0 ) { ?>
                    <fieldset>
                        <button class="action-button animate red" name="rezerva">Stoc epuizat</button>
                    </fieldset>
                    <?php }?>
                </div>
            </article>
        </section>

        <aside>
            <div class="widget">
                <div class="widget_header"><h3>Categorii</h3></div>

                <ul>
                    <?php
                        while($categorie=mysqli_fetch_assoc($categorii)) {
                    ?>
                        <li><a href="carti.php?categorie=<?php echo $categorie['id_categorie'] ?>"><?php echo $categorie['nume_categorie']; ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </aside>
    </div>
    <footer>
        <div class="container">
            <div class="widget program">
                <h3>Program</h3>

                <ul>
                    <li><?php echo $informatie['zile_program']; ?></li>
                    <li><?php echo $informatie['ore_program']; ?></li>
                </ul>
            </div>

            <div class="widget program">
                <h3>Contact</h3>

                <ul>
                    <li><i class="fa fa-location-arrow" aria-hidden="true"></i> <?php echo $informatie['adresa']; ?></li>
                    <li><i class="fa fa-envelope" aria-hidden="true"></i> <?php echo $informatie['email']; ?></li>
                </ul>
            </div>
        </div>
        <div class="container subsol">
            <i class="fa fa-copyright" aria-hidden="true"></i><?php echo $informatie["text_subsol"]; ?>
        </div>

    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/meniu.js"></script>
</body>
</html>