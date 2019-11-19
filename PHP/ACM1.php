<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
    <meta charset="UTF-8">
    <title>ACMS</title>
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
        <div class="title_text">
            <!--
            flex-grow: 3;
            -->
            <h1>状況管理</h1>
        </div>
        <!--クラスメニュー-->
        <p id="class">情報工学科</p>
    </div>
</div>




<!--    Jqueryのライブラリ-->
<script
        src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous">
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<!--momentのライブラリ（日付）-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/locale/ja.js"></script>

<script type="text/javascript">
    $(function () {
        $('#datepicker').datepicker({dateFormat: "yy-mm-dd"});
        $('#datepicker').datepicker("setDate",new Date());
        var now_jpn = moment();
    })
</script>



<!-- カレンダー -->
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">

    // html読み込み後に実行されるJavascriptの処理
    $(function(){
        $('#datepicker').datepicker({dateFormat:'yy-mm-dd'});
        $('#datepicker').datepicker('setDate', new Date());
    });



    // 日付入力欄が変更された時のイベント
    $('#datepicker').change(function() {
        selected = this.value; // 入力欄の値を取得
        $.ajax({
            type: 'GET',
            url: 'http://localhost:8081/mm/apitest/api.php',
            dataType: 'json',
            data: { date: selected },   // パラメータ(date)に入力欄の日付を設定する。
            success: function(json){
                var $myList = $('#myList'); // <ul>を取得する。
                $myList.empty(); // <ul>の子要素<li>を削除する。

                // 取得したデータを<li>として追加
                for( i in json ) {
                    $myList.append($('<li/>').text(json[i]));
                }
            },
            error: function(XMLHttpRequest,textStatus,errorThrown){
                // TODO:エラー処理
                alert('test');
            },
        });
    });
</script>





<!--検索バー-->
🔎<input type="text" id="sa-ch">

<!--メニューバー-->
<div class="tabs">
    　<li><input id="responsible"  name="menu"><a href="Responsible.html">担当グループ</a></li>
    <li><input id="group" name="menu"><a href="Group.html">グループ管理</a></li>
    <li><input id="users" name="menu"><a href="Users.html">ユーザー検索</a></li>
    <li><input id="resuser" name="menu"><a href="Resuser.html">管理者ユーザー一覧</a></li>
    <li><input id="groupmake" name="menu"><a href="Groupmake.html">グループ作成</a></li>
    <li><input id="classroom" name="menu"><a href="Classroom.html">教室管理</a></li>
    <li><input id="classroom" name="menu"><a href="Logout.html">ログアウト</a></li>
</div>

<!--フォームタグ-->
<form action="" method="post">

    <!--人の表情が入ります-->
    <!--
    <input type="image" src="image/face.png">
    -->

    <!--写真が入ります-->
    <!--グラフに飛ぶよん-->
    <form action="StudentGraph.html" method="post">
        <input type="image" src="image/noimage.gif">

        <p id="number">出席番号</p>
        <p id="name">名前</p>
        <p id="attend">出席率</p>
        <p id="status">状態</p>


        <p id="sum">◯人中◯人出席しました。</p>

    </form>

</body>
</html>
