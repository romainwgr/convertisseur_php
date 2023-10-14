<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="conv.css">
    <title>Convertisseur</title>
</head>
<body>

<?php
$valdeci=null;
$valbinary=null;
$valhexa=null;
$erreur="";

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    // Récupération des données
    $deci = filter_input(INPUT_POST, 'déci', FILTER_VALIDATE_INT);
    $binaire = filter_input(INPUT_POST, 'binaire', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^[01]+$/"]]);
    $hexa = filter_input(INPUT_POST, 'hexa', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^[0-9A-Fa-f]+$/"]]);
    $val = [$deci, $binaire, $hexa];
    $erreur=false;
    $message='';
    //erreur d'entrée
    if(!$deci && !$binaire && !$hexa){
        $erreur=true;
        $message .="<p> Vous devez rentrer une valeur valide pour la convertir </p>";
        
    }

    

    // Gestion des conversions
    //fonctions
    function deci(){
        global $deci, $valdeci, $valbinary, $valhexa;
        $valdeci = $deci;
        $valbinary = decbin($deci);
        $valhexa = dechex($deci);
    }
    
    function binaire(){
        global $binaire, $valdeci, $valbinary, $valhexa;
        $valdeci = bindec($binaire);
        $valbinary = $binaire;
        $valhexa = base_convert($binaire, 2, 16);
    }
    
    function hexa(){
        global $hexa, $valdeci, $valbinary, $valhexa;
        $valdeci = hexdec($hexa);
        $valbinary = base_convert($hexa, 16, 2);
        $valhexa = $hexa;
    }
    function comparer(){
        global $deci, $binaire, $hexa;
        if(decbin($deci)===$binaire){
            hexa();
        }elseif(dechex($deci)===$hexa){
            binaire();
        }elseif(base_convert($binaire,2,16)===$hexa){
            deci();
        }
    }
    //fins des fonctions
    
    if(empty($deci)|| empty($binaire) || empty($hexa)){

    
        if (!empty($deci)) {
            deci();
        } elseif (!empty($binaire)) {
            binaire();
        } elseif (!empty($hexa)) {
            hexa();
        }
    }
    else{
        comparer();
    }
}
if(isset($_POST['effacer'])){
    $valdeci = null;
    $valbinary = null;
    $valhexa = null;
    $message = '';
}
?>
<div class='centre'>
    <h1>Convertisseur décimal hexadécimal et binaire</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <p>Décimal</p>
        <input type="text" name="déci" value="<?= $valdeci ?>">
        <p>Binaire</p>
        <input type="text" name="binaire" value="<?= $valbinary ?>">
        <p>Hexadécimal</p>
        <input type="text" name="hexa" value="<?= $valhexa ?>">
        <br><br>
        <input type="submit" name='convertir'value="Convertir">
        <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"><button type="submit" name="effacer">Effacer</button></a>
    </form>
    <p><?php if($erreur===true){echo $message;}?> </p>
</div>
</body>
</html>
