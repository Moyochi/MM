<?php
require_once 'functions.php';
require_logined_session();
require 'db.php';

header('Content-Type:text/html; charset=UTF-8');

//グループ名変更
if (isset($_POST['update_class_id']) and isset($_POST['update_class_name'])) {
    prepareQuery("update classes set class_name = ? where class_id = ?"
        , [$_POST['update_class_name'], $_POST['update_class_id']]);
    $class = prepareQuery("
            select c.class_id, class_name
            from teachers_homerooms t2
            left join classes c on t2.class_id = c.class_id
            where teacher_id = ?", [$_SESSION['teacher_id']]);
    $_SESSION['class'] = null;
    foreach ($class as $row) {
        $_SESSION['class'][] = ['id' => $row['class_id'], 'name' => $row['class_name']];
    }
}
if(isset($_POST['class_id']) and isset($_POST['class_name'])){
    $class_id = $_POST['class_id'];
    $class_name = $_POST['class_name'];
    $_SESSION['current_class_id'] = $class_id;
    $_SESSION['current_class_name'] = $class_name;
}else{
    if(isset($_SESSION['current_class_id']) and isset($_SESSION['current_class_name'])) {
        $class_id = $_SESSION['current_class_id'];
        $class_name = $_SESSION['current_class_name'];
    }else{
        header('Location: index.php');
    }
}
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
                担当グループ編集
            </h1>
        </div>

        <div id="class" class="title_menu">
            <script type="text/javascript">
                function selectClass() {
                    // 選択されたオプションのバリューを取得する
                    var element = document.getElementById("class_id");
                    var selectedIndex = element.selectedIndex;
                    var form = document.createElement("form");
                    form.setAttribute("action", "");
                    form.setAttribute("method", "post");
                    form.style.display = "none";
                    document.body.appendChild(form);
                    var data = {
                        'class_id':element.options[selectedIndex].dataset.id,
                        'class_name':element.options[selectedIndex].dataset.name
                    }
                    for (var paramName in data) {
                        var input = document.createElement('input');
                        input.setAttribute('type', 'hidden');
                        input.setAttribute('name', paramName);
                        input.setAttribute('value', data[paramName]);
                        form.appendChild(input);
                    }
                    form.submit();
                }
            </script>
            <select id="class_id" onchange="selectClass()">
                <!-- 折り返し処理 -->
                <div id="re">
                    <?php foreach($_SESSION['class'] as $d){?>
                        <!--flex-grow: 1;-->
                        <option data-id="<?=h($d['id'])?>" data-name="<?=h($d['name'])?>"
                            <?php if($d['id'] == $class_id){echo 'selected';}?>>
                            <?=h($d['name'])?>
                        </option>
                    <?}?>
                </div>
            </select>
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
        <li><a href="Classroom.php">教室管理</a></li>
        <li><a href="./logout.php?token=<?=h(generate_token())?>">ログアウト</a></li>
    </ul>
    <div>
    <form action="" method="post">
        <div class="group_name">
            <h2>グループ名変更</h2>
            <p><input type="text" name="update_class_name" placeholder="<?=$class_name?>" size="50"></p>
        </div>
        <input type="hidden" name="update_class_id" value="<?=$class_id?>">
        <button type="submit">決定</button>
    </form>
    </div>

</div>

<!--    <table>-->
<!--    <div class="zi">-->
<!--    <h2 id="zi_label">教室管理</h2>-->
<!--    <tr>-->
<!--        <th></th>-->
<!--        <th>月曜日</th>-->
<!--        <th>火曜日</th>-->
<!--        <th>水曜日</th>-->
<!--        <th>木曜日</th>-->
<!--        <th>金曜日</th>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <td>一限目</td>-->
<!--        <th>-->
<!--            <!--時間割プルダウン-->-->
<!--            <div class="timetable" class="time_menu">-->
<!--                <select>-->
<!--                    <option>-</option>-->
<!--                </select>-->
<!--            </div>-->
<!--        </th>-->
<!--        <th>-->
<!--            <div class="timetable" class="time_menu">-->
<!--                <select>-->
<!--                <option>-</option>-->
<!--                </select>-->
<!--            </div>-->
<!--        </th>-->
<!--        <th>-->
<!--            <div class="timetable" class="time_menu">-->
<!--                <select>-->
<!--                <option>-</option>-->
<!--                </select>-->
<!--            </div>-->
<!--        </th>-->
<!--        <th>-->
<!--            <div class="timetable" class="time_menu">-->
<!--                <select>-->
<!--                    <option>-</option>-->
<!--                </select>-->
<!--            </div>-->
<!--        </th>-->
<!--        <th>-->
<!--            <div class="timetable" class="time_menu">-->
<!--                <select>-->
<!--                    <option>-</option>-->
<!--                </select>-->
<!--            </div>-->
<!--        </th>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <td>二限目</td>-->
<!--        <th>-->
<!--            <div class="timetable" class="time_menu">-->
<!--                <select>-->
<!--                    <option>-</option>-->
<!--                </select>-->
<!--            </div>-->
<!--        </th>-->
<!--        <th>-->
<!--            <div class="timetable" class="time_menu">-->
<!--                <select>-->
<!--                    <option>-</option>-->
<!--                </select>-->
<!--            </div>-->
<!--        </th>-->
<!--        <th>-->
<!--            <div class="timetable" class="time_menu">-->
<!--                <select>-->
<!--                    <option>-</option>-->
<!--                </select>-->
<!--            </div>-->
<!--        </th>-->
<!--        <th>-->
<!--            <div class="timetable" class="time_menu">-->
<!--                <select>-->
<!--                    <option>-</option>-->
<!--                </select>-->
<!--            </div>-->
<!--        </th>-->
<!--        <th>-->
<!--            <div class="timetable" class="time_menu">-->
<!--                <select>-->
<!--                    <option>-</option>-->
<!--                </select>-->
<!--            </div>-->
<!--        </th>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <td>三限目</td>-->
<!--        <th>-->
<!--            <div class="timetable" class="time_menu">-->
<!--                <select>-->
<!--                    <option>-</option>-->
<!--                </select>-->
<!--            </div>-->
<!--        </th>-->
<!--        <th>-->
<!--            <div class="timetable" class="time_menu">-->
<!--                <select>-->
<!--                    <option>-</option>-->
<!--                </select>-->
<!--            </div>-->
<!--        </th>-->
<!--        <th>-->
<!--            <div class="timetable" class="time_menu">-->
<!--                <select>-->
<!--                    <option>-</option>-->
<!--                </select>-->
<!--            </div>-->
<!--        </th>-->
<!--        <th>-->
<!--            <div class="timetable" class="time_menu">-->
<!--                <select>-->
<!--                    <option>-</option>-->
<!--                </select>-->
<!--            </div>-->
<!--        </th>-->
<!--        <th>-->
<!--            <div class="timetable" class="time_menu">-->
<!--                <select>-->
<!--                    <option>-</option>-->
<!--                </select>-->
<!--            </div>-->
<!--        </th>-->
<!---->
<!--    </tr>-->
<!--    <td>四限目</td>-->
<!--    <th>-->
<!--        <div class="timetable" class="time_menu">-->
<!--            <select>-->
<!--                <option>-</option>-->
<!--            </select>-->
<!--        </div>-->
<!--    </th>-->
<!--    <th>-->
<!--        <div class="timetable" class="time_menu">-->
<!--            <select>-->
<!--                <option>-</option>-->
<!--            </select>-->
<!--        </div>-->
<!--    </th>-->
<!--    <th>-->
<!--        <div class="timetable" class="time_menu">-->
<!--            <select>-->
<!--                <option>-</option>-->
<!--            </select>-->
<!--        </div>-->
<!--    </th>-->
<!--    <th>-->
<!--        <div class="timetable" class="time_menu">-->
<!--            <select>-->
<!--                <option>-</option>-->
<!--            </select>-->
<!--        </div>-->
<!--    </th>-->
<!--    <th>-->
<!--        <div class="timetable" class="time_menu">-->
<!--            <select>-->
<!--                <option>-</option>-->
<!--            </select>-->
<!--        </div>-->
<!--    </th>-->
<!--    </div>-->
<!--</table>-->
<!---->
<!--<!--画面リロード-->-->
<!--<div class="sub">-->
<!--    <a href="ResponsibleEdit.html" id="time_ok">決定</a>-->
<!--</div></form>-->


</body>
</html>