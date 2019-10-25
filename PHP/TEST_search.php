<html>
    ACM.php</br>
    <form action="search.php" method="post">
        subject:<input type="text" name="subject_id" placeholder="subject"></br>
        grade:<input type="text" name="grade" placeholder="grade"></br>
        day:<input type="text" name="date" placeholder="date"></br>
        time:<input type="text" name="time" placeholder="time"></br>
        <input type="hidden" name="redirect" value="redirect_ACM.php"></br>
        <input type="submit" value="送信">
    </form>
    </br>
    </br>
    名前検索フォーム</br>
    <form action="search.php" method="post">
        name:<input type="text" name="name" placeholder="name"></br>
        <input type="hidden" name="redirect" value="redirect_name_search.php"></br>
        <input type="submit" value="送信">
    </form>
    </br>
    </br>
    出席用PCのAPI呼び出し</br>
    <form action="search.php" method="post">
        student_id:<input type="text" name="student_id" placeholder="student_id"></br>
        subject_id:<input type="text" name="subject_id" placeholder="subject_id"></br>
        <input type="hidden" name="pc_id" value="1"></br>
        <input type="submit" value="送信">
    </form>

</html>