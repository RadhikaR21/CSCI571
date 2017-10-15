<html>  
    <head>        
<meta charset="UTF-8">
        <style>
        table.border {
    border-collapse: collapse;
            border: 1px solid black;
}
    </style>
    </head>
<body> 
<script>
              
</script>    
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" id="search">  
    <center>
    <table  bgcolor="#DCDCDC" width="1000px" height="200px">
<tr><td width="10px"></td><td  width="90px"></td><td  width="800px"><center><h1><i>Facebook Search</i></h1></center></td><td width="90px"></td><td width="10px"></td></tr>
<tr><td></td><td>
    Keyword:</td><td> <input type="text" name="keyword" id="q" value="<?php echo $_POST["keyword"] ?>" required></td><td></td><td></td>
        </tr>
        <tr><td></td><td>
    Type: </td>
    <td><select id="type" name="type" onchange="selectlocation()" value="<?php echo $_POST["type"]?>">
        <option value="user" <?php echo(isset($_POST['type']) && $_POST['type']=="user") ?'selected= "selected"':'';?>>Users</option>
        <option value="page" <?php echo(isset($_POST['type']) && $_POST['type']=="page") ?'selected= "selected"':'';?>>Pages</option>
        <option value="event" <?php echo(isset($_POST['type']) && $_POST['type']=="event") ?'selected= "selected"':'';?>>Events</option>
        <option value="place" <?php echo(isset($_POST['type']) && $_POST['type']=="place") ?'selected= "selected"':'';?>>Places</option>
        <option value="group" <?php echo(isset($_POST['type']) && $_POST['type']=="group") ?'selected= "selected"':'';?>>Groups</option>
        </select><br></td><td></td><td></td>
        </tr>
        <tr><td></td><td id="here1"></td><td id="here2"></td><td></td><td></td>
        </tr>
        <tr><td></td><td></td>
            <td>
<input type="submit" name="submit" value="Search" onclick="submitdata()">
<input type="button" onclick="resetForm()" value="Clear">
 </td>   <td></td><td></td></tr>
        </table>
        </center>
        </form>
        <script>    
function selectlocation() {
    var x = document.getElementById("type").value;
    
    if(x=="place"){
    document.getElementById("here1").innerHTML= "Location";
    document.getElementById("here2").innerHTML = "<input type='text' name='location' <?php echo $_POST["location"]?>>"+" Distance(meters) <input type='text' pattern='[0-9]+' name='distance'<?php  echo $_POST["distance"]?>>";
    }
    else
        {
    document.getElementById("here1").innerHTML= "";
    document.getElementById("here2").innerHTML = "";
    }    
}  
function submitdata(){
     
    q=document.getElementById("q").value;
    type=document.getElementById("type").value;
        document.getElementById("q").value=q;
    document.getElementById("type").value=type;
    
        
    if((document.getElementById("here1").style.display=='block')&&(document.getElementById("here1").style.display=='block'))
    
     {
    loc=document.getElementById("location").value;
     dist=document.getElementById("distance").value;
    
    document.getElementById("location").value=loc;
    document.getElementById("distance").value=dist;
     }
    
}
            function resetForm() {
      clear();
    document.getElementById("search").reset;
    document.getElementById("q").value=" ";
    document.getElementById("type").value="user";
    document.getElementById("location").value=" ";
    document.getElementById("distance").value=" ";
                
    
}   
  </script>   
    
    <?php 
    date_default_timezone_set('UTC');
