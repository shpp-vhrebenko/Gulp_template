<?php
include ('settings.php');

function startup()
{
    // создание линка подключение к БД
    sql_connect();

    // Языковая настройка.
    setlocale(LC_ALL, 'ru_RU.UTF-8');
    mb_internal_encoding('UTF-8');

    // запуск сесси
    session_start();
}

// Функция подключения к БД
function sql_connect()
{
    static $link;  
    // только одно соединение с БД
    if($link === null) {
        // Подключение к БД
        $link = mysqli_connect(HOSTNAME, USERNAME, PASSWORD);
        $db = mysqli_select_db($link, DB_NAME);
        // Создание БД, таблицы и заполнение таблицы
       /* if(!$db) {
            create_db();
            mysqli_select_db($link, $dbName);
            create_tb();
            create_data();
        }*/
        mysqli_query($link, 'SET NAMES utf8');
        mysqli_set_charset($link, 'utf8');
    }
    return $link;
}

function sql_select($sql)
{
    // Выполнение запроса
    $result = mysqli_query(sql_connect(), $sql);

    if (!$result) {
        die(mysqli_error(sql_connect()));
    }

    // извлекаем из БД данные
    $array = array();
    while($row = mysqli_fetch_assoc($result))
        $array[] = $row;
    return $array;
}

// Функция выполнения запроса к БД.
function sql_query($sql)
{
    // Выполнение запроса
    $result = mysqli_query(sql_connect(), $sql);

    if (!$result) {
        die(mysqli_error(sql_connect()));
    }
    return true;
}

// Функция экранирования спец. символов в sql запросе и удаление пробевол из начала и конца строки
function sql_escape($string)
{
    $result = mysqli_real_escape_string(sql_connect(), $string);
    return $result;
}

// Функция редиректа
function redirect($url)
{
    header("Location: $url");
    exit;
}

// В качестве аргумента используется ключ массива в сессии, в котором хранится сообщение об ошибке
function flashMessage($name)
{
    $storage = $_SESSION["$name"];  // сохранение в переменную
    unset($_SESSION["$name"]);      // удаление записи в массиве
    return $storage;
}

// Подключение шаблона.
function view_include($fileName, $vars = [])
{
    // Устанавливаем переменные
    foreach($vars as $key => $value)
        $$key = $value;

    // Генерация HTML в строку.
    ob_start();
    include $fileName;
    return ob_get_clean();
}



