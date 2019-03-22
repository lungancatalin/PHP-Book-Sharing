<?php
include 'server.php';

$sql = 'SELECT * FROM informatii WHERE id_info=1';
$informatii = mysqli_query($con, $sql);
$informatie = mysqli_fetch_assoc($informatii);

$sql = 'SELECT * FROM categorii';
$categorii = mysqli_query($con, $sql);

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

        <a href="#" class="navToggle"><i class="fa fa-bars" aria-hidden="true"></i> Meniu</a>
    </div>
</header>
<body>
<div class="container continut carte-pagina">
    <section>
        <?php if ( isset( $_SESSION['id'] ) && $utilizator_logat['status'] == 'Admin' ) { ?>
        <article>
            <form action="panou.php#rezervari" method="post" class="rezerva_carte">
                <fieldset>
                    <label for="nume">Nume: </label>

                    <input type="text" name="nume"">
                </fieldset>

                <fieldset>
                    <label for="telefon">Telefon: </label>

                    <input type="text" name="telefon"">
                </fieldset>

                <fieldset>
                    <label for="mail">Adresa de email: </label>

                    <input type="text" name="mail"">
                </fieldset>

                <fieldset>
                    <label for="carte">Carte: </label>

                    <select name="carte" class="carte">
                        <?php
                            $sql = 'SELECT * FROM carti WHERE stoc > 0';
                            $carti = mysqli_query($con, $sql);
                            while($carte=mysqli_fetch_assoc($carti)) {
                                echo '<option value="' . $carte['id_produs'] . '" data-nr-carti="' . $carte['stoc'] . '">' . $carte['titlu_carte'] . '</option>';
                            }
                        ?>
                    </select>
                </fieldset>

                <fieldset>
                    <label for="nr_carti">Nr carti: </label>

                    <select name="nr_carti" class="nr_carti"></select>
                </fieldset>

                <fieldset>
                    <button type="submit" name="adauga-rezervare">Rezerva</button
                </fieldset>
            </form>
        </article>
        <?php } else { ?>
            <div class='mesaj succes'>Accesul permis doar administratorilor site-ului.</div>
        <?php } ?>
    </section>

    <aside>
        <div class="widget">
            <h3>Categorii</h3>

            <ul>
                <?php
                while($categorie=mysqli_fetch_assoc($categorii)) {
                    ?>
                    <li><a href="carti.php?categorie=<?php echo $categorie['id_categorie'] ?>"><?php echo $categorie['nume_categorie']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </aside>

    <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>

    <script type="text/javascript">
        $( document ).ready(function() {
            function afis_nr_carti(select) {
                $('.nr_carti').empty();

                var nr_carti = select.children('option:selected').data('nr-carti');

                for( var i = 1; i <= nr_carti; i++ ) {
                    $('.nr_carti').append('<option value="' + i + '">' + i + '</option>');
                }
            }

            afis_nr_carti($('.carte'));

            $('.rezerva_carte .carte').on('change', function() {
                afis_nr_carti($('.carte'));
            });
        });
    </script>

</div>
</body>
</html>