<?php

?>


<html>
<head>
    <meta charset="UTF-8">
    <title>shooting</title>
</head>
<body>
<div class="header">
    <div class="title">
        <!--
        color: #364e96;
        border: solid 3px #364e96;
        padding: 0.5em;
        border-radius: 0.5em;
        display: flex;
        -->
        <!--フォームタグ-->
        <form action="" method="post">

            <!--撮影画面がはいる-->
            <body>

            <video id="camera"></video>
            <canvas id="picture"></canvas>

            <audio id="se" preload="auto">
                <source src="../mp3/camera-shutter1.mp3" type="audio/mp3">
            </audio>
            </body>

            <div class="sub">
                <button type=“button”><a href="recognition.html">撮影</a></button>
            </div></form>

</body>
</html>
