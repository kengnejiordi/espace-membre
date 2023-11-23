<?php /* End Of File = EOF */

$bdd = new PDO(dsn: <<<'EOF'
mysql:dbname = membres; host=localhost
EOF
    , username: 'root', password: '');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
