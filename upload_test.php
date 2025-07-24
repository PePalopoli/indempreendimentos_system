<?php
// Arquivo de teste para configurações de upload
echo "<h2>Configurações de Upload do PHP</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><td><strong>Configuração</strong></td><td><strong>Valor Atual</strong></td><td><strong>Recomendado</strong></td></tr>";

$configs = array(
    'max_file_uploads' => array('atual' => ini_get('max_file_uploads'), 'recomendado' => '50'),
    'upload_max_filesize' => array('atual' => ini_get('upload_max_filesize'), 'recomendado' => '10M'),
    'post_max_size' => array('atual' => ini_get('post_max_size'), 'recomendado' => '100M'),
    'memory_limit' => array('atual' => ini_get('memory_limit'), 'recomendado' => '256M'),
    'max_execution_time' => array('atual' => ini_get('max_execution_time'), 'recomendado' => '300'),
);

foreach ($configs as $config => $values) {
    $color = ($values['atual'] == $values['recomendado']) ? 'green' : 'red';
    echo "<tr>";
    echo "<td><strong>$config</strong></td>";
    echo "<td style='color: $color'>{$values['atual']}</td>";
    echo "<td>{$values['recomendado']}</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>Para corrigir no WAMP:</h3>";
echo "<ol>";
echo "<li>Clique no ícone do WAMP na bandeja</li>";
echo "<li>Vá em <strong>PHP</strong> → <strong>php.ini</strong></li>";
echo "<li>Procure e altere essas linhas:</li>";
echo "<ul>";
echo "<li><code>upload_max_filesize = 10M</code></li>";
echo "<li><code>post_max_size = 100M</code></li>";
echo "<li><code>max_file_uploads = 50</code></li>";
echo "</ul>";
echo "<li>Salve o arquivo</li>";
echo "<li>Reinicie o Apache pelo WAMP</li>";
echo "</ol>";

echo "<p><a href='upload_test.php'>Atualizar página</a> | <a href='javascript:history.back()'>Voltar</a></p>";
?> 