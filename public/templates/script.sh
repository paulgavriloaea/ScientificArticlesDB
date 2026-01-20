#!/bin/bash/




sudo touch "$1".php

sudo chmod 666 "$1".php

cat > "$1.php" <<EOF

<?php

\$connect=mysqli_connect("localhost","root","","Blabla");

\$query=





?>

EOF



