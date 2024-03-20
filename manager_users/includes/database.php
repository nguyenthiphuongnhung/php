<!--cac ham lien quan den csdl-->
<?php
if (!defined('_CODE')) {
    die('Access denied...');
}
function query($sql, $data = [], $check = false)
{
    global $conn;
    $ketqua = false;
    try {
        $statement = $conn->prepare($sql);
        if (!empty($data)) {
            $ketqua = $statement->execute($data);
        } else {
            $ketqua = $statement->execute();
        }
    } catch (Exception $exp) {
        echo $exp->getMessage() . '<br>';
        echo 'File:' . $exp->getFile() . '<br>';
        echo 'Line:' . $exp->getLine();
        die();
    }
    if ($check) {
        return $statement;
    }
    return $ketqua;
}
function insert($table, $data)
{
    $key = array_keys($data);
    $fields = implode(',', $key);
    $values = ':' . implode(',:', $key);
    $sql = 'INSERT INTO ' . $table . ' (' . $fields . ') VALUES (' . $values . ')';
    $result = query($sql, $data); // Giả sử bạn có một hàm query để thực thi các truy vấn SQL
    return $result;
}

function update($table, $data, $condition = '')
{
    $update = '';
    foreach ($data as $key => $value) {
        $update .= $key . '= :' . $key . ',';
    }
    $update = trim($update, ',');
    if (!empty($condition)) {
        $sql = 'UPDATE ' . $table . ' SET ' . $update . ' WHERE ' . $condition;
    } else {
        $sql = 'UPDATE ' . $table . ' SET ' . $update;
    }
    $result = query($sql, $data); // Giả sử bạn có một hàm query để thực thi các truy vấn SQL
    return $result;
}

function delete($table, $condition = '')
{
    if (empty($condition)) {
        $sql = ' DELETE FROM ' . $table;
    } else {
        $sql = ' DELETE FROM ' . $table . ' WHERE ' . $condition;
    }
    $result = query($sql); // Giả sử bạn có một hàm query để thực thi các truy vấn SQL
    return $result;
}
//lay nhieu dong
function getRaw($sql)
{
    $result = query($sql,'',true);
    if(is_object($result)){
        $dataFetch = $result ->fetchAll(PDO::FETCH_ASSOC);
    }
    return$dataFetch;
}
//lay 1 dong
function oneRaw($sql)
{
    $result = query($sql,'',true);
    if(is_object($result)){
        $dataFetch = $result ->fetch(PDO::FETCH_ASSOC);
    }
    return$dataFetch;
}
//dem dong du lieu
function getRows($sql)
{
    $result = query($sql,'',true);
    if(!empty($result)){
       return $result -> rowCount();    }
   
}