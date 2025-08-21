<html lang="en">
  <head>
	<script>
	function ValidateEmployeeNumber(emp_num) {
		//var emp_num = document.getElementById("emp_num").value;
		alert("Employee Number: " + emp_num);
		//location.replace("validate_attendance.php?emp_num="+emp_num);
		
	}
	</script>
  </head>
  <body onload="document.AttendanceForm.emp_num.focus();">
	<form name="AttendanceForm" method="POST">
	  <h1>Attendance</h1>
	  <div>
		<input type="text" id="emp_num" name="emp_num" onblur="ValidateEmployeeNumber(this.value)" class="form-control" placeholder="Employee Number"/>
	  </div>
	</form>
  </body>
</html>