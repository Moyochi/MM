<html>
    web : 名前検索フォーム</br>
    <form action="search.php" method="post">
        name:<input type="text" name="name" placeholder="name"></br>
        <input type="hidden" name="user-search" value="search.php"></br>
        <input type="submit" value="送信">
    </form>
    </br>
    </br>
    出席用PC : 出席情報確認API呼び出し</br>
    <form action="search.php" method="post">
        student_id:<input type="text" name="student_id" placeholder="student_id"></br>
        subject_id:<input type="text" name="subject_id" placeholder="subject_id"></br>
        <input type="hidden" name="request_flg" value="yes.html">
        <input type="submit" value="送信">
    </form>
    </br>
    </br>
    <form action="search.php" method="post">
        曜日:<input type="text" name="day_week" placeholder="day_week"><br>
        時限:<input type="text" name="time_period" placeholder="time_period"><br>
        <input type="hidden" name="request_flg" value="ACM.html">
        <input type="submit" value="送信">
    </form>

</html>