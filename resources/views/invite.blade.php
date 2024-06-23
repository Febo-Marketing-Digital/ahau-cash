<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>XV Zoe</title>
    <style>
        body {
            background-color: #a094c4;
        }

        div {
            display: grid;
            place-items: center;
        }

        img {
            width: 320px;
        }

        @media only screen and (min-width: 760px) {
            img {
                width: 750px;
            }
        }
    </style>
</head>

<body>
    <div>
        <img src="/images/xvzoe-landing.jpg" alt="XV Zoe" usemap="#invitemap">
        {{-- <audio name="music" src="https://youtu.be/e8jN1-HC-Vc?si=Hi3sJ2u2kfWe38Zv" loop="false" hidden="true"
            autostart="true">
            <p>If you are reading this, it is because your browser does not support the audio element.</p>
        </audio> --}}
        <iframe width="1" height="1" wmode="transparent"
            src="https://www.youtube.com/embed/e8jN1-HC-Vc?si=ThLVq2OwavjSlZOA" frameborder="0" autoplay loop></iframe>
    </div>

    <map name="invitemap">
        <area target="_blank" alt="location" title="ubicación" href="https://maps.app.goo.gl/Ynpx9Kz7r1phZCQB9"
            coords="335,2120,445,2230" shape="rect">
        <area target="_blank" alt="Liverpool" title="Liverpool"
            href="https://mesaderegalos.liverpool.com.mx/milistaderegalos/51456361" coords="500,2720,555,2782"
            shape="rect">
        <area target="_blank" alt="palacio de hierro" title="palacio de hierro"
            href="https://www.elpalaciodehierro.com/buscar?eventId=387633" coords="500,2785,555,2830" shape="rect">
    </map>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/maphilight/1.4.2/jquery.maphilight.min.js"
        integrity="sha512-1YiTT24MNHA6DRzyu+w9F5Egc8evYlyEnzSSTD4/M7q42xEb5fSpNgn0+1CPy3evubHs3xdlh8uXgae0DOhR7Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="js/imageMapResizer.min.js"></script>
    <script>
        $(document).ready(function() {
            $('img[usemap]').maphilight();
            $('#invitemap').imageMapResize();
        });
    </script>
</body>

</html>
