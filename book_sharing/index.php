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

    if ( isset( $_GET["delogare"] ) ) {
        session_destroy();
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo $informatie["titlu_site"]; ?> -Acasa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kaushan+Script" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

    <header class="acasa">
        <div class="container">
            <a href= "index.php">
            <div class="logo"><?php echo $informatie["titlu_site"]; ?>
            </div></a>

            <nav>
                <ul>
                    <li class="active"><a href="index.php"><i class="fa fa-home" aria-hidden="true"></i>Acasa</a></li>
                    <li><a href="carti.php"><i class="fa fa-book" aria-hidden="true"></i>Carti</a></li>
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

            <a href="#" class="navToggle"><i class="fa fa-bars" aria-hidden="true"></i> Meniu </a>
        </div>
    </header>

    <div class="cover">
        <div class="container">
            <h1><?php echo $informatie['descriere_site']; ?></h1>
        </div>
    </div>

    <div class="testimonials">
        <h2>Testimoniale</h2>

        <ul class="testimonials-lista">
            <?php
            include "testimonial.php"
            ?>
            <div class="clearfix"></div>
        </ul>
    </div>
    <div class="row">
        <div class="column">
            <img src="http://be.beantownthemes.com/html/content/library/images/home_library_img1.jpg" style="width:100%">
        </div>
        <div class="column">
            <img src="http://be.beantownthemes.com/html/content/library/images/home_library_img2.jpg" style="width:100%">
        </div>
    </div>

        <div class="header-sectiune"><h3>Ultimele cărți apărute</h3></div>

     <div class="container ultimele-carti">
        <?php
            $sql="SELECT * FROM carti ORDER BY id_produs DESC LIMIT 4" ;
            $carti=mysqli_query($con, $sql);

            if (mysqli_num_rows($carti) > 0) {
                $numar_carti=0;
            while($carte=mysqli_fetch_assoc($carti)){
                $numar_carti++;

                $clasa = '';
                if ($numar_carti==3 || $numar_carti==4) $clasa='alternativ';

                echo '<div class="ultimele-carti-item ' . $clasa . '">';
                    echo '<div class="ultimele-carti-descriere">';
                    echo '<a href="http://localhost/book_sharing/carte.php?id=' .  $carte['id_produs'] . '"><h2>' . $carte["titlu_carte"] . '</h2></a>';
                    echo '<p class="detalii">Autor: ' . $carte["autor_carte"] . '</p>';
                    echo '<p class="detalii">Editura: ' . $carte["editura_carte"] . '</p>';

                    $sql="SELECT * FROM categorii";
                    $categorii=mysqli_query($con, $sql);
                    $categorie=mysqli_fetch_assoc($categorii);

                    if( !$categorie) {
                        echo "Nici o categorie.";
                    }else{
                        do{
                            if ($carte["id_categorie"] == $categorie["id_categorie"]) {

                                echo '<p class="detalii">Categorie: ' . $categorie["nume_categorie"] . '</p>';
                            }
                        }while ($categorie=mysqli_fetch_assoc($categorii));
                    }
                if( $carte['stoc'] > 0 ) {
                    echo '<p class="detalii">Cartea este in stoc</p>';
                } else
                    echo '<p class="detalii">Stoc epuizat</p>';

                echo '</div>';
                        echo '<div class="ultimele-carti-img" style="background-image: url(img/carti/' . $carte["poza"] . ')"></div>';
                        echo '</div>';
                }
            }
        ?>
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




