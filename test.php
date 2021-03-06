<!DOCTYPE html>
<html>
<body>

<h1> PHP page for algorithm </h1>

<?php echo 'hello world';
echo "<br>"; //new line

echo "update"; echo "<br>"; //new line

?>

<?php

class Lecture{
    public $coursename;
    public $start_time;
    public $end_time;
    
    function __construct() 
    { 
        $a = func_get_args(); 
        $i = func_num_args(); 
        if (method_exists($this,$f='__construct'.$i)) { 
            call_user_func_array(array($this,$f),$a); 
        } 
    } 
    
    function __construct3($a1,$a2,$a3){
        // echo 'new Lecture created ';
        $this->coursename = $a1;
        $this->start_time = $a2;
        $this->end_time = $a3;
    }
    
    function __construct4($a1,$a2,$a3,$a4){
        // echo 'new Lecture created ';
        $this->coursename = $a1;
        $this->start_time = $a2;
        $this->end_time = $a3;
        $this->days = $a4;
    }
}

// $demodemo = new Lecture("EECS 280", 9, 10, "MWF");
// var_dump($demodemo);

class Course{
    public $coursename;
    public $credit;
    public $lectures = array();
    
    function __construct() 
    { 
        $a = func_get_args(); 
        $i = func_num_args(); 
        if (method_exists($this,$f='__construct'.$i)) { 
            call_user_func_array(array($this,$f),$a); 
        } 
    } 
    
    function __construct2($a1,$a2){
        // echo 'new Course created ';
        $this->coursename = $a1;
        $this->credit = $a2;
    }
    
    public function add($L){
        array_push($this->lectures, $L);
        // echo "ADD"; var_dump($this->lectures); echo "<br>";
    }
}

// GLOBAL VARIABLES
$solutions = array(); //2D array
$schedule = array(); //2D array
$allCourse = array(); //array for Courses 

function absolute_val($x){
    if($x > 0) return $x;
    $zero = 0;
    return $zero - $x;
}

function find_course_combination(&$potential_courses, $curr_index, $credits, &$selected) {
    global $solutions;
    global $schedule;
    
    // echo "find_course_comb inputs are: "; var_dump($potential_courses); 
    //       echo "<br>"; //new line
    // echo "input credit is: "; echo $credits;     echo " abs: "; echo absolute_val($credits); echo "<br>";
    // echo "selected array is: "; var_dump($selected); echo "<br>";
    

    if( absolute_val($credits) < 3){
      array_push($solutions, $selected);
    //   echo "I FIND A SOLUTION!  "; var_dump($selected);
    //   echo "<br>"; //new line
      return;
    }
    
    if($credits < 0) return;
    
    for ($i = $curr_index + 1 ; $i < count($potential_courses); $i++) {
      $course = $potential_courses[$i];

    //   echo "now pushing: "; var_dump($course); echo "<br>"; //new line
      array_push($selected, $course);
      
      $remain_credit = $credits - $course->credit;
    //   echo "Remaining Credit! Fuck: "; echo $remain_credit; echo " Course->Credit: "; echo $course->credit; 
    //       echo "input credit is: "; echo $credits; echo "<br>";

      
      find_course_combination($potential_courses, $i, $remain_credit, $selected);
      array_pop($selected);
    }  //end for
}

function isOnSameDay($L1, $L2) {
    $L1_arr = str_split((string)$L1->days);
    $L2_arr = str_split((string)$L2->days);
    foreach ($L1_arr as $c1) { // TODO horrible efficiency
        foreach ($L1_arr as $c2) {
            if ($c1 == $c2)
                return True;
        }
    }
    return False;
}

function hasConflict($L1, $L2) {
    if(isOnSameDay($L1, $L2) and $L1->start_time >= $L2->start_time and $L1->start_time < $L2->end_time)
        return True;
    if(isOnSameDay($L1, $L2) and $L2->start_time >= $L1->start_time and $L2->start_time < $L1->end_time)
        return True;
  return False;
}



function hasConflictWithList($L, &$V){
  if(count($V) == 0) return false;
  
  for ($i = 0 ; $i < count($V); $i++) {
    if(hasConflict($L, $V[$i])) return True;
  }    
  return False;
}



