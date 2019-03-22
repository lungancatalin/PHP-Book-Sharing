<?php
include 'server.php';

$sql = 'SELECT * FROM informatii WHERE id_info=1';
$informatii = mysqli_query($con, $sql);
$informatie = mysqli_fetch_assoc($informatii);

$sql = 'SELECT * FROM categorii';
$categorii = mysqli_query($con, $sql);

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
                <li><a href="rezervari.php"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Rezervari</a></li>
                <li><a href="panou.php" class="link-panou"><i class="fa fa-cog fa-spin fa-x fa-fw"></i><span class="sr-only">Loading...</span>Panoul de control</a></li>
                <li><a href="index_form.php"><i class="fa fa-sign-out" aria-hidden="true"></i>Sign-in/out</a></li>
                <li><a href="search.php"><i class="fa fa-search" aria-hidden="true"></i>Search</a></li>
            </ul>
        </nav>

        <a href="#" class="navToggle"><i class="fa fa-bars" aria-hidden="true"></i> Meniu</a>
    </div>
</header>
<body>
    <div class="container continut carte-pagina">
        <section>
            <article>
                <form action="carte.php?id=<?php echo $_REQUEST['id_carte'] ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_carte" value="<?php echo $_REQUEST['id_carte']; ?>"/>
                    <input type="hidden" name="nr_rezervari" value="<?php echo $_REQUEST['nr_rezervari']; ?>"/>

                    <fieldset>
                        <label for="nume">Nume: </label>

                        <input type="text" name="nume">
                    </fieldset>

                    <fieldset>
                        <label for="telefon">Telefon: </label>

                        <input type="text" name="telefon">
                    </fieldset>

                    <fieldset>
                        <label for="mail">Adresa de email: </label>

                        <input type="text" name="mail">
                    </fieldset>

                    <fieldset>
                        <button type="submit" name="rezerva-carte">Rezerva</button>
                    </fieldset>
                </form>
            </article>
        </section>

        <aside>
            <div class="widget">
                <h3>Categori</h3>

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
</body>
</html>