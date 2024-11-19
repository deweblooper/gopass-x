
Priama cesta pre zobrazenie tabuľky s výpisom všetkých čísel aj s dátumom pridania + export do "numbers.csv":
../wp-content/plugins/gopass-x/export-it.php?go=adc329df80ca9552f2d82126

Priama cesta pre aktuálny súbor "numbers.csv":
../wp-content/plugins/gopass-x/tmp/numbers.csv

Priama cesta pre export do "numbers.csv" a odoslanie emailom na info@gopass.sk:
../wp-content/plugins/gopass-x/export-it.php?go=0c83f57c786a0b4a39efab23

Pre odoslanie emailom sú možnosti:
- vstavaná funkcia php mail()
- class PHPMailer
nastavenia tela emailu a adries:
..\wp-content\plugins\gopass-x\export-it.php (pod PRIPRAVA HLAVICIEK A TELA EMAILU)
nastavenia pre odosielanie:
..\wp-content\plugins\gopass-x\class\mail.inc.php

Prípadná kontrola počtu odoslatých emailov:
../wp-content/plugins/gopass-x/tmp/_count.inc