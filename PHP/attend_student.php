<?php
require 'db.php';
?>

<?php
//$_POST['pc_id'] = '11_A';
//$_POST['attend_student_id'] = 1000001;
//$_POST['attend_id'] = 1;

if(isset($_POST['pc_id']) and isset($_POST['attend_student_id']) and isset($_POST['attend_id'])){
    $pc_id = $_POST['pc_id'];
    $date = date('Y-m-d');
    $day_of_week = date('w');
//    $day_of_week = 1;
    $time = date('H:i:s');
//    $time = '10:00:00';

    $attend_student_id = $_POST['attend_student_id'];
    $attend_id = $_POST['attend_id'];

    // [time_period, subject_id] 要求した出席用PCが配置されている教室で該当時間に受け付けている時限と、該当時間に実施される授業idを取得する。
    $register_env = prepareQuery("
        select time_period, CLS.subject_id
        from pc_classroom PC
          left join classrooms_lesson_schedule CLS on PC.classroom_id = CLS.classroom_id
          left join subjects s on CLS.subject_id = s.subject_id
        where pc_id = ? and day_of_the_week = ? and ? between reception_start and reception_end",
        [$pc_id, $day_of_week, $time]
    );
    $register_time_period = $register_env[0]['time_period'];
    $register_subject_id = $register_env[0]['subject_id'];

    //「出席」として情報を登録する。
    prepareQuery("
        update students_attend_lesson set attend_id = 1 where student_id = ? and date = ? and time_period = ?",
    [$attend_student_id, $date, $register_time_period]);

    //登録した授業がホームルーム(学校への出席として扱う、その日の最初の授業)であれば、student_attend_schoolに「出席」として情報を登録する。
    $HRclass = prepareQuery("
        select * from attend_max_sq_1 AMS1 where date = ? and time = ? and subject_id = ?",
        [$date, $register_time_period, $register_subject_id]);
    //レスポンスが1行以上(この授業がHRである)であれば、student_attend_schoolに登録する。
    if(count($HRclass)>0){
        prepareQuery("
            update students_attend_school set attend_id = 1 where student_id = ? and date = ?",
            [$attend_student_id, $date]);
    }
}
?>