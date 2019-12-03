<?php
require 'db.php';
?>

<?php
//テスト用データ
    //Users.html -web
        //$_POST['user_search'] 遷移先画面のurl /PHP/['ここを入れる']
        //$_POST['name'] 検索するユーザーの学籍番号 or 学生ユーザー名。

    //出席情報取得API -electron
        //$_POST['pc_id'] リクエスト元PCのpc_id
            $_POST['pc_id'] = '11_A';
        //$_POST['request_flg'] どのAPIを呼び出すかのフラグ。

    //クラス出席状況取得API -electron
        //$_POST['day_week'] 何曜日かの数字。月:1 火:2 ・・・
        //$_POST['time_period'] 何限目かの数字。 1限目:1 2限目:2　・・・
        //$_POST['request_flg'] どのAPIを呼び出すかのフラグ。
        //$_POST['pc_id'] リクエスト元PCのpc_id
            $_POST['pc_id'] = '11_A';

    //時限・授業名取得API -electron
        //$_POST['day_week'] 何曜日かの数字。月:1 火:2 ・・・
        //$_POST['time_period'] 何限目かの数字。 1限目:1 2限目:2　・・・
        //$_POST['request_flg'] どのAPIを呼び出すかのフラグ。
        //$_POST['pc_id'] リクエスト元PCのpc_id
            $_POST['pc_id'] = '11_A';

    //出席予定学生一覧取得API -electron
        //$_POST['day_week'] 何曜日かの数字。月:1 火:2 ・・・
        //$_POST['time_period'] 何限目かの数字。 1限目:1 2限目:2　・・・
        //$_POST['subject_id'] 何の教科かを示す数字。　・・・
        //$_POST['request_flg'] どのAPIを呼び出すかのフラグ。

    $day = date("Y-m-d");
    //テストデータ
    $day = "2019-09-01";

    if(isset($_POST['user_search'])){
        //Users.html -web
        $data = prepareQuery('select student_id from students where student_id = ? or student_name = ?',[$_POST['name'],$_POST['name']]);
        $get_student_id = '?selected_student_id='.$data[0]['student_id'];
        var_dump($data[0]);
        header("Location: http://localhost:8081/mm_project/PHP/StudentPro.php" . $get_student_id);
    }else if(isset($_POST['pc_id']) and isset($_POST['request_flg'])) {
        //出席情報取得API -electron
        if ($_POST['request_flg'] == 'yes') {
            $data = prepareQuery(
                "select SID.student_id,student_name,COALESCE(ROUND(LR.rate),0)totalRate,COALESCE(ROUND(LRM.rate),0)monthRate
                from (
                  select
                    student_id,
                    student_name
                  from students
                  where student_id = ?
                )SID
                  left join lesson_rate LR on SID.student_id = LR.student_id and LR.subject_id = ?
                  left join lesson_rate_month LRM on SID.student_id = LRM.student_id and LRM.subject_id = ? and LRM.month = ?",
                [$_POST['student_id'], $_POST['subject_id'], $_POST['subject_id'], date('m') + 1]
            );
            print json_encode($data, JSON_PRETTY_PRINT);
        }
        //クラス出席状況取得API -electron
        if ($_POST['request_flg'] == 'ACM') {
            $data = prepareQuery("
                select student_id,attend_id
                from(
                  select time_period, subject_id
                  from pc_classroom PC
                  left join classrooms_lesson_schedule CLS on PC.classroom_id = CLS.classroom_id
                  where PC.pc_id = ?
                        and day_of_the_week = ?
                        and time_period = ?)SQ
                  left join students_attend_lesson SAS on SAS.time_period = SQ.time_period and SAS.subject_id = SQ.subject_id
                where date = ?",
                [$_POST['pc_id'], $_POST['day_week'], $_POST['time_period'], $day]
            );
            print json_encode($data, JSON_PRETTY_PRINT);
        }
        //時限・授業名取得API -electron
        if ($_POST['request_flg'] == 'shooting_subject') {
            $data = prepareQuery("
                select time_period, CLS.subject_id, subject_name
                from pc_classroom PC
                  left join classrooms_lesson_schedule CLS on PC.classroom_id = CLS.classroom_id
                  left join subjects s on CLS.subject_id = s.subject_id
                where pc_id = ? and day_of_the_week = ? and ? between reception_start and reception_end",
                [$_POST['pc_id'], $_POST['day_week'], $_POST['time']]
            );
            print json_encode($data, JSON_PRETTY_PRINT);
        }
        //出席予定学生一覧取得API -electron
        if ($_POST['request_flg'] == 'shooting_student') {
            $data = prepareQuery("
                select S.student_id, student_name
                from classes_lesson_schedule CLS
                    left join students S on S.class_id = CLS.class_id
                    left join students_subjects ss on S.student_id = ss.student_id and CLS.subject_id = ss.subject_id
                where day_of_the_week = ? and time = ? and CLS.subject_id = ?
                ",
                [$_POST['day_week'], $_POST['time_period'], $_POST['subject_id']]
            );
            print json_encode($data, JSON_PRETTY_PRINT);
        }
    }
?>