if(isset($_POST["submit"])) {
$q=$_POST["keyword"];
   // echo $q;
$type = $_POST["type"];
   // echo $type;
$location=$_POST["location"];
   // echo $location;
$distance=$_POST["distance"];
   // echo $distance; 
    
    
    
switch ($type) {
        
case "place":{
      //  echo "Your type is:".$type;
     if(($location=='')&&($distance=$_POST["distance"]))
    {
        
    echo "<div id=\"main_table\">";
        echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                       echo "<tr>";
                       echo "<td style='text-align:center;'>";
                       echo "Distance specified without location or address";
                       echo "</td>";
                        echo "</tr>";
                        echo "</table>";
        echo "</div>";
       
    }
    else if(($location=$_POST["location"])&&($distance=$_POST["distance"])){
        
    {
        $loc=str_replace(' ','+',$location);
        $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$loc.'&sensor=false');
        $output= json_decode($geocode);
        $latitude = $output->results[0]->geometry->location->lat;
        $longitude = $output->results[0]->geometry->location->lng;	
      //  echo "latitude :".$latitude;
       // echo "longitude :".$longitude;
    }
    
    require_once __DIR__.'/facebook-sdk-v5/autoload.php';
    $fb = new Facebook\Facebook([
  'app_id' => '1810536372540952',
  'app_secret' => '811dad884b44dcaaea3dab3358d5252f',
  'default_graph_version' => 'v2.8',
]);    
$fb->setDefaultAccessToken('EAAZAurFlWlhgBAJmP9gBaAh1Y6giNkJ9lHuUewjandmgJ5K1xEqlvZAUX0r7f0coc0lcqMRkIT2ppb5BWaTN6IBtizC3klIIZAC9ZAk83QQxZBhmNLZCZCNOo5ZAuB1jZCLZBk96IMqTRAgtJSUsZAYjnLQz4hnZAUggsUIZD');
  

try { 
$placeresponse=$fb->get(
'search?q='.$q.'&type=place&center='.$latitude.','.$longitude.'&distance='.$distance.'&fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name,picture}},posts.limit(5)');    
}
 catch(Facebook\Exceptions\FacebookResponseException $e) {
  
  echo 'Graph returned an error: '. $e->getMessage();
  exit;
} 
    catch(Facebook\Exceptions\FacebookSDKException $e) {

  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}
    $graphObject = $placeresponse->getGraphEdge()->asArray();
         echo "<div id=\"main_table\">";
    $i=0;
    if($graphObject[$i]=='')
    {
            echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                       echo "<tr>";
                       echo "<td style='text-align:center;'>";
                       echo "No Records has been found";
                       echo "</td>";
                        echo "</tr>";
                        echo "</table>";
    }
     else
       {
echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px' id = \"pages_table\">";      
    echo "<th style='text-align:left;font-weight:bold'>";
    echo ProfilePhoto;
    echo "<td style='font-weight:bold'>";
    echo Name;
    echo "</td>";    
    echo "<td style='font-weight:bold'>";
    echo Details;
    echo "</td>";
    echo "</th>";    
    for ($i = 0; $i < count($graphObject); $i++)
    {   
        echo "<tr>";
        echo "<td>";
        echo "<a href='";
        echo $graphObject[$i]['picture']['url'];
        echo "' target='_blank'>";
        echo "<img height='30px' width='30px' src='";
        echo $graphObject[$i]['picture']['url'];
        echo "'/>"; 
        echo "</a>";         
        echo "</td>";
        echo "<td>";
        echo $graphObject[$i]['name'];
        echo "</td>";   
        echo "<td>";         
       echo "<a id='details' onclick='detailsLink(\"".$graphObject[$i]['id']."\")'  href=\"#\">Details </a>";
     echo "</td>";
     echo "</tr>";   
    }
    echo "</table>"; 
    
        for($i=0;$i<count($graphObject);$i++)
        {
                $a='albums';
                $albums=$graphObject[$i]['id'].$a;
               
                $aa='albumsheading';
                $albumsh=$graphObject[$i]['id'].$aa;
        
                if($graphObject[$i]['albums']=='')
                    {
                       
                        echo "<div id =\"".$albumsh."\" style = \"display :none\">";
                        
                        echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                       echo "<tr>";
                       echo "<td style='text-align:center;'>";
                       echo "No Albums has been found";
                       echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</div>";
                    
                    }
                    
                 else
                 {
    
    
     for($i=0;$i<count($graphObject);$i++)
        {
                $aa='albumsheading';
                $albumsh=$graphObject[$i]['id'].$aa;
                
                echo "<div id =\"".$albumsh."\" style = \"display :none\">";        
                echo "<table class='border' align='center'  bgcolor='#A9A9A9' width='1200px' height='10px'>"; 
                echo "<tr>";
                echo "<td style='text-align:center;'>";
                echo "<a id='albumclick' onclick='AlbumsLink(\"".$graphObject[$i]['id']."\")'  href=\"#\">Albums</a>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                echo "</div>";
        }
      echo "<br />\n";
            echo "<br />\n"; 
        for($i=0;$i<count($graphObject);$i++)
        {
                $a='albums';
                $albums=$graphObject[$i]['id'].$a;
                $ph='photos';
                $photos=$graphObject[$i]['id'].$ph;
        
               
                      {  
                        echo "<div id =\"".$albums."\" style = \"display :none\">";
                        
                        for($j=0;$j<5;$j++)
                        {
                        echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";
                            if($graphObject[$i]['albums'][$j]['name']!=''){
                        echo "<tr>";
                        echo "<td>";
                            if ($graphObject[$i]['albums'][$j]['photos']=='')
                            {
                                echo $graphObject[$i]['albums'][$j]['name'];
                            }else
                            {
                        echo "<a  onclick='albumpicturelink(\"".$j.$photos."\")' href=\"#\">";
                        echo $graphObject[$i]['albums'][$j]['name'];
                        echo  "</a>";
                            }
                        echo "</td>";   
                        echo "</tr>";    }
                        echo "</table>";
                       
                        echo "<div id =\"".$j.$photos."\" style = \"display :none\">";    
                            
                                                  
                          $k=0;
                            if ($graphObject[$i]['albums'][$j]['photos']=='')
                            {
                                echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                                if($graphObject[$i]['albums'][$j]['name']!=''){
                        echo "<tr>";
                        echo "<td>";
                        echo "<a  onclick='albumpicturelink(\"".$j.$photos."\")' href=\"#\">";
                        echo $graphObject[$i]['albums'][$j]['name'];
                        echo  "</a>";
                        echo "</td>";   
                        echo "</tr>"; 
                            }
                                echo "</table>";  
                            }    
                          else
                          {
                        $space=" ";
                        echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>"; 
                        echo "<tr>";   
                        echo "<td>";
                            
                         for($k=0;$k<2;$k++)  
                        {   if(($graphObject[$i]['albums'][$j]['photos'][$k]['picture'])!=''){
                            echo "<a href='";
                            echo $graphObject[$i]['albums'][$j]['photos'][$k]['picture'];
                            echo "' target='_blank'>";
                            echo "<img height='100px' width='100px' src='";
                            echo $graphObject[$i]['albums'][$j]['photos'][$k]['picture'];
                            echo "'/>"; 
                            echo "</a>"; 
                            echo $space;
                            echo $space;}
                        }
                        
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";  
                          }
                            echo "</div>";
                         }    
                        echo "</div>";
                     }         
        }
                 }
        }echo "<br />\n";
            
                     echo "<br />\n"; 
   for($i=0;$i<count($graphObject);$i++)
        {    $p='posts';   
             $posts=$graphObject[$i]['id'].$p;
             
                $pp='postsheading';
                $postsh=$graphObject[$i]['id'].$pp;               
            
               if($graphObject[$i]['posts']=='')
                    
                {       echo "<div id =\"".$postsh."\" style = \"display :none\">";
                        echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                        echo "<tr>";
                        echo "<td style='text-align:center;'>";
                        echo "No Posts has been found";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</div>";
                }
               else
            {    
                for($i=0;$i<count($graphObject);$i++)
        {
       $pp='postsheading';
                $postsh=$graphObject[$i]['id'].$pp; 
                echo "<div id =\"".$postsh."\" style = \"display :none\">";   
                echo "<table class='border' align='center'  bgcolor='#A9A9A9' width='1200px' height='10px'>"; 
                echo "<tr>";;
                echo "<td style='text-align:center;'>";                
                echo "<a id='postsclick' onclick='PostsLink(\"".$graphObject[$i]['id']."\")'  href=\"#\">Posts</a>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                echo "</div>";
        }
        echo "<br />\n";
            echo "<br />\n"; 
     for($i=0;$i<count($graphObject);$i++)
        {
           $p='posts';   
             $posts=$graphObject[$i]['id'].$p;
          
                {     
                      echo "<div id =\"".$posts."\" style = \"display :none\">";
                    $j=0;
                       if($graphObject[$i]['posts'][$j]['message']=='')
                       {
                            echo "<table align='center' bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                            echo "<tr>";
                            echo "<td style='text-align:center;'>";                           
                            echo "No Messages has been found";
                            echo "</td>";
                            echo "</tr>";
                            echo "</table>";
                       }
                       else
                       {    echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";  
                            echo "<tr>"; 
                           echo "<td>";
                           echo "<b>Message</b>";
                           echo "</td>";
                            echo "</tr>";
                               if($graphObject[$i]['posts'][$j]['message']!=''){
                              for($j=0;$j<5;$j++)
                                {    echo "<tr>";
                                    echo "<td>";
                                    echo $graphObject[$i]['posts'][$j]['message'];
                                    echo "</td>";
                                    echo "</tr>";  
                                }}
                           echo "</table>";
                      }
                    echo "</div>";
       
                }
        }
                 }
       
        }
     }
    echo "</div>";
    }
    else if(($location=='')&&($distance=='')){
         
        require_once __DIR__.'/facebook-sdk-v5/autoload.php';
    $fb = new Facebook\Facebook([
  'app_id' => '1810536372540952',
  'app_secret' => '811dad884b44dcaaea3dab3358d5252f',
  'default_graph_version' => 'v2.8',
]);
        
$fb->setDefaultAccessToken('EAAZAurFlWlhgBAJmP9gBaAh1Y6giNkJ9lHuUewjandmgJ5K1xEqlvZAUX0r7f0coc0lcqMRkIT2ppb5BWaTN6IBtizC3klIIZAC9ZAk83QQxZBhmNLZCZCNOo5ZAuB1jZCLZBk96IMqTRAgtJSUsZAYjnLQz4hnZAUggsUIZD');

     
try {
      
  $postresponse = $fb->get('search?q='.$q.'&type='.$type.'&fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name,picture}},posts.limit(5)');
   
}
 catch(Facebook\Exceptions\FacebookResponseException $e) {
  
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} 
    catch(Facebook\Exceptions\FacebookSDKException $e) {

  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
} 
    $graphObject = $postresponse->getGraphEdge()->asArray();
         echo "<div id=\"main_table\">";
      $i=0;
    if($graphObject[$i]=='')
    {
            echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                       echo "<tr>";
                       echo "<td style='text-align:center;'>";
                       echo "No Records has been found";
                       echo "</td>";
                        echo "</tr>";
                        echo "</table>";
    }
      
     else
     {
echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px' id = \"pages_table\">";      
    echo "<th style='text-align:left;font-weight:bold'>";
    echo ProfilePhoto;
    echo "<td style='font-weight:bold'>";
    echo Name;
    echo "</td>";    
    echo "<td style='font-weight:bold'>";
    echo Details;
    echo "</td>";
    echo "</th>";    
    for ($i = 0; $i < count($graphObject); $i++)
    {   
        echo "<tr>";
        echo "<td>";
        echo "<a href='";
        echo $graphObject[$i]['picture']['url'];
        echo "' target='_blank'>";
        echo "<img height='30px' width='30px' src='";
        echo $graphObject[$i]['picture']['url'];
        echo "'/>"; 
        echo "</a>";         
        echo "</td>";
        echo "<td>";
        echo $graphObject[$i]['name'];
        echo "</td>";   
        echo "<td>";         
       echo "<a id='details' onclick='detailsLink(\"".$graphObject[$i]['id']."\")'  href=\"#\">Details </a>";
     echo "</td>";
     echo "</tr>";   
    }
    echo "</table>"; 
         
        for($i=0;$i<count($graphObject);$i++)
        {
                $a='albums';
                $albums=$graphObject[$i]['id'].$a;
               
                $aa='albumsheading';
                $albumsh=$graphObject[$i]['id'].$aa;
        
                if($graphObject[$i]['albums']=='')
                    {
                       
                        echo "<div id =\"".$albumsh."\" style = \"display :none\">";
                        
                        echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                       echo "<tr>";
                       echo "<td style='text-align:center;'>";
                       echo "No Albums has been found";
                       echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</div>";
                    
                    }
                    
                 else
                 {
    
    
     for($i=0;$i<count($graphObject);$i++)
        {
                $aa='albumsheading';
                $albumsh=$graphObject[$i]['id'].$aa;
                
                echo "<div id =\"".$albumsh."\" style = \"display :none\">";        
                echo "<table class='border' align='center'  bgcolor='#A9A9A9' width='1200px' height='10px'>"; 
                echo "<tr>";
                echo "<td style='text-align:center;'>";
                echo "<a id='albumclick' onclick='AlbumsLink(\"".$graphObject[$i]['id']."\")'  href=\"#\">Albums</a>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                echo "</div>";
        }
      echo "<br />\n";
            echo "<br />\n"; 
        for($i=0;$i<count($graphObject);$i++)
        {
                $a='albums';
                $albums=$graphObject[$i]['id'].$a;
                $ph='photos';
                $photos=$graphObject[$i]['id'].$ph;
        
               
                      {  
                        echo "<div id =\"".$albums."\" style = \"display :none\">";
                        
                        for($j=0;$j<5;$j++)
                        {
                        echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";
                            if($graphObject[$i]['albums'][$j]['name']!=''){
                        echo "<tr>";
                        echo "<td>";
                            if ($graphObject[$i]['albums'][$j]['photos']=='')
                            {
                                echo $graphObject[$i]['albums'][$j]['name'];
                            }else
                            {
                        echo "<a  onclick='albumpicturelink(\"".$j.$photos."\")' href=\"#\">";
                        echo $graphObject[$i]['albums'][$j]['name'];
                        echo  "</a>";
                            }
                        echo "</td>";   
                        echo "</tr>";    }
                        echo "</table>";
                       
                        echo "<div id =\"".$j.$photos."\" style = \"display :none\">";    
                            
                                                  
                          $k=0;
                            if ($graphObject[$i]['albums'][$j]['photos']=='')
                            {
                                echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                                if($graphObject[$i]['albums'][$j]['name']!=''){
                        echo "<tr>";
                        echo "<td>";
                        echo "<a  onclick='albumpicturelink(\"".$j.$photos."\")' href=\"#\">";
                        echo $graphObject[$i]['albums'][$j]['name'];
                        echo  "</a>";
                        echo "</td>";   
                        echo "</tr>"; 
                            }
                                echo "</table>";  
                            }    
                          else
                          {
                        $space=" ";
                        echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>"; 
                        echo "<tr>";   
                        echo "<td>";
                            
                         for($k=0;$k<2;$k++)  
                        {   if(($graphObject[$i]['albums'][$j]['photos'][$k]['picture'])!=''){
                            echo "<a href='";
                            echo $graphObject[$i]['albums'][$j]['photos'][$k]['picture'];
                            echo "' target='_blank'>";
                            echo "<img height='100px' width='100px' src='";
                            echo $graphObject[$i]['albums'][$j]['photos'][$k]['picture'];
                            echo "'/>"; 
                            echo "</a>"; 
                            echo $space;
                            echo $space;}
                        }
                        
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";  
                          }
                            echo "</div>";
                         }    
                        echo "</div>";
                     }         
        }
                 }
        }echo "<br />\n";
            
                     echo "<br />\n"; 
   for($i=0;$i<count($graphObject);$i++)
        {    $p='posts';   
             $posts=$graphObject[$i]['id'].$p;
             
                $pp='postsheading';
                $postsh=$graphObject[$i]['id'].$pp;               
            
               if($graphObject[$i]['posts']=='')
                    
                {       echo "<div id =\"".$postsh."\" style = \"display :none\">";
                        echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                        echo "<tr>";
                        echo "<td style='text-align:center;'>";
                        echo "No Posts has been found";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</div>";
                }
               else
            {    
                for($i=0;$i<count($graphObject);$i++)
        {
       $pp='postsheading';
                $postsh=$graphObject[$i]['id'].$pp; 
                echo "<div id =\"".$postsh."\" style = \"display :none\">";   
                echo "<table class='border' align='center'  bgcolor='#A9A9A9' width='1200px' height='10px'>"; 
                echo "<tr>";;
                echo "<td style='text-align:center;'>";                
                echo "<a id='postsclick' onclick='PostsLink(\"".$graphObject[$i]['id']."\")'  href=\"#\">Posts</a>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                echo "</div>";
        }
        echo "<br />\n";
            echo "<br />\n"; 
     for($i=0;$i<count($graphObject);$i++)
        {
           $p='posts';   
             $posts=$graphObject[$i]['id'].$p;
          
                {     
                      echo "<div id =\"".$posts."\" style = \"display :none\">";
                    $j=0;
                       if($graphObject[$i]['posts'][$j]['message']=='')
                       {
                            echo "<table align='center' bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                            echo "<tr>";
                            echo "<td style='text-align:center;'>";                           
                            echo "No Messages has been found";
                            echo "</td>";
                            echo "</tr>";
                            echo "</table>";
                       }
                       else
                       {    echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";  
                            echo "<tr>"; 
                           echo "<td>";
                           echo "<b>Message</b>";
                           echo "</td>";
                            echo "</tr>";
                               if($graphObject[$i]['posts'][$j]['message']!=''){
                              for($j=0;$j<5;$j++)
                                {    echo "<tr>";
                                    echo "<td>";
                                    echo $graphObject[$i]['posts'][$j]['message'];
                                    echo "</td>";
                                    echo "</tr>";  
                                }}
                           echo "</table>";
                      }
                    echo "</div>";
       
                }
        }
                 }
       
        }
     }
        echo "</div>";

        }
    
    else if(($location=$_POST["location"])&&($distance=='')){
        
    {
        $locat=urlencode($location);
        $geocodee=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$locat);
        $output= json_decode($geocodee,true);
        if($output['status']!='OK')
         {echo "<div id=\"main_table\">";
        echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                       echo "<tr>";
                       echo "<td style='text-align:center;'>";
                       echo "Address is invalid";
                       echo "</td>";
                        echo "</tr>";
                        echo "</table>";
        echo "</div>";
             exit(0);
         }
     
        
        $latitude = $output->results[0]->geometry->location->lat;
        $longitude = $output->results[0]->geometry->location->lng;
       
      //  echo "latitude :".$latitude;
       // echo "longitude :".$longitude;
    }
    
    require_once __DIR__.'/facebook-sdk-v5/autoload.php';
    $fb = new Facebook\Facebook([
  'app_id' => '1810536372540952',
  'app_secret' => '811dad884b44dcaaea3dab3358d5252f',
  'default_graph_version' => 'v2.8',
]);    
$fb->setDefaultAccessToken('EAAZAurFlWlhgBAJmP9gBaAh1Y6giNkJ9lHuUewjandmgJ5K1xEqlvZAUX0r7f0coc0lcqMRkIT2ppb5BWaTN6IBtizC3klIIZAC9ZAk83QQxZBhmNLZCZCNOo5ZAuB1jZCLZBk96IMqTRAgtJSUsZAYjnLQz4hnZAUggsUIZD');
  

try { 
$placeresponse=$fb->get(
'search?q='.$q.'&type='.$type.'&fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name,picture}},posts.limit(5)');    
}
 catch(Facebook\Exceptions\FacebookResponseException $e) {
  
  echo 'Graph returned an error: '. $e->getMessage();
  exit;
} 
    catch(Facebook\Exceptions\FacebookSDKException $e) {

  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}
    $graphObject=$placeresponse->getGraphEdge()->asArray();
         echo "<div id=\"main_table\">";
         $i=0;
    if($graphObject[$i]=='')
    {
            echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                       echo "<tr>";
                       echo "<td style='text-align:center;'>";
                       echo "No Records has been found";
                       echo "</td>";
                        echo "</tr>";
                        echo "</table>";
    }
     else
       {
echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px' id = \"pages_table\">";      
    echo "<th style='text-align:left;font-weight:bold'>";
    echo ProfilePhoto;
    echo "<td style='font-weight:bold'>";
    echo Name;
    echo "</td>";    
    echo "<td style='font-weight:bold'>";
    echo Details;
    echo "</td>";
    echo "</th>";    
    for ($i = 0; $i < count($graphObject); $i++)
    {   
        echo "<tr>";
        echo "<td>";
        echo "<a href='";
        echo $graphObject[$i]['picture']['url'];
        echo "' target='_blank'>";
        echo "<img height='30px' width='30px' src='";
        echo $graphObject[$i]['picture']['url'];
        echo "'/>"; 
        echo "</a>";         
        echo "</td>";
        echo "<td>";
        echo $graphObject[$i]['name'];
        echo "</td>";   
        echo "<td>";         
       echo "<a id='details' onclick='detailsLink(\"".$graphObject[$i]['id']."\")'  href=\"#\">Details </a>";
     echo "</td>";
     echo "</tr>";   
    }
    echo "</table>"; 
    
        for($i=0;$i<count($graphObject);$i++)
        {
                $a='albums';
                $albums=$graphObject[$i]['id'].$a;
               
                $aa='albumsheading';
                $albumsh=$graphObject[$i]['id'].$aa;
        
                if($graphObject[$i]['albums']=='')
                    {
                       
                        echo "<div id =\"".$albumsh."\" style = \"display :none\">";
                        
                        echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                       echo "<tr>";
                       echo "<td style='text-align:center;'>";
                       echo "No Albums has been found";
                       echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</div>";
                    
                    }
                    
                 else if($graphObject[$i]['albums']!='')
                 {
    
    
     for($i=0;$i<count($graphObject);$i++)
        {
                $aa='albumsheading';
                $albumsh=$graphObject[$i]['id'].$aa;
                
                echo "<div id =\"".$albumsh."\" style = \"display :none\">";        
                echo "<table class='border' align='center'  bgcolor='#A9A9A9' width='1200px' height='10px'>"; 
                echo "<tr>";
                echo "<td style='text-align:center;'>";
                echo "<a id='albumclick' onclick='AlbumsLink(\"".$graphObject[$i]['id']."\")'  href=\"#\">Albums</a>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                echo "</div>";
        }
      echo "<br />\n";
            echo "<br />\n"; 
        for($i=0;$i<count($graphObject);$i++)
        {
                $a='albums';
                $albums=$graphObject[$i]['id'].$a;
                $ph='photos';
                $photos=$graphObject[$i]['id'].$ph;
        
               
                      {  
                        echo "<div id =\"".$albums."\" style = \"display :none\">";
                        
                        for($j=0;$j<5;$j++)
                        {
                        echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";
                            if($graphObject[$i]['albums'][$j]['name']!=''){
                        echo "<tr>";
                        echo "<td>";
                            if ($graphObject[$i]['albums'][$j]['photos']=='')
                            {
                                echo $graphObject[$i]['albums'][$j]['name'];
                            }else
                            {
                        echo "<a  onclick='albumpicturelink(\"".$j.$photos."\")' href=\"#\">";
                        echo $graphObject[$i]['albums'][$j]['name'];
                        echo  "</a>";
                            }
                        echo "</td>";   
                        echo "</tr>";    }
                        echo "</table>";
                       
                        echo "<div id =\"".$j.$photos."\" style = \"display :none\">";    
                            
                                                  
                          $k=0;
                            if ($graphObject[$i]['albums'][$j]['photos']=='')
                            {
                                echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                                if($graphObject[$i]['albums'][$j]['name']!=''){
                        echo "<tr>";
                        echo "<td>";
                        echo "<a  onclick='albumpicturelink(\"".$j.$photos."\")' href=\"#\">";
                        echo $graphObject[$i]['albums'][$j]['name'];
                        echo  "</a>";
                        echo "</td>";   
                        echo "</tr>"; 
                            }
                                echo "</table>";  
                            }    
                          else
                          {
                        $space=" ";
                        echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>"; 
                        echo "<tr>";   
                        echo "<td>";
                            
                         for($k=0;$k<2;$k++)  
                        {   if(($graphObject[$i]['albums'][$j]['photos'][$k]['picture'])!=''){
                            echo "<a href='";
                            echo $graphObject[$i]['albums'][$j]['photos'][$k]['picture'];
                            echo "' target='_blank'>";
                            echo "<img height='100px' width='100px' src='";
                            echo $graphObject[$i]['albums'][$j]['photos'][$k]['picture'];
                            echo "'/>"; 
                            echo "</a>"; 
                            echo $space;
                            echo $space;}
                        }
                        
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";  
                          }
                            echo "</div>";
                         }    
                        echo "</div>";
                     }         
        }
                 }
        }echo "<br />\n";
            
                     echo "<br />\n"; 
   for($i=0;$i<count($graphObject);$i++)
        {    $p='posts';   
             $posts=$graphObject[$i]['id'].$p;
             
                $pp='postsheading';
                $postsh=$graphObject[$i]['id'].$pp;               
            
               if($graphObject[$i]['posts']=='')
                    
                {       echo "<div id =\"".$postsh."\" style = \"display :none\">";
                        echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                        echo "<tr>";
                        echo "<td style='text-align:center;'>";
                        echo "No Posts has been found";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</div>";
                }
               else
            {    
                for($i=0;$i<count($graphObject);$i++)
        {
       $pp='postsheading';
                $postsh=$graphObject[$i]['id'].$pp; 
                echo "<div id =\"".$postsh."\" style = \"display :none\">";   
                echo "<table class='border' align='center'  bgcolor='#A9A9A9' width='1200px' height='10px'>"; 
                echo "<tr>";;
                echo "<td style='text-align:center;'>";                
                echo "<a id='postsclick' onclick='PostsLink(\"".$graphObject[$i]['id']."\")'  href=\"#\">Posts</a>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                echo "</div>";
        }
        echo "<br />\n";
            echo "<br />\n"; 
     for($i=0;$i<count($graphObject);$i++)
        {
           $p='posts';   
             $posts=$graphObject[$i]['id'].$p;
          
                {     
                      echo "<div id =\"".$posts."\" style = \"display :none\">";
                    $j=0;
                       if($graphObject[$i]['posts'][$j]['message']=='')
                       {
                            echo "<table align='center' bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                            echo "<tr>";
                            echo "<td style='text-align:center;'>";                           
                            echo "No Messages has been found";
                            echo "</td>";
                            echo "</tr>";
                            echo "</table>";
                       }
                       else
                       {    echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";  
                            echo "<tr>"; 
                           echo "<td>";
                           echo "<b>Message</b>";
                           echo "</td>";
                            echo "</tr>";
                               if($graphObject[$i]['posts'][$j]['message']!=''){
                              for($j=0;$j<5;$j++)
                                {    echo "<tr>";
                                    echo "<td>";
                                    echo $graphObject[$i]['posts'][$j]['message'];
                                    echo "</td>";
                                    echo "</tr>";  
                                }}
                           echo "</table>";
                      }
                    echo "</div>";
       
                }
        }
                 }
       
        }
     }
        echo "</div>";
    }
    break;
   
}
case "event":{
     //   echo "Your type is:".$type;
        
        require_once __DIR__.'/facebook-sdk-v5/autoload.php';
    $fb = new Facebook\Facebook([
  'app_id' => '1810536372540952',
  'app_secret' => '811dad884b44dcaaea3dab3358d5252f',
  'default_graph_version' => 'v2.8',
]);
        
$fb->setDefaultAccessToken('EAAZAurFlWlhgBAJmP9gBaAh1Y6giNkJ9lHuUewjandmgJ5K1xEqlvZAUX0r7f0coc0lcqMRkIT2ppb5BWaTN6IBtizC3klIIZAC9ZAk83QQxZBhmNLZCZCNOo5ZAuB1jZCLZBk96IMqTRAgtJSUsZAYjnLQz4hnZAUggsUIZD');

    try {
 $eventresponse=$fb->get('search?q='.$q.'&type='.$type.'&fields=id,name,picture.width(700).height(700),place');
   
} 
    catch(Facebook\Exceptions\FacebookResponseException $e) {
  
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} 
    catch(Facebook\Exceptions\FacebookSDKException $e) {

  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}
$graphObjectEvent=$eventresponse->getGraphedge()->asArray();
    
  //echo '<pre>' . print_r( $graphObjectEvent, 1 );  
     echo "<div id=\"main_table\">";
    $y=0;
    if($graphObjectEvent[$y]=='')
    {
            echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                       echo "<tr>";
                       echo "<td style='text-align:center;'>";
                       echo "No Records has been found";
                       echo "</td>";
                        echo "</tr>";
                        echo "</table>";
    }
     else
     {     
   echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";    
    echo "<th style='font-weight:bold;text-align:center'>";
    echo ProfilePhoto;
    echo "<td style='font-weight:bold'>";
    echo Name;
    echo "</td>";
    echo "<td style='font-weight:bold'>";
    echo Place;
    echo "</td>";
    echo "</th>";    
    for ($y = 0; $y < count($graphObjectEvent); $y++)
    {   
        echo "<tr>";
        echo "<td>";
        echo "<a href='";
        echo $graphObjectEvent[$y]['picture']['url'];
        echo "' target='_blank'>";
        echo "<img height='30px' width='30px' src='";
        echo $graphObjectEvent[$y]['picture']['url'];
        echo "'/>"; 
        echo "</a>";         
        echo "</td>";
        echo "<td>";
        echo $graphObjectEvent[$y]['name'];
        echo "</td>";   
        echo "<td>";         
       echo $graphObjectEvent[$y]['place']['name'];
        echo "</td>";
     echo "</tr>";   
    }
    echo "</table>"; 
     }
    break;
    echo "</div>";
}

