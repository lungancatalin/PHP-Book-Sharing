<?php
include 'server.php';

$sql = 'SELECT * FROM informatii WHERE id_info=1';
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

            <a href="#" class="navToggle"><i class="fa fa-bars" aria-hidden="true"></i>Meniu</a>
        </div>
</header>

<div class="container continut panou">
<section>
<?php if ( isset( $_SESSION['id'] ) && $utilizator_logat['status'] == 'Admin' ) { ?>
    <article>
        <h2 class="titlu-site">Informatii generale</h2>

        <?php
        if (isset($_POST["salveaza-informatii-generale"])) {
            $titlu_site = mysqli_real_escape_string($con, $_REQUEST['titlu']);
            $descriere_site = mysqli_real_escape_string($con, $_REQUEST['descriere']);
            $zile_program = mysqli_real_escape_string($con, $_REQUEST['zile']);
            $ore_program = mysqli_real_escape_string($con, $_REQUEST['ore']);
            $adresa = mysqli_real_escape_string($con, $_REQUEST['adresa']);
            $email = mysqli_real_escape_string($con, $_REQUEST['email']);
            $text_subsol = mysqli_real_escape_string($con, $_REQUEST['text_subsol']);

            $sql = "UPDATE informatii SET titlu_site='$titlu_site', descriere_site='$descriere_site', zile_program='$zile_program', ore_program='$ore_program', adresa='$adresa', email='$email', text_subsol='$text_subsol' WHERE id_info=1";

            if (mysqli_query($con, $sql)) {
                echo "<div class='mesaj succes'>Datele au fost salvate cu succes.</div>";
            } else {
                echo "<div class='mesaj eroare'>Datele nu au putut fi salvate.</div>";
            }
        }

        $sql = 'SELECT * FROM informatii WHERE id_info=1';
        $informatii = mysqli_query($con, $sql);
        $informatie = mysqli_fetch_assoc($informatii);
        ?>

        <form method="POST">
            <fieldset>
                <label for="descriere">Titlul site-ului: </label>

                <input type="text" name="titlu" id="titlu" value="<?php echo $informatie['titlu_site']; ?>">
            </fieldset>

            <fieldset>
                <label for="descriere">Descriere site: </label>

                <textarea name="descriere" id="descriere"><?php echo $informatie['descriere_site']; ?></textarea>
            </fieldset>

            <fieldset>
                <label for="zile">Zile:</label>

                <input type="text" name="zile" value="<?php echo $informatie['zile_program']; ?>">
            </fieldset>

            <fieldset>
                <label for="ore">Ore:</label>

                <input type="text" name="ore" value="<?php echo $informatie['ore_program']; ?>">
            </fieldset>

            <fieldset>
                <label for="ore">Adresa:</label>

                <input type="text" name="adresa" value="<?php echo $informatie['adresa']; ?>">
            </fieldset>

            <fieldset>
                <label for="email">Email:</label>

                <input type="email" name="email" value="<?php echo $informatie['email']; ?>">
            </fieldset>

            <fieldset>
                <label for="email">Text subsol:</label>

                <input type="text" name="text_subsol" value="<?php echo $informatie['text_subsol']; ?>">
            </fieldset>

            <fieldset>
                <button type="submit" name="salveaza-informatii-generale">Salveaza</button>
            </fieldset>
        </form>
    </article>

    <article id="categorii">
        <h2 class="titlu-site">Categorii de carti</h2>

        <?php
        if (isset($_POST["adauga-categorie"])) {
            $nume_categorie = mysqli_real_escape_string($con, $_REQUEST['nume']);

            $sql = "INSERT INTO categorii (nume_categorie) VALUES ('$nume_categorie')";

            if (mysqli_query($con, $sql)) {
                echo "<div class='mesaj succes'>Categoria a fost adaugata cu succes.</div>";
            } else {
                echo "<div class='mesaj eroare'>Categoria nu a putut fi adaugata.</div>";
            }
        }

        if (isset($_POST["modifica-categorie"])) {
            $id = mysqli_real_escape_string($con, $_REQUEST['id']);
            $nume_categorie = mysqli_real_escape_string($con, $_REQUEST['nume']);

            $sql = "UPDATE categorii SET nume_categorie='$nume_categorie' WHERE id_categorie=$id";

            if (mysqli_query($con, $sql)) {
                echo "<div class='mesaj succes'>Categoria a fost modificata cu succes.</div>";
            } else {
                echo "<div class='mesaj eroare'>Categoria nu a putut fi modificata.</div>";
            }
        }

        if (isset($_GET["sterge-categorie"])) {
            $id = $_GET['sterge-categorie'];
            $sql = "DELETE FROM categorii WHERE id_categorie=$id";

            if (mysqli_query($con, $sql)) {
                echo "<div class='mesaj succes'>Categoria a fost stearsa cu succes.</div>";
            } else {
                echo "<div class='mesaj eroare'>Categoria nu a putut fi stearsa.</div>";
            }
        }
        ?>

        <ul class="lista">
            <li>
                <div class="coloana">Nr.</div>

                <div class="coloana">Nume categorie</div>

                <div class="clearfix"></div>
            </li>

            <?php
            $sql = 'SELECT * FROM categorii';
            $categorii = mysqli_query($con, $sql);

            if(mysqli_num_rows($categorii) > 0) {
                $nr_categorie=0;

                while($categorie=mysqli_fetch_assoc($categorii)) {
                    $nr_categorie++;

                    echo '<li>';
                    echo '<div class="coloana">' . $nr_categorie . '</div>';
                    echo '<div class="coloana">' . $categorie['nume_categorie'] . '</div>';
                    echo '<div class="coloana"><a href="modifica_categorie.php?id=' . $categorie['id_categorie'] . '">Modifica</a></div>';
                    echo '<div class="coloana"><a href="?sterge-categorie=' . $categorie['id_categorie'] . '#categorii">Sterge</a></div>';
                    echo '</li>';
                }
            }
            ?>
        </ul>

        <a class="adauga" href="adauga_categorie.php">Adauga</a>
    </article>

    <article id="carti">
        <h2 class="titlu-site">Carti</h2>

        <?php
        if (isset($_POST['adauga-carte'])) {
            $filename = $_FILES["poza"]["name"];
            $filesize = $_FILES["poza"]["size"];
            $titlu_carte = mysqli_real_escape_string($con, $_REQUEST['nume']);
            $categorie = mysqli_real_escape_string($con, $_REQUEST['categorie']);
            $autor = mysqli_real_escape_string($con, $_REQUEST['autor']);
            $editura = mysqli_real_escape_string($con, $_REQUEST['editura']);
            $descriere = mysqli_real_escape_string($con, $_REQUEST['descriere']);
            $data = mysqli_real_escape_string($con, $_REQUEST['data']);
            $pagini = mysqli_real_escape_string($con, $_REQUEST['pagini']);
            $stoc = mysqli_real_escape_string($con, $_REQUEST['stoc']);

            $destFile = $dirImagini . 'carti/' . basename($filename);
            $erori = [];
            $cale = pathinfo($filename);
            $extensie = $cale["extension"];

            if($extensie != "jpg" && $extensie != "jpeg" && $extensie !="png") {
                $erori[] = "Fisierul nu e suportat. <br>";
            }
            if($filesize > 1000000) {
                $erori[] = "Fisierul e prea mare. <br>";
            }

            if(empty($erori)) {
                $succes = move_uploaded_file($_FILES["poza"]["tmp_name"], $destFile);
                var_dump($filename);
                $sql = "INSERT INTO carti (titlu_carte, poza, id_categorie, autor_carte, editura_carte, descriere, anul_aparitiei, numar_pagini, stoc) VALUES ('$titlu_carte', '$filename', '$categorie', '$autor', '$editura', '$descriere', '$data', '$pagini', '$stoc')";

                if($succes && mysqli_query($con, $sql)) {
                    echo "<div class='mesaj succes'>Cartea a fost adaugata cu succes.</div>";
                } else {
                    echo "<div class='mesaj eroare'>Cartea nu a putut fi adaugata.</div>";
                }
            } else {
                foreach ($erori as $eroare) {
                    echo "<div class='mesaj eroare'>" . $eroare . "</div>";
                }
            }
        }

        if (isset($_POST["modifica-carte"])) {
            $id = mysqli_real_escape_string($con, $_REQUEST['id']);
            $filename = $_FILES["poza"]["name"];
            $filesize = $_FILES["poza"]["size"];
            $titlu_carte = mysqli_real_escape_string($con, $_REQUEST['nume']);
            $categorie = mysqli_real_escape_string($con, $_REQUEST['categorie']);
            $autor = mysqli_real_escape_string($con, $_REQUEST['autor']);
            $editura = mysqli_real_escape_string($con, $_REQUEST['editura']);
            $descriere = mysqli_real_escape_string($con, $_REQUEST['descriere']);
            $data = mysqli_real_escape_string($con, $_REQUEST['data']);
            $pagini = mysqli_real_escape_string($con, $_REQUEST['pagini']);
            $stoc = mysqli_real_escape_string($con, $_REQUEST['stoc']);

            if(!empty($filename)) {
                $destFile = $dirImagini . 'carti/' . basename($filename);
                $erori = [];
                $cale = pathinfo($filename);
                $extensie = $cale["extension"];

                if($extensie != "jpg" && $extensie != "jpeg" && $extensie !="png") {
                    $erori[] = "Fisierul nu e suportat. <br>";
                }
                if($filesize > 1000000) {
                    $erori[] = "Fisierul e prea mare. <br>";
                }

                if(empty($erori)) {
                    $succes = move_uploaded_file($_FILES["poza"]["tmp_name"], $destFile);
                    $sql = "UPDATE carti SET titlu_carte='$titlu_carte', poza='$filename', id_categorie='$categorie', autor_carte='$autor', editura_carte='$editura', descriere='$descriere', anul_aparitiei='$data', numar_pagini='$pagini', stoc='$stoc' WHERE id_produs=$id";

                    if($succes && mysqli_query($con, $sql)) {
                        echo "<div class='mesaj succes'>Cartea a fost modificata cu succes.</div>";
                    } else {
                        echo "<div class='mesaj eroare'>Cartea nu a putut fi modificata.</div>";
                    }
                } else {
                    foreach ($erori as $eroare) {
                        echo "<div class='mesaj eroare'>" . $eroare . "</div>";
                    }
                }
            } else {
                $sql = "UPDATE carti SET titlu_carte='$titlu_carte', id_categorie='$categorie', autor_carte='$autor', editura_carte='$editura', descriere='$descriere', anul_aparitiei='$data', numar_pagini='$pagini', stoc='$stoc' WHERE id_produs=$id";

                if(mysqli_query($con, $sql)) {
                    echo "<div class='mesaj succes'>Cartea a fost modificata cu succes.</div>";
                } else {
                    echo "<div class='mesaj eroare'>Cartea nu a putut fi modificata.</div>";
                }
            }
        }

        if (isset($_GET["sterge-carte"])) {
            $id = $_GET['sterge-carte'];
            $sql = "DELETE FROM carti WHERE id_produs=$id";

            if (mysqli_query($con, $sql)) {

                echo "<div class='mesaj succes'>Cartea a fost stersa cu succes.</div>";
            } else {
                echo "<div class='mesaj eroare'>Cartea nu a putut fi stearsa.</div>";
            }
        }
        ?>

        <ul class="lista">
            <li>
                <div class="coloana">Nr.</div>

                <div class="coloana">Nume carte</div>

                <div class="clearfix"></div>
            </li>

            <?php
            $sql = 'SELECT * FROM carti';
            $carti = mysqli_query($con, $sql);

            if(mysqli_num_rows($carti) > 0) {

                $nr_carte=0;

                while($carte=mysqli_fetch_assoc($carti)) {
                    $nr_carte++;

                    echo '<li>';
                    echo '<div class="coloana">' . $nr_carte . '</div>';
                    echo '<div class="coloana">' . $carte['titlu_carte'] . '</div>';
                    echo '<div class="coloana"><a href="modifica_carte.php?id=' . $carte['id_produs'] . '">Modifica</a></div>';
                    echo '<div class="coloana"><a href="?sterge-carte=' . $carte['id_produs'] . '#carti">Sterge</a></div>';
                    echo '</li>';
                }
            }
            ?>

            <a class="adauga" href="adauga_carte.php">Adauga</a>
        </ul>
    </article>

    <article id="rezervari">
        <h2 class="titlu-site">Rezervari</h2>

        <?php
        if (isset($_POST['adauga-rezervare'])) {
            $nume = mysqli_real_escape_string($con, $_REQUEST['nume']);
            $telefon = mysqli_real_escape_string($con, $_REQUEST['telefon']);
            $id_carte = mysqli_real_escape_string($con, $_REQUEST['carte']);
            $nr_rezervari = mysqli_real_escape_string($con, $_REQUEST['nr_carti']);

            $sql = "INSERT INTO rezervari (id_produs, nume, telefon, nr_rezervari) VALUES ('$id_carte', '$nume', '$telefon', '$nr_rezervari')";

            if(mysqli_query($con, $sql)) {
                $sql = "SELECT * FROM carti WHERE id_produs=$id_carte";
                $carti = mysqli_query($con, $sql);
                $carte = mysqli_fetch_assoc($carti);

                $stoc_nou = $carte['stoc'] - $nr_rezervari;
                $sql = "UPDATE carti SET stoc='$stoc_nou' WHERE id_produs=$id_carte";
                mysqli_query($con, $sql);

                echo "<div class='mesaj succes'>Rezevarea a fost adaugata cu succes.</div>";
            } else {
                echo "<div class='mesaj eroare'>Rezervarea nu a putut fi adaugata.</div>";
            }
        }

        if (isset($_GET["sterge-rezervare"])) {
            $id = $_GET['sterge-rezervare'];
            $id_carte = $_GET['id-produs'];
            $nr_rezervari = $_GET['nr-rezervari'];
            $sql = "DELETE FROM rezervari WHERE id_rezervare=$id";

            if (mysqli_query($con, $sql)) {
                $sql = "SELECT * FROM carti WHERE id_produs=$id_carte";
                $carti = mysqli_query($con, $sql);
                $carte = mysqli_fetch_assoc($carti);

                $stoc_nou = $carte['stoc'] + $nr_rezervari;
                $sql = "UPDATE carti SET stoc='$stoc_nou' WHERE id_produs=$id_carte";
                mysqli_query($con, $sql);

                echo "<div class='mesaj succes'>Rezervarea a fost stearsa cu succes.</div>";
            } else {
                echo "<div class='mesaj eroare'>Rezervarea nu a putut fi stearsa.</div>";
            }
        }
        ?>

        <ul class="lista rezervari_tabel">
            <li>
                <div class="coloana">Nr.</div>

                <div class="coloana">Titlu carte</div>

                <div class="coloana">Cantitate</div>

                <div class="coloana">Nume client</div>

                <div class="coloana">Telefon</div>

                <div class="coloana">Mail</div>

                <div class="clearfix"></div>
            </li>

            <?php
            $sql = 'SELECT * FROM rezervari';
            $rezervari = mysqli_query($con, $sql);

            if(mysqli_num_rows($rezervari) > 0) {

                $nr_rezervare=0;

                while($rezervare=mysqli_fetch_assoc($rezervari)) {
                    $nr_rezervare++;
                    $id_produs = $rezervare['id_produs'];

                    $sql = "SELECT * FROM carti WHERE id_produs = $id_produs";
                    $carti = mysqli_query($con, $sql);
                    $carte = mysqli_fetch_assoc($carti);

                    echo '<li>';
                    echo '<div class="coloana">' . $nr_rezervare . '</div>';
                    echo '<div class="coloana">' . $carte['titlu_carte'] . '</div>';
                    echo '<div class="coloana">' . $rezervare['nr_rezervari'] . '</div>';
                    echo '<div class="coloana">' . $rezervare['nume'] . '</div>';
                    echo '<div class="coloana">' . $rezervare['telefon'] . '</div>';
                    echo '<div class="coloana">' . $rezervare['mail'] . '</div>';
                    echo '<div class="coloana"><a href="?sterge-rezervare=' . $rezervare['id_rezervare'] . '&id-produs=' . $rezervare['id_produs'] . '&nr-rezervari=' . $rezervare['nr_rezervari'] . '#rezervari">Sterge</a></div>';
                    echo '</li>';
                }
            }
            ?>

            <a class="adauga" href="adauga_rezervare.php">Adauga</a>
        </ul>
    </article>

    <article id="testimonials">
        <h2 class="titlu-site">Testimoniale</h2>

        <?php
        if (isset($_POST["adauga-testimonial"])) {
            $nume_testimonial = mysqli_real_escape_string($con, $_REQUEST['nume']);
            $autor_testimonial = mysqli_real_escape_string($con, $_REQUEST['autor']);

            $sql = "INSERT INTO testimonials (testimonial, autor_testimonial) VALUES ('$nume_testimonial', '$autor_testimonial')";

            if (mysqli_query($con, $sql)) {
                echo "<div class='mesaj succes'>Testimonialul a fost adaugat cu succes.</div>";
            } else {
                echo "<div class='mesaj eroare'>Testimonialul nu a putut fi adaugat.</div>";
            }
        }

        if (isset($_POST["modifica-testimonial"])) {
            $id = mysqli_real_escape_string($con, $_REQUEST['id']);
            $nume_testimonial = mysqli_real_escape_string($con, $_REQUEST['nume']);
            $autor_testimonial = mysqli_real_escape_string($con, $_REQUEST['autor']);

            $sql = "UPDATE testimonials SET testimonial='$nume_testimonial', autor_testimonial='$autor_testimonial' WHERE id=$id";

            if (mysqli_query($con, $sql)) {
                echo "<div class='mesaj succes'>Testimonialul a fost adaugat cu succes.</div>";
            } else {
                echo "<div class='mesaj eroare'>Testimonialul nu a putut fi adaugat.</div>";
            }
        }

        if (isset($_GET["sterge-testimonial"])) {
            $id = $_GET['sterge-testimonial'];
            $sql = "DELETE FROM testimonials WHERE id=$id";

            if (mysqli_query($con, $sql)) {
                echo "<div class='mesaj succes'>Testimonialul a fost adaugat cu succes.</div>";
            } else {
                echo "<div class='mesaj eroare'>Testimonialul nu a putut fi adaugat.</div>";
            }
        }
        ?>
        <ul class="lista">
            <li>
                <div class="coloana">Nr.</div>

                <div class="coloana">Testimonial</div>

                <div class="clearfix"></div>
            </li>

            <?php
            $sql = 'SELECT * FROM testimonials';
            $testimonials = mysqli_query($con, $sql);

            function getExcerpt($str, $startPos=0, $maxLength=50) {
                if(strlen($str) > $maxLength) {
                    $excerpt   = substr($str, $startPos, $maxLength-3);
                    $lastSpace = strrpos($excerpt, ' ');
                    $excerpt   = substr($excerpt, 0, $lastSpace);
                    $excerpt  .= '...';
                } else {
                    $excerpt = $str;
                }

                return $excerpt;
            }

            if(mysqli_num_rows($testimonials) > 0) {
                $nr_testimonial=0;

                while($testimonial=mysqli_fetch_assoc($testimonials)) {
                    $nr_testimonial++;

                    echo '<li>';
                    echo '<div class="coloana">' . $nr_testimonial . '</div>';
                    echo '<div class="coloana">' . getExcerpt($testimonial['testimonial']) . '</div>';
                    echo '<div class="coloana"><a href="modifica_testimonial.php?id=' . $testimonial['id'] . '">Modifica</a></div>';
                    echo '<div class="coloana"><a href="?sterge-testimonial=' . $testimonial['id'] . '#testimonials">Sterge</a></div>';
                    echo '</li>';
                }
            }
            ?>
        </ul>

        <a class="adauga" href="adauga_testimonial.php">Adauga</a>
    </article>

<?php } else { ?>
    <div class='mesaj succes'>Accesul permis doar administratorilor siteului.</div>
<?php } ?>


</section>

<!--    <aside>-->
<!--        <div class="widget program">-->
<!--            <h3>Program</h3>-->
<!---->
<!--            <ul>-->
<!--                <li>--><?php //echo $informatie['zile_program']; ?><!--</li>-->
<!--                <li>--><?php //echo $informatie['ore_program']; ?><!--</li>-->
<!--            </ul>-->
<!--        </div>-->
<!---->
<!--        <div class="widget program">-->
<!--            <h3>Contact</h3>-->
<!---->
<!--            <ul>-->
<!--                <li><i class="fa fa-location-arrow" aria-hidden="true"></i> --><?php //echo $informatie['adresa']; ?><!--</li>-->
<!--                <li><i class="fa fa-envelope" aria-hidden="true"></i> --><?php //echo $informatie['email']; ?><!--</li>-->
<!--            </ul>-->
<!--        </div>-->
<!--    </aside>-->
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