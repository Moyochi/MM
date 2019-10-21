<html lang="en">
<head>
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


<!--日付-->
<p id="day">
    <script type="text/javascript">
        weeks=new Array("日","月","火","水","木","金","土");
        today=new Date();
        m=today.getMonth()+1;
        d=today.getDate();
        w=weeks[today.getDay()];
        document.write("<span>",m,"<\/span>月");
        document.write("<span>",d,"<\/span>日");
        document.write("(<span>",w,"<\/span>)");
    </script>
</p>

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
