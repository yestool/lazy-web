<?php


// 数据库文件路径
$dbPath = __DIR__ . '/database.sqlite';
echo "数据库文件路径:" . $dbPath . "\n";

// SQL 文件路径
$sqlPath = __DIR__ . '/init.sql';

try {
    // 创建 SQLite 数据库连接
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 读取 SQL 文件内容
    $sql = file_get_contents($sqlPath);

    // 执行 SQL 语句
    //$res = $db->exec($sql) ;

    $stmt = $db->query("SELECT * FROM users");
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "执行结果:" . json_encode($res) . "\n";

    echo "数据库初始化成功！\n";

} catch (PDOException $e) {
    echo "数据库初始化失败：" . $e->getMessage() . "\n";
}

?>