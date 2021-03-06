<?php
    require_once 'functions.php';
    require_logined_session();

    header('Content-Type:text/html; charset=UTF-8');
    require 'db.php';
?>

<html>
    <head>
        <link rel="stylesheet" media="all" href="../CSS/All.css">
        <meta charset="UTF-8">
        <title>Users</title>
    </head>
    <body>
        <div class="header">
            <div class="title">
                <div class="title_text">
                    <!--
                    flex-grow: 3;
                    -->
                    <h1>ユーザー検索</h1>

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

        <div class="tex">

        条件の絞り込みを選択してください。
        </div>

        <!--フォームタグ-->
        <p><form action="" method="post">

            <!--条件-->
            <input type="checkbox" id="number">出席番号
            <select>
                <option>-</option>
            </select>

            <input type="checkbox" id="grade">学年
            <select>
                <option>-</option>
            </select>

            <br><br>

            <input type="checkbox" id="up">出席率
            <select>
                <option>％</option>
            </select>
            以上

            <input type="checkbox" id="down">出席率
            <select>
                <option>％</option>
            </select>
            以下

            <br><br><br>


            表示順番の指定をしてください。<br><br><br>

            <input type="checkbox" id="syouz">昇順
            <input type="checkbox" id="kouz">降順

            <br><br>

            グループ内検索<br><br><br>

            <!--スイッチ-->
            <div id="switchArea">
                <input type="checkbox" id="switch1">
                <label for="switch1"><span></span></label>
                <div id="swImg"></div>
            </div>

            <style type="text/css">
                /* === ボタンを表示するエリア ============================== */
                #switchArea {
                    line-height    : 40px;                /* 1行の高さ          */
                    letter-spacing : 0;                   /* 文字間             */
                    text-align     : center;              /* 文字位置は中央     */
                    font-size      : 17px;                /* 文字サイズ         */

                    position       : relative;            /* 親要素が基点       */
                    margin         : auto;                /* 中央寄せ           */
                    width          : 91px;               /* ボタンの横幅       */
                    background     : #fff;                /* デフォルト背景色   */
                }

                /* === チェックボックス ==================================== */
                #switchArea input[type="checkbox"] {
                    display        : none;            /* チェックボックス非表示 */
                }

                /* === チェックボックスのラベル（標準） ==================== */
                #switchArea label {
                    display        : block;               /* ボックス要素に変更 */
                    box-sizing     : border-box;          /* 枠線を含んだサイズ */
                    height         : 40px;                /* ボタンの高さ       */
                    border         : 2px solid #999999;   /* 未選択タブのの枠線 */
                    border-radius  : 20px;                /* 角丸               */
                }

                /* === チェックボックスのラベル（ONのとき） ================ */
                #switchArea input[type="checkbox"]:checked +label {
                    border-color   : #78bd78;             /* 選択タブの枠線     */
                }

                /* === 表示する文字（標準） ================================ */
                #switchArea label span:after{
                    content        : "OFF";               /* 表示する文字       */
                    padding        : 0 0 0 24px;          /* 表示する位置       */
                    color          : #999999;             /* 文字色             */
                }

                /* === 表示する文字（ONのとき） ============================ */
                #switchArea  input[type="checkbox"]:checked + label span:after{
                    content        : "ON";                /* 表示する文字       */
                    padding        : 0 24px 0 0;          /* 表示する位置       */
                    color          : #78bd78;             /* 文字色             */
                }

                /* === 丸部分のSTYLE（標準） =============================== */
                #switchArea #swImg {
                    position       : absolute;            /* 親要素からの相対位置*/
                    width          : 32px;                /* 丸の横幅           */
                    height         : 32px;                /* 丸の高さ           */
                    background     : #999999;             /* カーソルタブの背景 */
                    top            : 4px;                 /* 親要素からの位置   */
                    left           : 4px;                 /* 親要素からの位置   */
                    border-radius  : 16px;                /* 角丸               */
                    transition     : .2s;                 /* 滑らか変化         */
                }

                /* === 丸部分のSTYLE（ONのとき） =========================== */
                #switchArea input[type="checkbox"]:checked ~ #swImg {
                    transform      : translateX(51px);    /* 丸も右へ移動       */
                    background     : #78bd78;             /* カーソルタブの背景 */
                }
            </style>





            <div class="sub">
                <button type=“button”><a href="">検索</a></button>
            </div></<form>

        </form>

    </body>
</html>
