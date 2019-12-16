<html>
    web : 名前検索フォーム</br>
    <form action="search.php" method="post">
        name:<input type="text" name="name" placeholder="name"></br>
        <input type="hidden" name="user_search" value="search.php"></br>
        <input type="submit" value="送信">
    </form>
    </br>
    </br>
    出席用PC : 出席情報確認API呼び出し</br>
    <form action="search.php" method="post">
        student_id:<input type="text" name="student_id" placeholder="student_id"></br>
        subject_id:<input type="text" name="subject_id" placeholder="subject_id"></br>
        <input type="hidden" name="request_flg" value="yes">
        <input type="submit" value="送信">
    </form>
    </br>
    </br>
    出席用PC : クラス出席状況取得</br>
    <form action="search.php" method="post">
        曜日:<input type="text" name="day_week" placeholder="day_week"><br>
        時限:<input type="text" name="time_period" placeholder="time_period"><br>
        <input type="hidden" name="request_flg" value="ACM">
        <input type="submit" value="送信">
    </form>
    </br>
    </br>
    出席用PC : 時限・授業名取得<br>
    <form action="search.php" method="post">
        曜日:<input type="text" name="day_week" placeholder="day_week"><br>
        時間:<input type="text" name="time" placeholder="??:??:??"><br>
        <input type="hidden" name="request_flg" value="shooting_subject">
        <input type="submit" value="送信">
    </form>
    </br>
    </br>
    出席用PC : 出席予定学生一覧取得<br>
    <form action="search.php" method="post">
        曜日:<input type="text" name="day_week" placeholder="day_week"><br>
        時限:<input type="text" name="time_period" placeholder="time_period"><br>
        教科ID:<input type="text" name="subject_id" placeholder="subject_id"><br>
        <input type="hidden" name="request_flg" value="shooting_student">
        <input type="submit" value="送信">
    </form>

</html>