case "user":
        {   // echo "Your type is:".$type;
        
  require_once __DIR__.'/facebook-sdk-v5/autoload.php';
    $fb = new Facebook\Facebook([
  'app_id' => '1810536372540952',
  'app_secret' => '811dad884b44dcaaea3dab3358d5252f',
  'default_graph_version' => 'v2.8',
]);
        
$fb->setDefaultAccessToken('EAAZAurFlWlhgBAJmP9gBaAh1Y6giNkJ9lHuUewjandmgJ5K1xEqlvZAUX0r7f0coc0lcqMRkIT2ppb5BWaTN6IBtizC3klIIZAC9ZAk83QQxZBhmNLZCZCNOo5ZAuB1jZCLZBk96IMqTRAgtJSUsZAYjnLQz4hnZAUggsUIZD');

try {
      
  $postresponse = $fb->get('search?q='.$q.'&type='.$type.'&fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name,picture}},posts.limit(5)');
   
}
 catch(Facebook\Exceptions\FacebookResponseException $e) {
  
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} 
    catch(Facebook\Exceptions\FacebookSDKException $e) {

  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
} 
    $graphObject = $postresponse->getGraphEdge()->asArray();
     
    $i=0;
    echo "<div id=\"main_table\">";
    if($graphObject[$i]=='')
    {
            echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                       echo "<tr>";
                       echo "<td style='text-align:center;'>";
                       echo "No Records has been found";
                       echo "</td>";
                        echo "</tr>";
                        echo "</table>";
    }
     else
      {
echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px' id = \"pages_table\">";      
    echo "<th style='text-align:left;font-weight:bold'>";
    echo ProfilePhoto;
    echo "<td style='font-weight:bold'>";
    echo Name;
    echo "</td>";    
    echo "<td style='font-weight:bold'>";
    echo Details;
    echo "</td>";
    echo "</th>";    
    for ($i = 0; $i < count($graphObject); $i++)
    {   
        echo "<tr>";
        echo "<td>";
        echo "<a href='";
        echo $graphObject[$i]['picture']['url'];
        echo "' target='_blank'>";
        echo "<img height='30px' width='30px' src='";
        echo $graphObject[$i]['picture']['url'];
        echo "'/>"; 
        echo "</a>";         
        echo "</td>";
        echo "<td>";
        echo $graphObject[$i]['name'];
        echo "</td>";   
        echo "<td>";         
       echo "<a id='details' onclick='detailsLink(\"".$graphObject[$i]['id']."\")'  href=\"#\">Details </a>";
     echo "</td>";
     echo "</tr>";   
    }
    echo "</table>"; 
    
        for($i=0;$i<count($graphObject);$i++)
        {
                $a='albums';
                $albums=$graphObject[$i]['id'].$a;
               
                $aa='albumsheading';
                $albumsh=$graphObject[$i]['id'].$aa;
        
                if($graphObject[$i]['albums']=='')
                    {
                       
                        echo "<div id =\"".$albumsh."\" style = \"display :none\">";
                        
                        echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                       echo "<tr>";
                       echo "<td style='text-align:center;'>";
                       echo "No Albums has been found";
                       echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</div>";
                    
                    }
                    
                 else if($graphObject[$i]['albums']!='')
                 {
    
    
     for($i=0;$i<count($graphObject);$i++)
        {
                $aa='albumsheading';
                $albumsh=$graphObject[$i]['id'].$aa;
                
                echo "<div id =\"".$albumsh."\" style = \"display :none\">";        
                echo "<table class='border' align='center'  bgcolor='#A9A9A9' width='1200px' height='10px'>"; 
                echo "<tr>";
                echo "<td style='text-align:center;'>";
                echo "<a id='albumclick' onclick='AlbumsLink(\"".$graphObject[$i]['id']."\")'  href=\"#\">Albums</a>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                echo "</div>";
        }
      echo "<br />\n";
            echo "<br />\n"; 
        for($i=0;$i<count($graphObject);$i++)
        {
                $a='albums';
                $albums=$graphObject[$i]['id'].$a;
                $ph='photos';
                $photos=$graphObject[$i]['id'].$ph;
        
               
                      {  
                        echo "<div id =\"".$albums."\" style = \"display :none\">";
                        
                        for($j=0;$j<5;$j++)
                        {
                        echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";
                            if($graphObject[$i]['albums'][$j]['name']!=''){
                        echo "<tr>";
                        echo "<td>";
                            if ($graphObject[$i]['albums'][$j]['photos']=='')
                            {
                                echo $graphObject[$i]['albums'][$j]['name'];
                            }else
                            {
                        echo "<a  onclick='albumpicturelink(\"".$j.$photos."\")' href=\"#\">";
                        echo $graphObject[$i]['albums'][$j]['name'];
                        echo  "</a>";
                            }
                        echo "</td>";   
                        echo "</tr>";    }
                        echo "</table>";
                       
                        echo "<div id =\"".$j.$photos."\" style = \"display :none\">";    
                            
                                                  
                          $k=0;
                            if ($graphObject[$i]['albums'][$j]['photos']=='')
                            {
                                echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                                if($graphObject[$i]['albums'][$j]['name']!=''){
                        echo "<tr>";
                        echo "<td>";
                        echo "<a  onclick='albumpicturelink(\"".$j.$photos."\")' href=\"#\">";
                        echo $graphObject[$i]['albums'][$j]['name'];
                        echo  "</a>";
                        echo "</td>";   
                        echo "</tr>"; 
                            }
                                echo "</table>";  
                            }    
                          else
                          {
                        $space=" ";
                        echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>"; 
                        echo "<tr>";   
                        echo "<td>";
                            
                         for($k=0;$k<2;$k++)  
                        {   if(($graphObject[$i]['albums'][$j]['photos'][$k]['picture'])!=''){
                            echo "<a href='";
                            echo $graphObject[$i]['albums'][$j]['photos'][$k]['picture'];
                            echo "' target='_blank'>";
                            echo "<img height='100px' width='100px' src='";
                            echo $graphObject[$i]['albums'][$j]['photos'][$k]['picture'];
                            echo "'/>"; 
                            echo "</a>"; 
                            echo $space;
                            echo $space;}
                        }
                        
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";  
                          }
                            echo "</div>";
                         }    
                        echo "</div>";
                     }         
        }
                 }
        }echo "<br />\n";
            
                     echo "<br />\n"; 
   for($i=0;$i<count($graphObject);$i++)
        {    $p='posts';   
             $posts=$graphObject[$i]['id'].$p;
             
                $pp='postsheading';
                $postsh=$graphObject[$i]['id'].$pp;               
            
               if($graphObject[$i]['posts']=='')
                    
                {       echo "<div id =\"".$postsh."\" style = \"display :none\">";
                        echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                        echo "<tr>";
                        echo "<td style='text-align:center;'>";
                        echo "No Posts has been found";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</div>";
                }
               else
            {    
                for($i=0;$i<count($graphObject);$i++)
        {
       $pp='postsheading';
                $postsh=$graphObject[$i]['id'].$pp; 
                echo "<div id =\"".$postsh."\" style = \"display :none\">";   
                echo "<table class='border' align='center'  bgcolor='#A9A9A9' width='1200px' height='10px'>"; 
                echo "<tr>";;
                echo "<td style='text-align:center;'>";                
                echo "<a id='postsclick' onclick='PostsLink(\"".$graphObject[$i]['id']."\")'  href=\"#\">Posts</a>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                echo "</div>";
        }
        echo "<br />\n";
            echo "<br />\n"; 
     for($i=0;$i<count($graphObject);$i++)
        {
           $p='posts';   
             $posts=$graphObject[$i]['id'].$p;
          
                {     
                      echo "<div id =\"".$posts."\" style = \"display :none\">";
                    $j=0;
                       if($graphObject[$i]['posts'][$j]['message']=='')
                       {
                            echo "<table align='center' bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                            echo "<tr>";
                            echo "<td style='text-align:center;'>";                           
                            echo "No Messages has been found";
                            echo "</td>";
                            echo "</tr>";
                            echo "</table>";
                       }
                       else
                       {    echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";  
                            echo "<tr>"; 
                           echo "<td>";
                           echo "<b>Message</b>";
                           echo "</td>";
                            echo "</tr>";
                               if($graphObject[$i]['posts'][$j]['message']!=''){
                              for($j=0;$j<5;$j++)
                                {    echo "<tr>";
                                    echo "<td>";
                                    echo $graphObject[$i]['posts'][$j]['message'];
                                    echo "</td>";
                                    echo "</tr>";  
                                }}
                           echo "</table>";
                      }
                    echo "</div>";
       
                }
        }
                 }
       
        }
     }
break;
    echo "</div>";
        }
    case "group":
 {   // echo "Your type is:".$type;
        
  require_once __DIR__.'/facebook-sdk-v5/autoload.php';
    $fb = new Facebook\Facebook([
  'app_id' => '1810536372540952',
  'app_secret' => '811dad884b44dcaaea3dab3358d5252f',
  'default_graph_version' => 'v2.8',
]);
        
$fb->setDefaultAccessToken('EAAZAurFlWlhgBAJmP9gBaAh1Y6giNkJ9lHuUewjandmgJ5K1xEqlvZAUX0r7f0coc0lcqMRkIT2ppb5BWaTN6IBtizC3klIIZAC9ZAk83QQxZBhmNLZCZCNOo5ZAuB1jZCLZBk96IMqTRAgtJSUsZAYjnLQz4hnZAUggsUIZD');

try {
      
  $postresponse = $fb->get('search?q='.$q.'&type='.$type.'&fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name,picture}},posts.limit(5)');
   
}
 catch(Facebook\Exceptions\FacebookResponseException $e) {
  
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} 
    catch(Facebook\Exceptions\FacebookSDKException $e) {

  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
} 
    $graphObject = $postresponse->getGraphEdge()->asArray();
     
    $i=0;
    echo "<div id=\"main_table\">";
    if($graphObject[$i]=='')
    {
            echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                       echo "<tr>";
                       echo "<td style='text-align:center;'>";
                       echo "No Records has been found";
                       echo "</td>";
                        echo "</tr>";
                        echo "</table>";
    }
     else
  {
echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px' id = \"pages_table\">";      
    echo "<th style='text-align:left;font-weight:bold'>";
    echo ProfilePhoto;
    echo "<td style='font-weight:bold'>";
    echo Name;
    echo "</td>";    
    echo "<td style='font-weight:bold'>";
    echo Details;
    echo "</td>";
    echo "</th>";    
    for ($i = 0; $i < count($graphObject); $i++)
    {   
        echo "<tr>";
        echo "<td>";
        echo "<a href='";
        echo $graphObject[$i]['picture']['url'];
        echo "' target='_blank'>";
        echo "<img height='30px' width='30px' src='";
        echo $graphObject[$i]['picture']['url'];
        echo "'/>"; 
        echo "</a>";         
        echo "</td>";
        echo "<td>";
        echo $graphObject[$i]['name'];
        echo "</td>";   
        echo "<td>";         
       echo "<a id='details' onclick='detailsLink(\"".$graphObject[$i]['id']."\")'  href=\"#\">Details </a>";
     echo "</td>";
     echo "</tr>";   
    }
    echo "</table>"; 
        for($i=0;$i<count($graphObject);$i++)
        {
                $a='albums';
                $albums=$graphObject[$i]['id'].$a;
               
                $aa='albumsheading';
                $albumsh=$graphObject[$i]['id'].$aa;
        
                if($graphObject[$i]['albums']=='')
                    {
                       
                        echo "<div id =\"".$albumsh."\" style = \"display :none\">";
                        
                        echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                       echo "<tr>";
                       echo "<td style='text-align:center;'>";
                       echo "No Albums has been found";
                       echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</div>";
                    
                    }
                    
                 else if($graphObject[$i]['albums']!='')
                 {
    
    
     for($i=0;$i<count($graphObject);$i++)
        {
                $aa='albumsheading';
                $albumsh=$graphObject[$i]['id'].$aa;
                
                echo "<div id =\"".$albumsh."\" style = \"display :none\">";        
                echo "<table class='border' align='center'  bgcolor='#A9A9A9' width='1200px' height='10px'>"; 
                echo "<tr>";
                echo "<td style='text-align:center;'>";
                echo "<a id='albumclick' onclick='AlbumsLink(\"".$graphObject[$i]['id']."\")'  href=\"#\">Albums</a>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                echo "</div>";
        }
      echo "<br />\n";
            echo "<br />\n"; 
        for($i=0;$i<count($graphObject);$i++)
        {
                $a='albums';
                $albums=$graphObject[$i]['id'].$a;
                $ph='photos';
                $photos=$graphObject[$i]['id'].$ph;
        
               
                      {  
                        echo "<div id =\"".$albums."\" style = \"display :none\">";
                        
                        for($j=0;$j<5;$j++)
                        {
                        echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";
                            if($graphObject[$i]['albums'][$j]['name']!=''){
                        echo "<tr>";
                        echo "<td>";
                            if ($graphObject[$i]['albums'][$j]['photos']=='')
                            {
                                echo $graphObject[$i]['albums'][$j]['name'];
                            }else
                            {
                        echo "<a  onclick='albumpicturelink(\"".$j.$photos."\")' href=\"#\">";
                        echo $graphObject[$i]['albums'][$j]['name'];
                        echo  "</a>";
                            }
                        echo "</td>";   
                        echo "</tr>";    }
                        echo "</table>";
                       
                        echo "<div id =\"".$j.$photos."\" style = \"display :none\">";    
                            
                                                  
                          $k=0;
                            if ($graphObject[$i]['albums'][$j]['photos']=='')
                            {
                                echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                                if($graphObject[$i]['albums'][$j]['name']!=''){
                        echo "<tr>";
                        echo "<td>";
                        echo "<a  onclick='albumpicturelink(\"".$j.$photos."\")' href=\"#\">";
                        echo $graphObject[$i]['albums'][$j]['name'];
                        echo  "</a>";
                        echo "</td>";   
                        echo "</tr>"; 
                            }
                                echo "</table>";  
                            }    
                          else
                          {
                        $space=" ";
                        echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>"; 
                        echo "<tr>";   
                        echo "<td>";
                            
                         for($k=0;$k<2;$k++)  
                        {   if(($graphObject[$i]['albums'][$j]['photos'][$k]['picture'])!=''){
                            echo "<a href='";
                            echo $graphObject[$i]['albums'][$j]['photos'][$k]['picture'];
                            echo "' target='_blank'>";
                            echo "<img height='100px' width='100px' src='";
                            echo $graphObject[$i]['albums'][$j]['photos'][$k]['picture'];
                            echo "'/>"; 
                            echo "</a>"; 
                            echo $space;
                            echo $space;}
                        }
                        
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";  
                          }
                            echo "</div>";
                         }    
                        echo "</div>";
                     }         
        }
                 }
        }echo "<br />\n";
            
                     echo "<br />\n"; 
   for($i=0;$i<count($graphObject);$i++)
        {    $p='posts';   
             $posts=$graphObject[$i]['id'].$p;
             
                $pp='postsheading';
                $postsh=$graphObject[$i]['id'].$pp;               
            
               if($graphObject[$i]['posts']=='')
                    
                {       echo "<div id =\"".$postsh."\" style = \"display :none\">";
                        echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                        echo "<tr>";
                        echo "<td style='text-align:center;'>";
                        echo "No Posts has been found";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</div>";
                }
               else
            {    
                for($i=0;$i<count($graphObject);$i++)
        {
       $pp='postsheading';
                $postsh=$graphObject[$i]['id'].$pp; 
                echo "<div id =\"".$postsh."\" style = \"display :none\">";   
                echo "<table class='border' align='center'  bgcolor='#A9A9A9' width='1200px' height='10px'>"; 
                echo "<tr>";;
                echo "<td style='text-align:center;'>";                
                echo "<a id='postsclick' onclick='PostsLink(\"".$graphObject[$i]['id']."\")'  href=\"#\">Posts</a>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                echo "</div>";
        }
        echo "<br />\n";
            echo "<br />\n"; 
     for($i=0;$i<count($graphObject);$i++)
        {
           $p='posts';   
             $posts=$graphObject[$i]['id'].$p;
          
                {     
                      echo "<div id =\"".$posts."\" style = \"display :none\">";
                    $j=0;
                       if($graphObject[$i]['posts'][$j]['message']=='')
                       {
                            echo "<table align='center' bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                            echo "<tr>";
                            echo "<td style='text-align:center;'>";                           
                            echo "No Messages has been found";
                            echo "</td>";
                            echo "</tr>";
                            echo "</table>";
                       }
                       else
                       {    echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";  
                            echo "<tr>"; 
                           echo "<td>";
                           echo "<b>Message</b>";
                           echo "</td>";
                            echo "</tr>";
                               if($graphObject[$i]['posts'][$j]['message']!=''){
                              for($j=0;$j<5;$j++)
                                {    echo "<tr>";
                                    echo "<td>";
                                    echo $graphObject[$i]['posts'][$j]['message'];
                                    echo "</td>";
                                    echo "</tr>";  
                                }}
                           echo "</table>";
                      }
                    echo "</div>";
       
                }
        }
                 }
       
        }
     }
     
     
break;
    echo "</div>";
        }
    case "page":
        
 {   // echo "Your type is:".$type;
        
  require_once __DIR__.'/facebook-sdk-v5/autoload.php';
    $fb = new Facebook\Facebook([
  'app_id' => '1810536372540952',
  'app_secret' => '811dad884b44dcaaea3dab3358d5252f',
  'default_graph_version' => 'v2.8',
]);
        
$fb->setDefaultAccessToken('EAAZAurFlWlhgBAJmP9gBaAh1Y6giNkJ9lHuUewjandmgJ5K1xEqlvZAUX0r7f0coc0lcqMRkIT2ppb5BWaTN6IBtizC3klIIZAC9ZAk83QQxZBhmNLZCZCNOo5ZAuB1jZCLZBk96IMqTRAgtJSUsZAYjnLQz4hnZAUggsUIZD');

try {
      
  $postresponse = $fb->get('search?q='.$q.'&type='.$type.'&fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name,picture.width(1500).height(1500)}},posts.limit(5)');
   
}
 catch(Facebook\Exceptions\FacebookResponseException $e) {
  
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} 
    catch(Facebook\Exceptions\FacebookSDKException $e) {

  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
} 
    $graphObject = $postresponse->getGraphEdge()->asArray();
     
    $i=0;
    echo "<div id=\"main_table\">";
    if($graphObject[$i]=='')
    {
            echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                       echo "<tr>";
                       echo "<td style='text-align:center;'>";
                       echo "No Records has been found";
                       echo "</td>";
                        echo "</tr>";
                        echo "</table>";
    }
     else
     {
echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px' id = \"pages_table\">";      
    echo "<th style='text-align:left;font-weight:bold'>";
    echo ProfilePhoto;
    echo "<td style='font-weight:bold'>";
    echo Name;
    echo "</td>";    
    echo "<td style='font-weight:bold'>";
    echo Details;
    echo "</td>";
    echo "</th>";    
    for ($i = 0; $i < count($graphObject); $i++)
    {   
        echo "<tr>";
        echo "<td>";
        echo "<a href='";
        echo $graphObject[$i]['picture']['url'];
        echo "' target='_blank'>";
        echo "<img height='30px' width='30px' src='";
        echo $graphObject[$i]['picture']['url'];
        echo "'/>"; 
        echo "</a>";         
        echo "</td>";
        echo "<td>";
        echo $graphObject[$i]['name'];
        echo "</td>";   
        echo "<td>";         
       echo "<a id='details' onclick='detailsLink(\"".$graphObject[$i]['id']."\")'  href=\"#\">Details </a>";
     echo "</td>";
     echo "</tr>";   
    }
    echo "</table>"; 
        for($i=0;$i<count($graphObject);$i++)
        {
                $a='albums';
                $albums=$graphObject[$i]['id'].$a;
               
                $aa='albumsheading';
                $albumsh=$graphObject[$i]['id'].$aa;
        
                if($graphObject[$i]['albums']=='')
                    {
                       
                        echo "<div id =\"".$albumsh."\" style = \"display :none\">";
                        
                        echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                       echo "<tr>";
                       echo "<td style='text-align:center;'>";
                       echo "No Albums has been found";
                       echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</div>";
                    
                    }
                    
                 else if($graphObject[$i]['albums']!='')
                 {
    
    
     for($i=0;$i<count($graphObject);$i++)
        {
                $aa='albumsheading';
                $albumsh=$graphObject[$i]['id'].$aa;
                
                echo "<div id =\"".$albumsh."\" style = \"display :none\">";        
                echo "<table class='border' align='center'  bgcolor='#A9A9A9' width='1200px' height='10px'>"; 
                echo "<tr>";
                echo "<td style='text-align:center;'>";
                echo "<a id='albumclick' onclick='AlbumsLink(\"".$graphObject[$i]['id']."\")'  href=\"#\">Albums</a>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                echo "</div>";
        }
      echo "<br />\n";
            echo "<br />\n"; 
        for($i=0;$i<count($graphObject);$i++)
        {
                $a='albums';
                $albums=$graphObject[$i]['id'].$a;
                $ph='photos';
                $photos=$graphObject[$i]['id'].$ph;
        
               
                      {  
                        echo "<div id =\"".$albums."\" style = \"display :none\">";
                        
                        for($j=0;$j<5;$j++)
                        {
                        echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";
                            if($graphObject[$i]['albums'][$j]['name']!=''){
                        echo "<tr>";
                        echo "<td>";
                            if ($graphObject[$i]['albums'][$j]['photos']=='')
                            {
                                echo $graphObject[$i]['albums'][$j]['name'];
                            }else
                            {
                        echo "<a  onclick='albumpicturelink(\"".$j.$photos."\")' href=\"#\">";
                        echo $graphObject[$i]['albums'][$j]['name'];
                        echo  "</a>";
                            }
                        echo "</td>";   
                        echo "</tr>";    }
                        echo "</table>";
                       
                        echo "<div id =\"".$j.$photos."\" style = \"display :none\">";    
                            
                                                  
                          $k=0;
                            if ($graphObject[$i]['albums'][$j]['photos']=='')
                            {
                                echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                                if($graphObject[$i]['albums'][$j]['name']!=''){
                        echo "<tr>";
                        echo "<td>";
                        echo "<a  onclick='albumpicturelink(\"".$j.$photos."\")' href=\"#\">";
                        echo $graphObject[$i]['albums'][$j]['name'];
                        echo  "</a>";
                        echo "</td>";   
                        echo "</tr>"; 
                            }
                                echo "</table>";  
                            }    
                          else
                          {
                        $space=" ";
                        echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>"; 
                        echo "<tr>";   
                        echo "<td>";
                            
                         for($k=0;$k<2;$k++)  
                        {   if(($graphObject[$i]['albums'][$j]['photos'][$k]['picture'])!=''){
                            echo "<a href='";
                            echo $graphObject[$i]['albums'][$j]['photos'][$k]['picture'];
                            echo "' target='_blank'>";
                            echo "<img height='100px' width='100px' src='";
                            echo $graphObject[$i]['albums'][$j]['photos'][$k]['picture'];
                            echo "'/>"; 
                            echo "</a>"; 
                            echo $space;
                            echo $space;}
                        }
                        
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";  
                          }
                            echo "</div>";
                         }    
                        echo "</div>";
                     }         
        }
                 }
        }echo "<br />\n";
            
                     echo "<br />\n"; 
   for($i=0;$i<count($graphObject);$i++)
        {    $p='posts';   
             $posts=$graphObject[$i]['id'].$p;
             
                $pp='postsheading';
                $postsh=$graphObject[$i]['id'].$pp;               
            
               if($graphObject[$i]['posts']=='')
                    
                {       echo "<div id =\"".$postsh."\" style = \"display :none\">";
                        echo "<table align='center'  bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                        echo "<tr>";
                        echo "<td style='text-align:center;'>";
                        echo "No Posts has been found";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</div>";
                }
               else
            {    
                for($i=0;$i<count($graphObject);$i++)
        {
       $pp='postsheading';
                $postsh=$graphObject[$i]['id'].$pp; 
                echo "<div id =\"".$postsh."\" style = \"display :none\">";   
                echo "<table class='border' align='center'  bgcolor='#A9A9A9' width='1200px' height='10px'>"; 
                echo "<tr>";;
                echo "<td style='text-align:center;'>";                
                echo "<a id='postsclick' onclick='PostsLink(\"".$graphObject[$i]['id']."\")'  href=\"#\">Posts</a>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
                echo "</div>";
        }
        echo "<br />\n";
            echo "<br />\n"; 
     for($i=0;$i<count($graphObject);$i++)
        {
           $p='posts';   
             $posts=$graphObject[$i]['id'].$p;
          
                {     
                      echo "<div id =\"".$posts."\" style = \"display :none\">";
                    $j=0;
                       if($graphObject[$i]['posts'][$j]['message']=='')
                       {
                            echo "<table align='center' bgcolor='#DCDCDC' width='1200px' height='10px'>";   
                            echo "<tr>";
                            echo "<td style='text-align:center;'>";                           
                            echo "No Messages has been found";
                            echo "</td>";
                            echo "</tr>";
                            echo "</table>";
                       }
                       else
                       {   
                          
                           echo "<table class='border' align='center' border='1' bgcolor='#DCDCDC' width='1200px' height='10px'>";  
                            echo "<tr>"; 
                           echo "<td>";
                           echo "<b>Message</b>";
                           echo "</td>";
                            echo "</tr>";
                               if($graphObject[$i]['posts'][$j]['message']!=''){
                              for($j=0;$j<5;$j++)
                                {    echo "<tr>";
                                    echo "<td>";
                                    echo $graphObject[$i]['posts'][$j]['message'];
                                    echo "</td>";
                                    echo "</tr>";  
                                }
                               }
                           echo "</table>";
        } 
                    echo "</div>";
       
                }
        }
                 }
       
        }
     }
     
break;
    echo "</div>";
        }
    default:
}   
}
?>    
    
