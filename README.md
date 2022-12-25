# Реализация шаблона CRUD

## Цель работы:
Разработать и реализовать клиент-серверную информационную систему, реализующую механизм CRUD.

## Задание:
- добавление текстовых заметок в общую ленту
- реагирование на чужие заметки (лайки)
## Ход работы:
- Разработать пользовательский интерфейс
- Описать сценарии работы
- Описать API сервера и хореографию
- Описать структуру БД и алгоритмы обработки данных
- Написать программный код
- Удостовериться в корректности кода
## Пользовательский интерфейс
![Интерфейс](https://user-images.githubusercontent.com/119607566/209472430-5059581b-4060-4c3c-b8bd-7f17bf8ba8e7.png)
## Пользовательский сценарий
1. Пользователь вводит сообщение и заголовок, нажимает кнопку "Cоздать тему", чтобы отправить сообщение на сайт - сообщение успешно загружается и попадает наверх списка, остальные сообщения сдвигаются вниз.
2. Пользователь видит сообщение которое ему понравилось и нажимает кнопку Like. Счетчик лайков на данном посте увеличивается на 1.
## Описание API сервера и его хореографии
Сервер использует HTTP POST запросы для выгрузки комментариев в базу данных.

1. Алгоритм отправки сообщения - после проверки на заполненность полей с заголовком и комментарием в базу данных отправляется запрос, добавляющий комментарий в БД с учётом даты его отправки, текстом заголовка и текстом сообщения. Если проверка пройдена не была, поступит уведомление о необходимости заполнить оба доступных поля.
2. Алгоритм вывода комментариев на экран форума - в базу данных отправляется запрос, по которому берутся 100 последних комментариев, они выводятся на экран в порядке убывания их "message_id", так, что более новые комментарии оказываются сверху.
3. Алгоритм добавления лайка - при нажатии на кнопку "Like" на определённом сообщении страница обновляется и счётчик "Likes: Х", где Х - число лайков, возрастает на единицу.
## Описание структуры базы данных
Браузерное приложение phpMyAdminДля используется для просмотра содержимого базы данных. Всего 5 столбцов:

"cid" типа int с автоинкрементом для выдачи уникальных id каждому сообщению
"date" типа datetime для хранения даты и времени отправки сообщения на сайт
"message" типа text для хранения сообщений
"likes" типа int для хранения количества лайков
## Значимые фрагменты кода
1. Алгоритм отправки комментария в базу данных
```
function setComment() {
    require("connection.php");
    if(isset($_POST['commentSubmit'])) {
            $date = $_POST['date'];
            $title = $_POST['message_title'];
            $message = $_POST['message_text'];
        if (!empty($title) && !empty ($message)) {
            $date = $_POST['date'];
            $title = $_POST['message_title'];
            $message = $_POST['message_text'];
            $query = "INSERT INTO messages (date, message_title, message_text) VALUES ('$date', '$title', '$message')";
            $result = mysqli_query($con, $query);
        }
        else {
            echo "<br><b><div class ='comment'>Please, fill out both boxes to submit your message.</div></b>";
        }
    }
}
```
2. Алгоритм вывода комментариев на сайт
```
function getComments($page_count) {
    require("connection.php");
    $query = "SELECT * FROM messages ORDER BY message_id DESC LIMIT 100";
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_assoc($result)) {   
        echo "<div class='comment'>
            <div> By <b>Anonymous</b> on <i>".$row['date']."</i><br><b>".$row['message_title']."</b></div>
            <div>".$row["message_text"]."</div>
            <div><br>  <form method='POST' action='".likeSubmit($row)."'>  <button type='submit' name='".$row['message_id']."' class='like_button'>Like</button>  Likes: ".$row["likes"]."</form></div>
        </div><br>";
    }
}
```
3. Функция добавления лайка на комментарий
```
function likeSubmit($conn,$row) {
 require("connection.php");
    if(isset($_POST[$row['cid']])) {
        $cid = $row['cid'];
        $likes = $row['likes']+1;
        $query = "UPDATE comments SET likes = '$likes' WHERE cid = '$cid'";
        $result = mysqli_query($conn, $query);
    }
}
```
