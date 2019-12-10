<?php
require 'db.php';
?>

<?php

$date = date('Y-m-d');
$day_of_week = date('w');
$time = date('H');

//午後3時から翌日の午前4時までの間に実行された場合は、授業終了後処理を、
//午前4時から午前9時までの時間に実行された場合は、授業開始前処理を実行する。
if($time > 15 or $time > 0 and $time < 3){
    after_day($date, $day_of_week);
}elseif ($time < 9){
    before_day($date, $day_of_week);
}

//授業開始前処理　
//・lesson_history, lesson_history_grope に実施授業を登録
//・students_attend_lesson, students_attend_school に出席予定者を「未登録」として登録。
function before_day($date, $day_of_week){
// [classroom_id, time_period, subject_id] 今日行う全ての授業を配列で取得
    $classroom_lesson_schedule = prepareQuery("
    select classroom_id, time_period, subject_id
    from classrooms_lesson_schedule
    where day_of_the_week = ?",
        [$day_of_week]);
// 今日行う予定の授業をlesson_historyに登録する
    foreach ($classroom_lesson_schedule as $row) {
        prepareQuery("
          insert into lesson_history (date, time, subject_id, classroom_id) values (?, ?, ?, ?)",
          [$date, $row['time_period'], $row['subject_id'], $row['classroom_id']]);
    }
// [lesson_history_id, class_id] 登録した授業を行うクラスのclass_idを取得する。　
    $history_class_relation = prepareQuery("
    select lesson_history_id, class_id
    from classes_lesson_schedule CLS
      left join lesson_history LH on CLS.day_of_the_week = LH.lesson_history_id and CLS.time = LH.time and CLS.subject_id = LH.subject_id
    where date = ?",
        [$date]);
// 登録した今日行う予定の授業と、それを受講するクラスの関連をclasses_lesson_scheduleに登録する。
    foreach ($history_class_relation as $row) {
        prepareQuery("
        insert into lesson_history_grope values (?,?)",
            [$row['lesson_history_id'], $row['class_id']]);
    }
// [classroom_id, time_period, student_id] ある教室である時間において、出席予定である学生のIDリストを取得
    $student_schedule = prepareQuery("
    select CLS.subject_id, time_period, student_id
    from classrooms_lesson_schedule CLS
      left join students_subjects SS on CLS.subject_id = SS.subject_id
    where day_of_the_week = ?
    order by student_id",
        [$day_of_week]);
// 全ての今日における実施予定の授業について、出席予定である学生を、出席情報「未登録」でstudents_attend_lessonに登録する。
    foreach ($student_schedule as $row) {
        prepareQuery("
        insert into students_attend_lesson values (?,?,?,?,7,0,null)",
            [$row['student_id'], $date, $row['time_period'], $row['subject_id']]);
    }
// 今日学校へ出席予定である学生を、出席情報「未登録」でstudents_attend_schoolに登録する。
    $student_id = 'noone';
    foreach ($student_schedule as $row) {
        if ($student_id != $row['student_id']) {
            $student_id = $row['student_id'];
            prepareQuery("
            insert into students_attend_school values (?,?,?)",
                [$student_id, $date, 7]);
        } else continue;
    }
}
//授業終了後処理
//・students_attend_school, students_attend_lesson の「未登録」者を、「欠席」や「欠課」に変更する。
function after_day($date, $day_of_week){
    // students_attend_lesson内の、今日実施された授業について、「未登録」となっている未出席者の出席情報を「欠課」に変更する。
    prepareQuery("
        update students_attend_lesson set attend_id = 5 where attend_id = 7 and date = ?", [$date]);
    // students_attend_school内の、今日の学校への出席について、「未登録」となっている欠席者の出席情報を「欠席」に変更する。
    prepareQuery("
      update students_attend_school set attend_id = 2 where attend_id = 7 and date = ?", [$date]);
}
?>
