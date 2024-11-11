<?php
session_start(); // Start en ny eller fortsett en eksisterende økt
session_unset(); // Fjern alle øktvariabler, nullstiller øktdataen
session_destroy(); // Avslutt økten helt, og slett øktdataen
header('Location: /RomBooking-System-/Views/Users/Login.php'); // Omdiriger brukeren til innloggingssiden
exit(); // Sørg for at skriptet stopper etter omdirigeringen, for å unngå at videre kode blir kjørt