<script type="text/javascript">
    
    function detailsLink(idval)
    {
        document.getElementById("pages_table").style.display = 'none';
        albumsheading=idval+"albumsheading";
        document.getElementById(albumsheading).style.display = 'block';
        postsheading=idval+"postsheading";
        document.getElementById(postsheading).style.display = 'block';
     }
    function AlbumsLink(albumval)
    {
        albumsheading=albumval+"albumsheading";        
        document.getElementById(albumsheading).style.display = 'block';
        album =albumval+"albums";
        if(document.getElementById(album).style.display == "block")
            {
                document.getElementById(album).style.display = 'none';
            }
        else if(document.getElementById(album).style.display == "none")
            {
   document.getElementById(album).style.display = 'block';
            }
              
        
    }
    
   function PostsLink(postsval)
    {
        postsheading=postsval+"postsheading";
        posts=postsval+"posts";
        
        if(document.getElementById(postsheading).style.display == 'block')
        if(document.getElementById(posts).style.display == "block")
            {
                document.getElementById(posts).style.display = 'none';
            }
        else if(document.getElementById(posts).style.display == "none")
            {
   document.getElementById(posts).style.display = 'block';
            }
        }
    
    
    function albumpicturelink(picval)
    {
//        alert(document.getElementById(picval).style.display);
        if(document.getElementById(picval).style.display == "block")
            {
                document.getElementById(picval).style.display = 'none';
            }
        else if(document.getElementById(picval).style.display == "none")
            {
   document.getElementById(picval).style.display = 'block';
            }
        
        
    }
function clear()
    {   
        document.getElementById("main_table").style.display='none';
    
    }
      

</script>    
    
  <NOSCRIPT/>      
</body>
</html>