<?php
    include "server.php";

$sql = 'SELECT * FROM testimonials';
$testimoniale = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_testimonial.css">
    <title>Slider</title>
</head>

<body>
<div id="carousel">
    <div class="btn-bar">
        <div id="buttons"><a id="prev" href="#"><</a><a id="next" href="#">></a> </div></div>
    <div id="slides">
        <ul>
            <?php
                if($testimoniale->num_rows > 0) {
                    while ($testimonial = $testimoniale->fetch_array()) {
                        ?>

                        <li class="slide">
                            <div class="quoteContainer">
                                <p class="quote-phrase"><span class="quote-marks">"</span><?php echo $testimonial['testimonial'] ?><span class="quote-marks">"</span></p>
                            </div>
                            <div class="authorContainer">
                                <p class="quote-author"><?php echo $testimonial['autor_testimonial'] ?></p>
                            </div>
                        </li>

                    <?php }
                } else {
                    $output = 'There was no search results!';
                }
            ?>
        </ul>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/testimonial.js"></script>
</div>
</body>
</html>

