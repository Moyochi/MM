<?php
require_once 'functions.php';
require_logined_session();

header('Content-Type:text/html; charset=UTF-8');
require 'db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" media="all" href="../CSS/All.css">
    <link rel="stylesheet" media="all" href="../CSS/ResponsibleEdit.css">
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <title>ResponsibleEdit.html</title>
</head>
<body>


<!--どのアカウントで入ったか確認-->

<div class="header">
    <div class="title">
        <div class="title_text">
            <!--flex-grow: 3;-->
            <h1 class="head">
                <!-- 題名 -->
                管理者ユーザー編集
            </h1>
        </div>

    </div>

</div>

<!--検索バー -->
<div class="container">
    <input type="text" placeholder="Search..." id="sa-ch">
    <div class="search"></div>
</div>

<div class="contents">
    <ul class="nav">
        <li><a href="./index.php">担当グループ</a></li>
        <li><a href="Group.php">グループ管理</a></li>
        <li><a href="Users.php">ユーザー検索</a></li>
        <li><a href="Resuser.php">管理者ユーザー一覧</a></li>
        <li><a href="Groupmake.php">グループ作成</a></li>
        <li><a href="Classroom.php">教室管理</a></li>
        <li><a href="./logout.php?token=<?=h(generate_token())?>">ログアウト</a></li>
    </ul>
</div>

    <table>
    <div class="zi">
    <h2 id="zi_label">教室管理</h2>
    <tr>
        <th></th>
        <th>月曜日</th>
        <th>火曜日</th>
        <th>水曜日</th>
        <th>木曜日</th>
        <th>金曜日</th>
    </tr>
    <tr>
        <td>一限目</td>
        <th>
            <!--時間割プルダウン-->
            <div class="timetable" class="time_menu">
                <select>
                    <option>-</option>
                </select>
            </div>
        </th>
        <th>
            <div class="timetable" class="time_menu">
                <select>
                <option>-</option>
                </select>
            </div>
        </th>
        <th>
            <div class="timetable" class="time_menu">
                <select>
                <option>-</option>
                </select>
            </div>
        </th>
        <th>
            <div class="timetable" class="time_menu">
                <select>
                    <option>-</option>
                </select>
            </div>
        </th>
        <th>
            <div class="timetable" class="time_menu">
                <select>
                    <option>-</option>
                </select>
            </div>
        </th>
    </tr>
    <tr>
        <td>二限目</td>
        <th>
            <div class="timetable" class="time_menu">
                <select>
                    <option>-</option>
                </select>
            </div>
        </th>
        <th>
            <div class="timetable" class="time_menu">
                <select>
                    <option>-</option>
                </select>
            </div>
        </th>
        <th>
            <div class="timetable" class="time_menu">
                <select>
                    <option>-</option>
                </select>
            </div>
        </th>
        <th>
            <div class="timetable" class="time_menu">
                <select>
                    <option>-</option>
                </select>
            </div>
        </th>
        <th>
            <div class="timetable" class="time_menu">
                <select>
                    <option>-</option>
                </select>
            </div>
        </th>
    </tr>
    <tr>
        <td>三限目</td>
        <th>
            <div class="timetable" class="time_menu">
                <select>
                    <option>-</option>
                </select>
            </div>
        </th>
        <th>
            <div class="timetable" class="time_menu">
                <select>
                    <option>-</option>
                </select>
            </div>
        </th>
        <th>
            <div class="timetable" class="time_menu">
                <select>
                    <option>-</option>
                </select>
            </div>
        </th>
        <th>
            <div class="timetable" class="time_menu">
                <select>
                    <option>-</option>
                </select>
            </div>
        </th>
        <th>
            <div class="timetable" class="time_menu">
                <select>
                    <option>-</option>
                </select>
            </div>
        </th>

    </tr>
    <td>四限目</td>
    <th>
        <div class="timetable" class="time_menu">
            <select>
                <option>-</option>
            </select>
        </div>
    </th>
    <th>
        <div class="timetable" class="time_menu">
            <select>
                <option>-</option>
            </select>
        </div>
    </th>
    <th>
        <div class="timetable" class="time_menu">
            <select>
                <option>-</option>
            </select>
        </div>
    </th>
    <th>
        <div class="timetable" class="time_menu">
            <select>
                <option>-</option>
            </select>
        </div>
    </th>
    <th>
        <div class="timetable" class="time_menu">
            <select>
                <option>-</option>
            </select>
        </div>
    </th>
    </div>
</table>

<!--画面リロード-->
<div class="sub">
    <a href="ResponsibleEdit.html" id="time_ok">決定</a>
</div></form>


</body>
</html>