<?php 
// No direct access
defined('_JEXEC') or die; 
//Displays module output
$userPreferenceList = array();//User Preferred List
?>
<?php //echo json_encode($currentList); ?>
<script type="text/javascript">
//Javascript to store JS functions and initialize array
    //List of your teacher list - default empty
    yourTeacherList = [];
    //Js function to load the list
    updateLists = function(){
        /*
            JavaScript function: Update all of the lists in html based on the lists
            Requires no parameters
            Condition: userPreferredList, userNotPreferredList and userMayTryList have to be already defined
        */
        //Set values of the input boxes to match the lists after converting them into a string
        document.getElementById("input_list1").value = JSON.stringify(yourTeacherList);
        document.getElementById("text_list1").innerHTML = JSON.stringify(yourTeacherList);
    }

    toggleTeacher = function(teacher_id){
    /*
        JavaScript Function: To toggle preference by clicking on the boxes, also does the color change effect
        Parameter tag: Refers to the particular tag id/name
    */
        var element = document.getElementById(teacher_id); //Get the tag html element
        if (yourTeacherList.includes(teacher_id)){
            /*
                If teacher exists already inside your list, remove them
            */
            //Removes tag from preferred list
            yourTeacherList = yourTeacherList.filter(item => item !== teacher_id);
            //Code to change tag element class for interface color change (CSS)
            element.classList.remove("teacherSelected");
            //For debug purposes to show which list the teacher is added to
            console.log("Removed "+teacher_id+" from list! "); 
        }
        else {
            /*
                If teacher doesn't exist in list, add them.
            */
            //Adds tag to may try list
            yourTeacherList.push(teacher_id);
            //Code to change tag element class for interface color change (CSS)
            element.classList.add("teacherSelected");
            //For debug purposes to show which list the teacher is added to
            console.log("Added "+teacher_id+" to list! "); 
        }
        updateLists();//Update list display using the above js func
    }
</script>

<!-- 
Display left hand side text
    Requires preferenceOptions class to make it stay left
-->
<div class="customtext teachersOptions">
    <!--If header exists, display header-->
    <?php if ($displayHeader) : ?>
        <h3><?php echo $displayHeader; ?></h3>
    <?php endif; ?>
    <!--If text exists, display text-->
    <?php if ($displayText) : ?>
        <?php echo $displayText; ?>
    <?php endif; ?>
    <br>
    <!--
        Create input boxes in a form (Method: POST)
            list1 refers to the Preferred List
    -->
    <form id="preferenceForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
        <br><span style="background-color: green;">Your Teachers: </span><span id="text_list1">[]</span><input type="text" name="list1" id="input_list1" placeholder = "[]">
        <br><input type="submit" name="submit">
    </form>
</div>

<!--
    Displays right hand side preference box
        Requires css class preferenceList, preferenceBox and preferenceLabel
-->
<div class="teachersList">
    <!--For loop to display tag list-->
    <?php foreach ($teacherList as $key => $value) : ?>
        <!--Set the div id as the tag name and give it onclick toggleTeacher func-->
        <div class="teacherBox" id="<?php echo $key;?>" onclick="toggleTeacher(<?php echo $key;?>)">
            <!--
                Displays image of based on the images folder and img name in the mod: Width and Height 100%
                If no image is found, give it default image using onerror func
            -->
            <img src="/taskmeisterx/modules/mod_taskmeister_chooseclass/images/<?php echo $key;?>.jpg" width="100%" height="100%" onerror="this.src='/taskmeisterx/modules/mod_taskmeister_choosepreference/images/default.jpg';"/>
            <!--Display label of tags, including tag name and number of uses-->
            <p class = "teacherLabel"><?php echo $value; ?><br>
            Code: <?php echo $key; ?></p>
        </div>
        </script>
    <?php endforeach; ?>
</div>

<script>
//Function to add current list to menu
<?php foreach ($currentList as $key => $value) : ?>
    <?php if ($key): ?>
        <?php if ($value==0): ?>//If teacher exists, toggle
            toggleTeacher('<?php echo $key; ?>');
        <?php endif; ?>
    <?php endif; ?>
<?php endforeach; ?>
</script>
