<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
$userPreferenceList = array();//User Preferred List
?>
<?php //echo json_encode($currentList); ?>
<script type="text/javascript">
//Javascript to store JS functions and initialize array
    //List of user preferred tags - default empty
    userPreferredList = [];
    //List of user not preferred tags - default empty
    userNotPreferredList = [];
    //List of user may try tags - default empty
    userMayTryList = [];
    //Js function to load the list
    updateLists = function(){
        /*
            JavaScript function: Update all of the lists in html based on the lists
            Requires no parameters
            Condition: userPreferredList, userNotPreferredList and userMayTryList have to be already defined
        */
        //Set values of the input boxes to match the lists after converting them into a string
        document.getElementById("input_list1").value = JSON.stringify(userPreferredList);
        document.getElementById("input_list2").value = JSON.stringify(userNotPreferredList);
        document.getElementById("input_list3").value = JSON.stringify(userMayTryList);
        document.getElementById("text_list1").innerHTML = JSON.stringify(userPreferredList);
        document.getElementById("text_list2").innerHTML = JSON.stringify(userNotPreferredList);
        document.getElementById("text_list3").innerHTML = JSON.stringify(userMayTryList);
    }

    togglePreference = function(tag){
    /*
        JavaScript Function: To toggle preference by clicking on the boxes, also does the color change effect
        Parameter tag: Refers to the particular tag id/name
    */
        var element = document.getElementById(tag); //Get the tag html element
        if (userPreferredList.includes(tag)){
            /*
                If tag exists already inside preferred list,
                change it to not preferred list
            */
            //Removes tag from preferred list
            userPreferredList = userPreferredList.filter(item => item !== tag);
            //Adds tag to not preferred list
            userNotPreferredList.push(tag);
            //Code to change tag element class for interface color change (CSS)
            element.classList.add("userNotPreferred");
            element.classList.remove("userPreferred");
            element.classList.remove("userMayTry");
            //For debug purposes to show which list the tag is added to
            console.log("Added "+tag+" to not preferred list! "); 
        }
        else if (userNotPreferredList.includes(tag)){
            /*
                If tag exists already inside not preferred list,
                change it to may try list
            */
            //Removes tag from not preferred list
            userNotPreferredList = userNotPreferredList.filter(item => item !== tag);
            //Adds tag to may try list
            userMayTryList.push(tag);
            //Code to change tag element class for interface color change (CSS)
            element.classList.add("userMayTry");
            element.classList.remove("userNotPreferred");
            element.classList.remove("userPreferred");
            //For debug purposes to show which list the tag is added to
            console.log("Added "+tag+" to may try list! ");
        }
        else if (userMayTryList.includes(tag)){
            /*
                If tag exists already inside may try list,
                remove it from the list/make the tag neutral
            */
            //Removes tag from may try list
            userMayTryList = userMayTryList.filter(item => item !== tag);
            //For debug purposes to show the tag is removed from the lists
            console.log("Remove "+tag+" from lists! ");
            //Code to change tag element class for interface color change (CSS)
            element.classList.remove("userMayTry");
            element.classList.remove("userPreferred");
            element.classList.remove("userNotPreferred");
        }
        else {
            /*
                If tag doesn't exists in any lists,
                add it to the preferred list
            */
            //Adds tag to the preferred list
            userPreferredList.push(tag);
            //For debug purposes to show which list the tag is added to
            console.log("Added "+tag+" to preferred list! ");
            //Code to change tag element class for interface color change (CSS)
            element.classList.add("userPreferred");
            element.classList.remove("userNotPreferred");
            element.classList.remove("userMayTry");
        }
        updateLists();//Update list display using the above js func
    }

function filterName(keyword){
    //Set Vars
    var classGroup = document.querySelectorAll(".preferenceBox");
    console.log("Changing");
    //Check if keyword is empty
    if (keyword.length<1){
        for (var i=0;i<classGroup.length;i++){
            classGroup[i].style.display = "inline-flex";
        }
    }
    else{//If keyword exists
        for (var i=0;i<classGroup.length;i++){//Loop class group
            var user_name = classGroup[i].querySelector(".preferenceLabel").innerHTML;
            if (user_name.toLowerCase().includes(keyword.toLowerCase())) classGroup[i].style.display = "inline-flex";
            else classGroup[i].style.display = "none";
        }
    }
}
</script>

