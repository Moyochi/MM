<?php
require 'db.php';
?>

<?php
session_destroy();
    //テスト用データ
    $_POST['data']['class_id'] = 1;
    $_POST['data']['class_year'] = $_POST['grade'];


    if(isset($_POST['redirect'])){
        //sa-chからの検索。
        if ($_POST['redirect'] == "redirect_name_search.php") {
            $data = prepareQuery('select * from students where student_id = ? or student_name = ?',[$_POST['name'],$_POST['name']]);
        }
        //AttendanceConfirmation.htmlからの検索。
        else if($_POST['redirect'] == "redirect_ACM.php"){
            $data = prepareQuery(
                "select student_num, student_name, ROUND(lesson_rate)lesson_rate, COALESCE(attend_name,'欠席')attend_data
            from (
              select S.student_id,CS.student_num,student_name,COALESCE(rate, 0) lesson_rate
              from (select SQ1.class_id
                    from (select *
                          from courses_classes
                          where class_id = ? ## 学科選択
                         ) SQ1
                    where SQ1.class_year = ? ## 学年選択
                   ) SQ2
                left join students S on SQ2.class_id = S.class_id
                left join classes_students CS on SQ2.class_id = CS.class_id and S.student_id = CS.student_id
                left join lesson_rate LR on S.student_id = LR.student_id and LR.subject_id = ? ## 教科選択
              order by S.student_id
            )SQ3 left join students_attend_lesson SAL on SQ3.student_id = SAL.student_id and SAL.date = ? and SAL.time_period = ?
              left join attend A on SAL.attend_id=A.attend_id
              order by SQ3.student_num",
                [$_POST['data']['class_id'], $_POST['data']['class_year'], $_POST['subject_id'], $_POST['date'], $_POST['time']]
            );
        }
        session_start();
        $_SESSION['data'] = $data;
        header("Location: http://localhost:63342/mm_project/PHP/" . $_POST['redirect']);
    }

    //出席情報取得API
    if(isset($_POST['pc_id'])){
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
            [$_POST['student_id'], $_POST['subject_id'], $_POST['subject_id'], date('m')+1]
        );
        print json_encode($data, JSON_PRETTY_PRINT);
    }
?>