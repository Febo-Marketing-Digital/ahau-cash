<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>XV Zoe</title>
    <style>
        body {
            background-color: #a094c4;
        }

        /* div {
            display: grid;
            place-items: center;
        }

        img {
            width: 350px;
        }

        @media only screen and (min-width: 760px) {
            img {
                width: 750px;
            }
        } */
        .hover_group:hover {
            opacity: 1;
        }

        #projectsvg {
            position: relative;
            width: 100%;
            padding-bottom: 555%;
            vertical-align: middle;
            margin: 0;
            overflow: hidden;
            background: lightgreen;
        }

        #projectsvg svg {
            display: inline-block;
            position: absolute;
            top: 0;
            left: 0;
        }
    </style>
</head>

<body>
    <figure id="projectsvg">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            viewBox="0 0 3056 16984" preserveAspectRatio="xMinYMin meet">

            <!-- set your background image -->
            <image width="3056" height="16984" xlink:href="/images/xvzoe-landing.jpg" />

            <!-- create the regions -->
            <g class="hover_group" opacity="0">
                <a xlink:href="https://maps.app.goo.gl/Ynpx9Kz7r1phZCQB9">
                    <text x="1730" y="8900" font-size="20">Ubicacion</text>
                    <rect x="1450" y="8700" opacity="0.2" fill="#CCCCCC" width="264.6" height="387.8"></rect>
                </a>
            </g>
            <g class="hover_group" opacity="0">
                <a xlink:href="https://mesaderegalos.liverpool.com.mx/milistaderegalos/51456361">
                    <text x="2100" y="11220" font-size="20">Liverpool</text>
                    <rect x="2010" y="11120" opacity="0.2" fill="#FFC0CB" width="200" height="200"></rect>
                </a>
            </g>
            <g class="hover_group" opacity="0">
                <a xlink:href="https://www.elpalaciodehierro.com/buscar?eventId=387633">
                    <text x="2100" y="11450" font-size="20">Palacio de Hierro</text>
                    <rect x="2010" y="11350" opacity="0.2" fill="#FFFF00" width="200" height="200"></rect>
                </a>
            </g>
            <g class="hover_group" opacity="0">
                <a xlink:href="https://wa.me/qr/BIG6MUWPXILSG1">
                    <text x="1630" y="12350" font-size="20">whatsapp</text>
                    <rect x="1350" y="12250" opacity="0.2" fill="#3dfe43" width="400" height="400"></rect>
                </a>
            </g>
        </svg>
    </figure>

    <audio loop autoplay preload="none" src="/media/i_see_the_light.mp3" style="display: none;">
    </audio>

    {{-- <div>
        <img src="/images/xvzoe-landing.jpg" alt="XV Zoe" usemap="#invitemap" />
    </div>

    <iframe width="1" height="1" wmode="transparent"
        src="https://www.youtube-nocookie.com/embed/e8jN1-HC-Vc?si=GKOZeyaS5PqDooVr&amp;controls=0&amp;autoplay=1"
        title="background music" frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; web-share"></iframe>

    <map name="invitemap">
        <area target="_blank" alt="location" title="ubicación" href="https://maps.app.goo.gl/Ynpx9Kz7r1phZCQB9"
            coords="335,2120,445,2230" shape="rect">
        <area target="_blank" alt="Liverpool" title="Liverpool"
            href="https://mesaderegalos.liverpool.com.mx/milistaderegalos/51456361" coords="500,2720,555,2782"
            shape="rect">
        <area target="_blank" alt="palacio de hierro" title="palacio de hierro"
            href="https://www.elpalaciodehierro.com/buscar?eventId=387633" coords="500,2785,555,2830" shape="rect">
    </map> --}}
    {{--
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/maphilight/1.4.2/jquery.maphilight.min.js"
                integrity="sha512-1YiTT24MNHA6DRzyu+w9F5Egc8evYlyEnzSSTD4/M7q42xEb5fSpNgn0+1CPy3evubHs3xdlh8uXgae0DOhR7Q=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    {{-- <script>
        $(document).ready(function() {
            $('img[usemap]').maphilight();
        });
    </script> --}}
</body>

</html>