<!-- 
Display left hand side text
    Requires preferenceOptions class to make it stay left
-->
<div class="customtext preferenceOptions">
    <!--If header exists, display header-->
    <?php if ($displayHeader) : ?>
        <h3><?php echo $displayHeader; ?></h3>
    <?php endif; ?>
    <!--If text exists, display text-->
    <?php echo "Welcome ".$user->name; ?><br>
    <?php if ($displayText) : ?>
        <?php echo $displayText; ?>
    <?php endif; ?>
    <br>
    <!--
        Create input boxes in a form (Method: POST)
            list1 refers to the Preferred List
            list2 refers to the Not Preferred List
            list3 refers to the May Try List
    -->
    <form id="preferenceForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
        <br><span style="background-color: green; color: white;">Preferred</span>: <span id="text_list1">[]</span><input type="text" name="list1" id="input_list1" placeholder = "[]"> 
        <br><span style="background-color: red; color: white;">Against</span>: <span id="text_list2">[]</span><input type="text" name="list2" id="input_list2" placeholder = "[]"> 
        <br><span style="background-color: goldenrod; color: white;">May Try</span>: <span id="text_list3">[]</span><input type="text" name="list3" id="input_list3" placeholder = "[]"> 
        <br><input class="inputSavePreference" type="submit" name="submit" value="Save Preferences">
    </form>
    <input onkeyup="filterName(this.value);" onchange="filterName(this.value);" type="text" name="filter" id="name_filter" placeholder = "filter by name">
</div>

<!--
    Displays right hand side preference box
        Requires css class preferenceList, preferenceBox and preferenceLabel
-->
<div class="preferenceList">
    <!--For loop to display tag list-->
    <?php $count=0;//To count number of tags ?>
    <?php foreach ($tagList as $key => $value) : ?>
        <!--Set the div id as the tag name and give it onclick togglePreference func-->
        <div class="preferenceBox" id="<?php echo $key;?>" onclick="togglePreference('<?php echo $key;?>')">
            <!--
                Displays image of based on the images folder and img name in the mod: Width and Height 100%
                If no image is found, give it default image using onerror func
            -->
            <img src="/taskmeisterx/modules/mod_taskmeister_choosepreference/images/<?php echo $key;?>.jpg" width="100%" height="100%" onerror="this.src='/taskmeisterx/modules/mod_taskmeister_choosepreference/images/default.jpg';"/>
            <!--Display label of tags, including tag name and number of uses-->
            <p class = "preferenceLabel"><?php echo $key; ?></p>
            <p class = "preferenceStats"><?php echo $value["likes"]; ?> 👍 <?php echo $value["deployed"]; ?> 👨‍💻</p>
        </div>
        <?php $count = $count + 1; ?>
        <?php if ($count==25): ?>
            <!--<button onclick="//getAllTags();" id="getMoreBtn" class="inputSavePreference" >Get All Tags</button><br>-->
            <?php echo "<span id='getMore'>"; ?>
        <?php endif; ?>
        </script>
    <?php endforeach; ?>
    <!--Make a get more button-->
    <?php if ($count>=25): ?>
            <?php echo "</span>"; ?>
    <?php endif; ?>
</div>

<script>
var moreText = document.getElementById("getMore");
//moreText.style.display = "none";

function getAllTags() {
  var moreText = document.getElementById("getMore");
  var btnText = document.getElementById("getMoreBtn");

  if (moreText.style.display == "inline") {
    btnText.innerHTML = "Get All"; 
    moreText.style.display = "none";
  } 
  else {
    btnText.innerHTML = "Hide Rest"; 
    moreText.style.display = "inline";
  }
}
//Function to add current list to menu
<?php foreach ($currentList as $key => $value) : ?>
    <?php if ($key): ?>
        <?php if ($value==0): ?>//No way
            togglePreference('<?php echo $key; ?>');
            togglePreference('<?php echo $key; ?>');
        <?php elseif ($value==1) : ?>
            togglePreference('<?php echo $key; ?>');
            togglePreference('<?php echo $key; ?>');
            togglePreference('<?php echo $key; ?>');
        <?php elseif ($value==2) : ?>//$value ==2
            togglePreference('<?php echo $key; ?>');
        <?php endif; ?>
    <?php endif; ?>
<?php endforeach; ?>
</script>
