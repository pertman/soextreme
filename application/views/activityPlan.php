<?php
if (isset($activity)){
    var_dump($activity); }

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hello Bulma!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma-carousel@4.0.4/dist/css/bulma-carousel.min.css">
</head>
<body>
<!-- Start Hero Carousel -->
<section class="hero is-medium has-carousel">
    <div id="carousel-demo" class="hero-carousel">
        <div class="item-1">
            <img src="https://bulma.io/images/placeholders/1920x1080.png" alt="Placeholder image">
        </div>
        <div class="item-2">
            <img src="https://bulma.io/images/placeholders/1280x960.png" alt="Placeholder image">
        </div>
        <div class="item-3">
            <img src="https://bulma.io/images/placeholders/1280x960.png" alt="Placeholder image">
        </div>
    </div>
    <div class="hero-head"></div>
    <div class="hero-body"></div>
    <div class="hero-foot"></div>
</section>
<!-- End Hero Carousel -->

<script src="https://cdn.jsdelivr.net/npm/bulma-carousel@4.0.4/dist/js/bulma-carousel.min.js"></script>
<script>
    bulmaCarousel.attach('#carousel-demo', {
        slidesToScroll: 1,
        slidesToShow: 4
    });
</script>
</body>
</html>