function find_time(&$course_list, $curr_index, &$curr_result){
    global $solutions;
    global $schedule;
    
  if(count($course_list) == count($curr_result)) {
    array_push($schedule, $curr_result);  
    return;
  }
  
  for ($i = $curr_index+1 ; $i < count($course_list); $i++) {
    $temp_course = $course_list[$i];
    for ($j = 0 ; $j < count($temp_course->lectures); $j++) {
      if( hasConflictWithList($temp_course->lectures[$j], $curr_result) ) 
        continue;
      
      array_push($curr_result, $temp_course->lectures[$j]);
      find_time($course_list, $i, $curr_result);
      array_pop($curr_result);
    }
  }
}

function debug_solutions()
{
    echo "<br>";
    global $solutions;
    global $schedule;
    
    for($i=0; $i < count($solutions); $i++){
        $total_credit = 0;
        for($j=0; $j < count($solutions[$i]); $j++){
            $course = $solutions[$i][$j];
            echo $course->coursename; echo " ";
            $total_credit += $course->credit;
        }
        echo "total: "; echo $total_credit ; echo "<br>"; //new line
    }
}


function print_time($time)
{
    $add = $time + 0.6;
    echo (int)$time; 
    
    if((int)$add == (int)$time){
    	// time o'clock
    	echo ":00";
    }else{
    	echo ":30";
    }
}

$bigstring;

function print_one_schedule($aschedule) {
    $out = array(); //2D
    
    $title = str_repeat('&nbsp;', 12) . "MONDAY". str_repeat('&nbsp;', 8).   "TUESDAY". str_repeat('&nbsp;', 7).   
    "WEDNESDAY". str_repeat('&nbsp;', 6).   "THRUSDAY". str_repeat('&nbsp;', 6).   "FRIDAY";
    for($i = 1; $i < 10; $i++){
    	$out[$i] = array(); // array of string
    	$out[$i][0] = (string)($i + 7);
    	for($j=1; $j<6;$j++){
    		$out[$i][$j] = " ";
    	}
    }
    
    //Core, set up
    foreach($aschedule as $lec){
    	$s_arr = str_split((string)$lec->days);
    	foreach($s_arr as $c){
    		if($c == "M") { $out[(int)$lec->start_time - 7][1] = $lec->coursename; }
    		else if($c == "T") { $out[(int)$lec->start_time - 7][2] = $lec->coursename;}
    		else if($c == "W") { $out[(int)$lec->start_time - 7][3] = $lec->coursename;}
    		else if($c == "R") { $out[(int)$lec->start_time - 7][4] = $lec->coursename;}
    		else if($c == "F") { $out[(int)$lec->start_time - 7][5] = $lec->coursename;}
    	}
    }
    
    //var_dump($out);
    
    //Print out
    echo "<br>";
    echo $title; echo "<br>"; 
    echo "------------------------------------------------------------------------------------------------------------";
    echo "<br>"; 
    for($i = 1; $i < count($out); $i++){
    	for($j=0; $j < 6; $j++){
    		if($i == 1 and $j == 0){
    			echo "08";
    		}else if( $i == 2 and $j == 0){
    			echo "09";
    		}else if($j == 0){ // first column
    			echo $out[$i][$j]; 
    		}else if( strlen($out[$i][$j]) < 4 ){
    			echo  str_repeat('&nbsp;', 8);
    		}else{
    		    //echo $i; echo ","; echo $j;
    		    echo strtoupper($out[$i][$j]); 
    		}
    		echo  str_repeat('&nbsp;', 14);
    	}
    	echo "<br>";
    }
}

