<?php
echo "<h2>Configurações PHP para Upload</h2>";
echo "<strong>upload_max_filesize:</strong> " . ini_get('upload_max_filesize') . "<br>";
echo "<strong>post_max_size:</strong> " . ini_get('post_max_size') . "<br>";
echo "<strong>memory_limit:</strong> " . ini_get('memory_limit') . "<br>";
echo "<strong>max_execution_time:</strong> " . ini_get('max_execution_time') . "<br>";
echo "<strong>max_file_uploads:</strong> " . ini_get('max_file_uploads') . "<br>";

// Conversão para bytes para facilitar comparação
function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }
    return $val;
}

echo "<br><h3>Em bytes:</h3>";
echo "<strong>upload_max_filesize:</strong> " . return_bytes(ini_get('upload_max_filesize')) . " bytes<br>";
echo "<strong>post_max_size:</strong> " . return_bytes(ini_get('post_max_size')) . " bytes<br>";
?> 