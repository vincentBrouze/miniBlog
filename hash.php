<?php

if (isset($_GET['mdp']))
  echo "pass haché : ".sha1($_GET['mdp']);
else echo "Spécifier mdp";
?>