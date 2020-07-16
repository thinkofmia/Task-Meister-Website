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
    yourStudentList = [];
    //Js function to load the list
    updateTeacherLists = function(){
        /*
            JavaScript function: Update all of the lists in html based on the lists
            Requires no parameters
            Condition: userPreferredList, userNotPreferredList and userMayTryList have to be already defined
        */
        //Set values of the input boxes to match the lists after converting them into a string
        document.getElementById("input_list1").value = JSON.stringify(yourTeacherList);
        document.getElementById("text_list1").innerHTML = JSON.stringify(yourTeacherList);
    }
    updateStudentLists = function(){
        document.getElementById("input_list2").value = JSON.stringify(yourStudentList);
        document.getElementById("text_list2").innerHTML = JSON.stringify(yourStudentList);
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
        updateTeacherLists();//Update list display using the above js func
    }
    toggleStudent = function(student_id){
    /*
        JavaScript Function: To toggle preference by clicking on the boxes, also does the color change effect
        Parameter tag: Refers to the particular tag id/name
    */
        var element = document.getElementById(student_id); //Get the tag html element
        if (yourStudentList.includes(student_id)){
            /*
                If teacher exists already inside your list, remove them
            */
            //Removes tag from preferred list
            yourStudentList = yourStudentList.filter(item => item !== student_id);
            //Code to change tag element class for interface color change (CSS)
            element.classList.remove("teacherSelected");
            //For debug purposes to show which list the teacher is added to
            console.log("Removed "+student_id+" from list! "); 
        }
        else {
            /*
                If teacher doesn't exist in list, add them.
            */
            //Adds tag to may try list
            yourStudentList.push(student_id);
            //Code to change tag element class for interface color change (CSS)
            element.classList.add("teacherSelected");
            //For debug purposes to show which list the teacher is added to
            console.log("Added "+student_id+" to list! "); 
        }
        updateStudentLists();//Update list display using the above js func
    }

function editClass() {
    //Set Vars
    var classInput = document.getElementById("preferenceForm");
    var editBtn = document.getElementById("editClassBtn");
    var classList = document.querySelector(".teachersList");

    if (editBtn.innerHTML=="Edit Class"){
        //Change inner html
        editBtn.innerHTML="Cancel Edit";
        //Show
        classInput.style.display = "inline-block";
        classList.style.display = "inline-block";
    }
    else {
        //Change inner html
        editBtn.innerHTML="Edit Class";
        //Show
        classInput.style.display = "none";
        classList.style.display = "none";
    }
    
}

function filterName(keyword){
    //Set Vars
    var classGroup = document.querySelectorAll(".teacherBox");
    console.log("Changing");
    //Check if keyword is empty
    if (keyword.length<1){
        for (var i=0;i<classGroup.length;i++){
            classGroup[i].style.display = "inline-flex";
        }
    }
    else{//If keyword exists
        for (var i=0;i<classGroup.length;i++){//Loop class group
            var user_name = classGroup[i].querySelector(".teacherLabel").innerHTML;
            if (user_name.toLowerCase().includes(keyword.toLowerCase())) classGroup[i].style.display = "inline-flex";
            else classGroup[i].style.display = "none";
        }
    }
}

document.addEventListener('DOMContentLoaded', function(){
    //Set Vars
    var classInput = document.getElementById("preferenceForm");
    var classList = document.querySelector(".teachersList");

    //Hide Button
    classInput.style.display = "none";
    classList.style.display = "none";
});
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
    <?php if ($isTeacher) : ?>
        Account Type: Teacher.<br>
        <div class="flex stretch"><span style="background-color: orange;">Your Students: </span><span id="text_list2">[]</span></div>
        <form id="preferenceForm" style="display:none;" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
            <input type="text" name="list2" id="input_list2" placeholder = "[]">
            <br><input type="submit" class="inputSavePreference" name="submit2" value="Save Selection">
            <br><input onkeyup="filterName(this.value);" onchange="filterName(this.value);" type="text" name="filter" id="name_filter" placeholder = "filter by name">
        </form>
    <?php else : ?>
        Account Type: Student.<br>
        <div class="flex stretch"><span style="background-color: orange;">Your Teachers: </span><span id="text_list1">[]</span></div>
        <form id="preferenceForm" style="display:none;" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
            <input type="text" name="list1" id="input_list1" placeholder = "[]">
            <br><input type="submit" class="inputSavePreference" name="submit" value="Save Selection">
            <br><input onkeyup="filterName(this.value);" onchange="filterName(this.value);" type="text" name="filter" id="name_filter">
        </form>
    <?php endif; ?>
    <br><button onclick="editClass();" id="editClassBtn" class="inputSavePreference" >Edit Class</button>
    <br>
    <!--
        Create input boxes in a form (Method: POST)
            list1 refers to the Preferred List
    -->
    
</div>

<!--
    Display differently if teacher or students
-->
<?php if ($isTeacher) : ?><!--For teachers to pick students-->
    <div class="teachersList" style="display:none;">
        <!--For loop to display tag list-->
        <?php foreach ($studentList as $key => $value) : ?>
            <!--Set the div id as the tag name and give it onclick toggleTeacher func-->
            <div class="teacherBox" id="<?php echo $key;?>" onclick="toggleStudent(<?php echo $key;?>)">
                <!--
                    Displays image of based on the images folder and img name in the mod: Width and Height 100%
                    If no image is found, give it default image using onerror func
                -->
                <img src="/taskmeisterx/modules/mod_taskmeister_chooseclass/images/<?php echo $key;?>.jpg" width="100%" height="100%" onerror="var randomImgName = ['1.jpg','2.jpg','3.jpg','4.jpg','2.jpg']; this.src='/taskmeisterx/modules/mod_taskmeister_chooseclass/images/'+Math.floor(1+Math.random() * 15)+'.jpg';"/>
                <!--Display label of tags, including tag name and number of uses-->
                <p class = "teacherLabel"><?php echo $value; ?><br>
                Code: <?php echo $key; ?></p>
            </div>
            </script>
        <?php endforeach; ?>
    </div>
<?php else : ?><!--For students to pick teachers-->
    <div class="teachersList" style="display:none;">
        <!--For loop to display tag list-->
        <?php foreach ($teacherList as $key => $value) : ?>
            <!--Set the div id as the tag name and give it onclick toggleTeacher func-->
            <div class="teacherBox" id="<?php echo $key;?>" onclick="toggleTeacher(<?php echo $key;?>)">
                <!--
                    Displays image of based on the images folder and img name in the mod: Width and Height 100%
                    If no image is found, give it default image using onerror func
                -->
                <img src="/taskmeisterx/modules/mod_taskmeister_chooseclass/images/<?php echo $key;?>.jpg" width="100%" height="100%" onerror="var randomImgName = ['1.jpg','2.jpg','3.jpg','4.jpg','2.jpg']; this.src='/taskmeisterx/modules/mod_taskmeister_chooseclass/images/'+Math.floor(1+Math.random() * 15)+'.jpg';"/>
                <!--Display label of tags, including tag name and number of uses-->
                <p class = "teacherLabel"><?php echo $value; ?><br>
                Code: <?php echo $key; ?></p>
            </div>
            </script>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
//Function to add current list to menu
<?php foreach ($currentList as $row) : ?>
    <?php if ($isTeacher) : ?>
        toggleStudent(<?php echo $row; ?>);
    <?php else : ?>
        toggleTeacher(<?php echo $row; ?>);
    <?php endif; ?>
<?php endforeach; ?>
</script>