function debug_schedule(){
        echo "<br>";
    global $solutions;
    global $schedule;
    
    var_dump($schedule);
    
    global $bigstring;
    
    echo "Here are the sections:"; echo "<br>";
    $count = 1;
    if( count($schedule) == 0 || $schedule[0] == NULL ){
    	echo "Wrong! No Output!"; echo "<br>";
    	return;
    }

	$i = 0;  echo "<br>";
	for($j=0; $j<count($schedule[$i]); $j++){
            $lect = $schedule[$i][$j];
            $bigstring = $bigstring . $lect->coursename . ", "  . $lect->start_time . ", ". $lect->end_time . ", " . $lect->days . ";";
        }
	// var_dump($bigstring);  echo "<br>";
    
    for($i=0; $i<count($schedule); $i++){
        echo "Schedule: "; echo $count; echo "<br>";
        for($j=0; $j<count($schedule[$i]); $j++){
            $lect = $schedule[$i][$j];
            echo strtoupper($lect->coursename); echo ": "; 
            print_time( $lect->start_time ); echo " - "; print_time( $lect->end_time);
            echo "   "; echo $lect->days; echo "<br>";
        }
        print_one_schedule($schedule[$i]);

        echo "<br>"; echo "<br>"; echo "<br>";
        $count++;
    }
    
    echo "<br>";
}



//START READING USER INPUT

$inputs = array( "eecs 281", "eecs 183", "MATH 217", "EECS 482");
// $inputs = array(" ");

// START SQL FETCH
error_reporting(-1);
ini_set('display_errors', true);
echo "Hello World!";

$server = "tcp:rd4vxrj1mk.database.windows.net";
$user = "SQLAdmin";
$pwd = "Mhacks12345";
$db = "MHacks2015";

$conn = sqlsrv_connect($server, array("UID"=>$user, "PWD"=>$pwd, "Database"=>$db));

if($conn === false){
	echo "Failure!";
} else {
	// echo "Success!";
}



foreach($inputs as $course_name){
    $sql = "SELECT credits
    FROM courses
    WHERE coursename = '" . $course_name . "';";

    $stmt = sqlsrv_query( $conn, $sql);
    if( $stmt === false ) {
        echo "Query failed!";
    } else {
        // echo "Query successful";
    }

 	// var_dump($stmt);
 	
	// Make the first (and in this case, only) row of the result set available for reading.
    if( sqlsrv_fetch( $stmt ) ) {
	// Get the row fields. Field indeces start at 0 and must be retrieved in order.
	// Retrieving row fields by name is not supported by sqlsrv_get_field.
	$num_credit = sqlsrv_get_field( $stmt, 0);
	$new_course = new Course($course_name, $num_credit);
	    
	$sql = "SELECT starttime, endtime, days 
    	FROM lectures
    	WHERE coursename = '" . $course_name . "';";

	$stmt = sqlsrv_query( $conn, $sql);
    	if( $stmt === false ) {
            echo "Query failed!";
    	} else {
            //echo "Query successful";
    	}
    	
    	while( sqlsrv_fetch( $stmt ) ) {
	    $start_time = sqlsrv_get_field( $stmt, 0);
	    $end_time = sqlsrv_get_field( $stmt, 1);
	    $days = sqlsrv_get_field( $stmt, 2);

	    $new_lecture = new Lecture($course_name, $start_time, $end_time, (string)$days); //?? todo for location
	    $new_course->add($new_lecture);
    	}
	
	array_push($allCourse, $new_course);
     }
}//end foreach inputs

// var_dump($allCourse); //??todo


//$allCourse = array();
$temp_sol = array();



function max_credits(){
   global $allCourse;
   $total = 0;
   foreach($allCourse as $oneCourse){
   	$total += $oneCourse->credit;
   }
   
   if($total > 18) return 18;
   return $total - 1;
}

$student_credit = max_credits();


find_course_combination($allCourse, -1, $student_credit, $temp_sol);
// echo "THE SOLUTION IS: "; var_dump($solutions);

for($i=0; $i < count($solutions); $i++){
    global $solutions;
    global $schedule;
    
    $total_credits = 0;
    for($j=0; $j<count($solutions[$i]); $j++){
        $sch = $solutions[$i][$j];
        $total_credits += $sch->credit;
        // echo $sch->coursename; echo " ";
    }
    // echo "total: "; echo $total_credits; echo "<br>";
    $dumb = array();

    find_time($solutions[$i], -1, $dumb);
}


echo "<br>";

debug_schedule();
// var_dump($schedule);


?>

</body>
</html>
