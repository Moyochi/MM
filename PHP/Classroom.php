<?php

?>


<html>
    <head>
        <link rel="stylesheet" media="all" href="../CSS/All.css">
        <meta charset="UTF-8">
        <title>Classroom</title>
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
                    <h1>教室管理</h1>

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

                <!--フォームタグ-->
                <form action="" method="post">

                    <table border="20px" cellpadding="10px">
                        <caption>教室管理</caption>
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
                                <div id="classtable" class="class_menu">
                                    <select>
                                        <option>-</option>
                                    </select>
                                </div>
                            </th>
                            <th>
                                <div id="classtable1" class="class_menu">            <select>
                                        <option>-</option>
                                    </select>
                                </div>
                            </th>
                            <th>
                                <div id="classtable2" class="class_menu">            <select>
                                        <option>-</option>
                                    </select>
                                </div>
                            </th>
                            <th>
                                <div id="classtable3" class="class_menu">            <select>
                                        <option>-</option>
                                    </select>
                                </div>
                            </th>
                            <th>
                                <div id="classtable4" class="class_menu">            <select>
                                        <option>-</option>
                                    </select>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <td>二限目</td>
                            <th>
                                <div id="classtable5" class="class_menu">            <select>
                                        <option>-</option>
                                    </select>
                                </div>
                            </th>
                            <th>
                                <div id="classtable6" class="class_menu">            <select>
                                        <option>-</option>
                                    </select>
                                </div>
                            </th>
                            <th>
                                <div id="classtable7" class="class_menu">            <select>
                                        <option>-</option>
                                    </select>
                                </div>
                            </th>
                            <th>
                                <div id="classtable8" class="class_menu">            <select>
                                        <option>-</option>
                                    </select>
                                </div>
                            </th>
                            <th>
                                <div id="classtable9" class="class_menu">            <select>
                                        <option>-</option>
                                    </select>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <td>三限目</td>
                            <th>
                                <div id="classtable10" class="class_menu">            <select>
                                        <option>-</option>
                                    </select>
                                </div>
                            </th>
                            <th>
                                <div id="classtable11" class="class_menu">            <select>
                                        <option>-</option>
                                    </select>
                                </div>
                            </th>
                            <th>
                                <div id="classtable12" class="class_menu">            <select>
                                        <option>-</option>
                                    </select>
                                </div>
                            </th>
                            <th>
                                <div id="classtable13" class="class_menu">            <select>
                                        <option>-</option>
                                    </select>
                                </div>
                            </th>
                            <th>
                                <div id="classtable14" class="class_menu">            <select>
                                        <option>-</option>
                                    </select>
                                </div>
                            </th>

                        </tr>
                        <td>四限目</td>
                        <th>
                            <div id="classtable15" class="class_menu">            <select>
                                    <option>-</option>
                                </select>
                            </div>
                        </th>
                        <th>
                            <div id="classtable16" class="class_menu">            <select>
                                    <option>-</option>
                                </select>
                            </div>
                        </th>
                        <th>
                            <div id="classtable17" class="class_menu">            <select>
                                    <option>-</option>
                                </select>
                            </div>
                        </th>
                        <th>
                            <div id="classtable18" class="class_menu">            <select>
                                    <option>-</option>
                                </select>
                            </div>
                        </th>
                        <th>
                            <div id="classtable19" class="class_menu">            <select>
                                    <option>-</option>
                                </select>
                            </div>
                        </th>
                    </table>
                    <br><br>

                    <!--画面リロード-->
                    <div class="sub">
                        <button type=“button”><a href="Classroom.html">OK</a></button>
                    </div></form>

    </body>
</html>

