
var myapp = angular.module('fbapp',['ngAnimate']);

myapp.controller('fbctrl',fbcall);

function fbcall($scope,$http,$window)
{
 //$window.localStorage.clear();
   //geolocation
$scope.position= function(pos){
        $scope.lat=pos.coords.latitude;
        $scope.long=pos.coords.longitude;
    }
    navigator.geolocation.getCurrentPosition($scope.position);
    
    
   //time format 
    $scope.dateformat=function(d){
        d=d.replace("T"," ");
        return d.replace("+0000"," ");
        
    }
    
    
    
    //user 
     $scope.user;     
    $scope.pageuser=true;
$scope.detuser=false;
    
   
     $scope.userslist = function userslist()
   {   
             $scope.showbar = true;
         $scope.input = $scope.input.split(" ");
        // alert($scope.input);
         $scope.input = $scope.input.toString();
        // alert($scope.input);
         $http(
{    
method : "GET",
url : "http://lowcost-env.hma7mhrif3.us-west-2.elasticbeanstalk.com/index.php",
params : {type : "user",q : $scope.input}
}
).then(function successCallback(response){
    
     $scope.showbar = false;
        $scope.pageuser = true;         
             
   $scope.user = response.data.data; 
    $scope.paginate=response.data;
         console.log($scope.user);   
  },function errorCallback(response){
              console.log(response);
           
 });     
}   
        

$scope.displayuserFirstPage=function(){
    
$scope.pageuser=!$scope.pageuser;
$scope.detuser=!$scope.detuser;
    
}


 $scope.getNextUserPage = function() {
                    
     
} 
 
   $scope.displayuserDetails = function(id) {

       $scope.pageuser=!$scope.pageuser;
$scope.detuser=!$scope.detuser;
   }
   

   $scope.usrdetailspage;        
 $scope.userdetailspage=function userdetailspage(id)
{  $scope.albshowbar=true;
     $scope.postshowbar=true;
     $scope.albumcontent=false;
     $scope.postcontent=false;
     
  $http(
{
  method :"GET",
  url :"http://lowcost-env.hma7mhrif3.us-west-2.elasticbeanstalk.com/index.php",
  params :{q : $scope.input,userid:id,type:"user2"}
}
).then (function successCallback(response){
       $scope.albshowbar=false;
     $scope.postshowbar=false;
     $scope.albumcontent=true;
     $scope.postcontent=true;
     
        $scope.detuser = true; 
             
  $scope.usrdetailspage=response;
  
  console.log($scope.usrdetailspage);
      
 
});
}   

 //page
  $scope.showbar = true;
      $scope.page;   
      
       $scope.pagepage=true;
$scope.detpage=false;
         
     $scope.pagelist = function pagelist()
   {    $scope.showbar = true; 
    $scope.input = $scope.input.split(" ");
         $scope.input = $scope.input.toString();
        $http(
{    
method : "GET",
url : "http://lowcost-env.hma7mhrif3.us-west-2.elasticbeanstalk.com/index.php",
params : {type : "page",q : $scope.input}
}
).then (function successCallback(response){  
            
$scope.showbar = false;
        $scope.pagepage = true;     
   
   $scope.page = response.data.data; 
         console.log($scope.page);      
 });     
}
      $scope.pagepage=true;
     
 $scope.displaypageFirstPage=function(){
    
$scope.pagepage=!$scope.pagepage;
$scope.detpage=!$scope.detpage;
    
}


 $scope.getNextPagePage = function() {
                    
} 
 
   $scope.displaypageDetails = function(id) {

       $scope.pagepage=!$scope.pagepage;
$scope.detpage=!$scope.detpage;
   }
   
   
   $scope.pgedetailspage;        
 $scope.pagedetailspage=function pagedetailspage(id)
{ $scope.albshowbar=true;
     $scope.postshowbar=true;
     $scope.albumcontent=false;
     $scope.postcontent=false; 
     
  $http(
{
  method :"GET",
  url :"http://lowcost-env.hma7mhrif3.us-west-2.elasticbeanstalk.com/index.php",
  params :{q : $scope.input,userid:id,type:"page2"}
}
).then (function successCallback(response){
        $scope.albshowbar=false;
     $scope.postshowbar=false;
     $scope.albumcontent=true;
     $scope.postcontent=true;
      
  $scope.pgedetailspage=response;  
  console.log($scope.pgedetailspage);
      
 
});
}       
     
     
     
//event 
  $scope.showbar = true;
     $scope.event;    
     $scope.pageevt=true;
$scope.detevent=false;
     $scope.eventlist = function eventlist()
   {   $scope.showbar = true; 
    $scope.input = $scope.input.split(" ");
         $scope.input = $scope.input.toString();
        $http(
{    
method : "GET",
url : "http://lowcost-env.hma7mhrif3.us-west-2.elasticbeanstalk.com/index.php",
params : {type : "event",q : $scope.input}
}
).then (function successCallback(response){ 
            
$scope.showbar = false;
        $scope.pageevt = true;         
                         
            
   $scope.event = response.data.data; 
         console.log($scope.event);      
 });     
} 

       $scope.pageevt=true;   
 $scope.displayeventFirstPage=function(){
     
    
$scope.pageevt=!$scope.pageevt;
$scope.detevent=!$scope.detevent;
    
}


 $scope.getNextEventPage = function() {
                    
} 
 
   $scope.displayeventDetails = function(id) {

       $scope.pageevt=!$scope.pageevt;
$scope.detevent=!$scope.detevent;
   }
   
   
   $scope.evtdetailspage;        
 $scope.eventdetailspage=function eventdetailspage(id)
{ 
    $scope.albshowbar=true;
     $scope.postshowbar=true;
     $scope.albumcontent=false;
     $scope.postcontent=false;   
  $http(
{
  method :"GET",
  url :"http://lowcost-env.hma7mhrif3.us-west-2.elasticbeanstalk.com/index.php",
  params :{q : $scope.input,userid:id,type:"event2"}
}
).then (function successCallback(response){
     
      $scope.albshowbar=false;
     $scope.postshowbar=false;
     $scope.albumcontent=true;
     $scope.postcontent=true;
                
  $scope.evtdetailspage=response;  
  console.log($scope.evtdetailspage);
      
 
});
}       
     
   //place    
  $scope.showbar = true;
     $scope.place;
    
    $scope.pageplc=true;
$scope.detplc=false;
         
     $scope.placelist = function placelist()
   {    $scope.showbar = true;
    $scope.input = $scope.input.split(" ");
         $scope.input = $scope.input.toString();
        $http(
{    
method : "GET",
url : "http://lowcost-env.hma7mhrif3.us-west-2.elasticbeanstalk.com/index.php",
params : {type : "place",q : $scope.input}
}
).then (function successCallback(response){   
            $scope.showbar = false;
        $scope.pageplc = true;         
             
            
   $scope.place = response.data.data; 
         console.log($scope.place);      
 });     
}
    $scope.pageplc=true;
 
$scope.displayplaceFirstPage=function(){
    
$scope.pageplc=!$scope.pageplc;
$scope.detplc=!$scope.detplc;
    
}


 $scope.getNextPlacePage = function() {
                    
} 
 
   $scope.displayplaceDetails = function(id) {

       $scope.pageplc=!$scope.pageplc;
$scope.detplc=!$scope.detplc;
   }
   
   
   $scope.plcdetailspage;        
 $scope.placedetailspage=function placedetailspage(id)
{ 
$scope.albshowbar=true;
     $scope.postshowbar=true;
     $scope.albumcontent=false;
     $scope.postcontent=false;      
     
  $http(
{
  method :"GET",
  url :"http://lowcost-env.hma7mhrif3.us-west-2.elasticbeanstalk.com/index.php",
  params :{q : $scope.input,userid:id,type:"place2"}
}
).then (function successCallback(response){
       $scope.albshowbar=false;
     $scope.postshowbar=false;
     $scope.albumcontent=true;
     $scope.postcontent=true;
  $scope.plcdetailspage=response;
  
  console.log($scope.plcdetailspage);
      
 
});
}   
    
     
     
//group 
  $scope.showbar = true;
      $scope.group;   
    $scope.pagegrp=true;
$scope.detgrp=false;
     $scope.grouplist = function grouplist()
   {   $scope.showbar = true; 
    $scope.input = $scope.input.split(" ");
         $scope.input = $scope.input.toString();
        $http(
{    
method : "GET",
url : "http://lowcost-env.hma7mhrif3.us-west-2.elasticbeanstalk.com/index.php",
params : {type : "group",q : $scope.input}
}
).then (function successCallback(response){ 
           
    $scope.showbar = false;
        $scope.pagegrp = true;         

   $scope.group = response.data.data; 
         console.log($scope.group);      
 });     
}
  
    $scope.pagegrp=true;
 
$scope.displaygroupFirstPage=function(){
    
$scope.pagegrp=!$scope.pagegrp;
$scope.detgrp=!$scope.detgrp;
    
}


 $scope.getNextGroupPage = function() {
                    
} 
 
   $scope.displaygroupDetails = function(id) {

       $scope.pagegrp=!$scope.pagegrp;
$scope.detgrp=!$scope.detgrp;
   }
   
   
   $scope.grpdetailspage;        
 $scope.groupdetailspage=function groupdetailspage(id)
{ 
   $scope.albshowbar=true;
     $scope.postshowbar=true;
     $scope.albumcontent=false;
     $scope.postcontent=false;  
      
  $http(
{
  method :"GET",
  url :"http://lowcost-env.hma7mhrif3.us-west-2.elasticbeanstalk.com/index.php",
  params :{q : $scope.input,userid:id,type:"group2"}
}
).then (function successCallback(response){
      $scope.albshowbar=false;
     $scope.postshowbar=false;
     $scope.albumcontent=true;
     $scope.postcontent=true;
  $scope.grpdetailspage=response;
  
  console.log($scope.grpdetailspage);
      
 
});
}   
 var arr2;
//favorite section
 //add favorite
$scope.makeasfav = function makeasfav(idd,picture,name,typ)
{

if(!$window.localStorage.getItem(idd.toString()))    
    
{
 //convert type and ID to string    
 var type=typ.toString();
    
 var id=idd.toString();    
 //add the fav    
 arr2="["+picture.toString()+","+name.toString()+","+type+","+id+"]";
    console.log(arr2);
$window.localStorage.setItem(id,arr2);
}
 }   


//show favorite
$scope.displayfav=function displayfav(){
  $scope.favPage=true;
 var key,value,value1,element;
    
    $scope.arr=[];
    
    for(i=0;i<$window.localStorage.length;i++){
   key=$window.localStorage.key(i);
value=$window.localStorage.getItem(key);
        
value=value.replace("[","");
value=value.replace("]","");
        
 var value1=value.split(",");
        element={pictureurl:value1[0],name:value1[1],type:value1[2],id:value1[3]};
        
   $scope.arr.push(element);     
        
        
    }
    
 $scope.favPage=true;
$scope.favDetails=false;    
  
    
}
         
 
     
  //delete favorite
$scope.deletefav= function removefav(a)
         {
    var id=a.toString();  
$window.localStorage.removeItem(id);
  $scope.favPage=false;  
    
    $scope.displayfav();
    
         }     
     




//fav info section
   
    $scope.favPage=true;
$scope.favDetails=false;
     
 
$scope.displayfavFirstPage=function(){
    
$scope.favPage=!$scope.favPage;
$scope.favDetails=!$scope.favDetails;
    
}


 $scope.getNextFavPage = function() {
                    
} 
 
   $scope.displayfavDetails = function(id) {

       $scope.favPage=!$scope.favPage;
$scope.favDetails=!$scope.favDetails;
   }
   
   
   $scope.fvdetailspage;        
 $scope.favdetailspage=function favdetailspage(id)
{ 
     
  $http(
{
  method :"GET",
  url :"http://lowcost-env.hma7mhrif3.us-west-2.elasticbeanstalk.com/index.php",
  params :{q : $scope.input,userid:id,type:"favo2"}
}
).then (function successCallback(response){
      
  $scope.fvdetailspage=response;
  
  console.log($scope.fvdetailspage);
      
 
});
}        
     
  
//fb-post
 window.fbAsyncInit = function() {
        FB.init({appId: '256394911436431',
                 status: true,
                 cookie: true,
               xfbml: true,
                version:'v2.8'});
    };
        
        (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s);
            js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
        
       $scope.fbp;  
    
     $scope.share= function share(id)
   {  
       
          $http(
{    
method : "GET",
url : "http://lowcost-env.hma7mhrif3.us-west-2.elasticbeanstalk.com/index.php",
params : {userid:id,type:'fb'}
}
).then (function successCallback(response){           
   $scope.fbp = response.data; 
        console.log($scope.fbp); 
         console.log($scope.fbp.name);     
         console.log($scope.fbp.picture.data.url);
 });     
         	FB.ui(
				{  
                 
					method: 'feed',
					name: $scope.fbp.name,
					link: window.location.href,
					caption: 'FB SEARCH FROM USC CSCI571',
					picture: $scope.fbp.picture.data.url,
				},  
				 // callback
				function(response) 
				{
					if (response && !response.error_message) 
					{
						alert('Posted Successfully.');
					} 
					else 
					{
						alert('Not Posted.');
					}
				});
			
			}
    
//pagination


     
     //highlight star
     
     
    $scope.changeColor= function changeColor(id)
            {
           // alert(id);
                document.getElementById(id).setAttribute("class","glyphicon glyphicon-star");
        if(document.getElementById(id).style.color != "yellow")        
        document.getElementById(id).style.color="yellow";
        else
            {
            document.getElementById(id).setAttribute("class","glyphicon glyphicon-star-empty");
                document.getElementById(id).style.color="black";
            }
            }     
    
        
   
    
     $scope.clear=function clear(){
        //alert("clear");
     $scope.pageuser=false;
$scope.detuser=false;
         
       $scope.pagepage=false;
$scope.detpage=false;
         
       $scope.pageplc=false;
$scope.detplc=false;
         
       $scope.pageevt=false;
$scope.detevent=false; 
         
         $scope.pagegrp=false;
$scope.detgrp=false;
         
         $scope.favPage=false;
$scope.favDetails=false;
        
    }

     
     

}
 
     
