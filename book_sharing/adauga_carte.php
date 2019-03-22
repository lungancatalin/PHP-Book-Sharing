<?php
include 'server.php';

$sql = 'SELECT * FROM categorii';
$categorii = mysqli_query($con, $sql);

$sql = "SELECT * FROM informatii WHERE id_info=1";
$informatii = mysqli_query($con, $sql);
$informatie = mysqli_fetch_assoc($informatii);

session_start();

if ( isset( $_SESSION['id'] ) ) {
    $id_utilizator = $_SESSION['id'];
    $sql = "SELECT * FROM accounts WHERE id='$id_utilizator'";
    $utilizatori_logati = $con->query($sql);
    $utilizator_logat = $utilizatori_logati->fetch_array();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $informatie["titlu_site"]; ?> - Panoul de control </title>
    <meta name="vieport" content="width=device-width, initial-scale=1.0">

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
                <li><a href="carti.php"><i class="fa fa-book" aria-hidden="true"></i>Carti</a></li>
                <?php if ( isset( $_SESSION['id'] ) && $utilizator_logat['status'] == 'Admin' ) { ?>
                    <li class="active"><a href="panou.php" class="link-panou"><i class="fa fa-cog fa-spin fa-x fa-fw"></i><span class="sr-only">Loading...</span>Panoul de control</a></li>
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

        <a href="#" class="navToggle"><i class="fa fa-bars" aria-hidden="true"></i> Meniu </a>
    </div>
</header>

<div class="container continut panou">
    <section>
        <?php if ( isset( $_SESSION['id'] ) && $utilizator_logat['status'] == 'Admin' ) { ?>
            <article>
                <h2 class="titlu-site">Adauga o carte</h2>

                <form action="panou.php#carti" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <label for="poza">Poza: </label>

                        <input name="poza" type="file" id="poza" accept="image/*">
                    </fieldset>

                    <fieldset>
                        <label for="nume">Nume: </label>
                        <input type="text" name="nume">
                    </fieldset>

                    <fieldset>
                        <label for="categorie">Categorie: </label>
                        <select name="categorie" id="categorie">
                            <?php
                                if(mysqli_num_rows($categorii) >0){
                                    while($categorie = mysqli_fetch_assoc($categorii)) {
                                        echo '<option value="' . $categorie["id_categorie"] . '">' . $categorie["nume_categorie"] . '</option>';
                                    }
                                }
                            ?>
                        </select>
                    </fieldset>

                    <fieldset>
                        <label for="autor">Numele autorului: </label>
                        <input type="text" name="autor">
                    </fieldset>

                    <fieldset>
                        <label for="editura">Editura cartii: </label>
                        <input type="text" name="editura">
                    </fieldset>

                    <fieldset>
                        <label for="editura">Descriere: </label>
                        <textarea name="descriere"></textarea>
                    </fieldset>

                    <fieldset>
                        <label for="data">Anul aparitiei: </label>
                        <input type="date" name="data">
                    </fieldset>

                    <fieldset>
                        <label for="pagini">Numarul de pagini: </label>
                        <input type="text" name="pagini">
                    </fieldset>

                    <fieldset>
                        <label for="stoc">Disponibilitate/buc: </label>
                        <input type="text" name="stoc">
                    </fieldset>

                    <fieldset>
                        <button type="submit" name="adauga-carte">Adauga o carte</button>
                    </fieldset>
                </form>
            </article>

        <?php } else { ?>
            <div class='mesaj succes'>Accesul permis doar administratorilor site-ului.</div>
        <?php } ?>
    </section>

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





