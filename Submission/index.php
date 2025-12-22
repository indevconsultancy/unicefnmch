<?php include('../includes/config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Tracker</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
	    <script>
			// Client-side validation for WhatsApp number format
			function validateForm(event) {
				const whatsappNumber = document.getElementById("whatsappNumber").value;
				const numberRegex = /^[6-9]\d{9}$/;

				if (!numberRegex.test(whatsappNumber)) {
					alert("Please enter a valid WhatsApp number (10 digits starting with 6-9).");
					event.preventDefault(); // Prevent form submission
					return false;
				}
				return true; // Proceed if validation passes
			}
		</script>
</head>
<body>
    <!-- Header Section -->
    <header class="bg-primary text-white text-center py-3">
        <img src="https://unicef.indevconsultancy.in/mis/img/unicef.png" alt="Logo" style="max-height: 60px;"> <span style="font-size:17px; font-weight:700">Track your submission</span>
    </header>

    <!-- Main Form Section -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
			    <form action="submit.php" method="post" class="p-4 border rounded shadow-sm bg-light" onsubmit="return validateForm(event)">
				<h2 class="text-center" style="font-size:22px;">Search your submissions</h2>
                     <div class="mb-3">
                        <label for="district" class="form-label">Select User</label>
                        <select class="form-select" id="user" name="user" required>
                            <option value="">-- Select User --</option>
							<?php $sqlUser=mysqli_query($conn, "SELECT distinct(original_name) FROM `consultants`
 order by original_name asc"); 
 while($dataUser=mysqli_fetch_object($sqlUser)) { ?>
                            <option value="<?=$dataUser->original_name?>"><?=$dataUser->original_name?></option>
 <?php } ?>
                       
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="district" class="form-label">Select Form</label>
                        <select class="form-select" id="survey" name="survey" required>
                            <option value="">-- Select Form --</option>
							<?php $sqlUser=mysqli_query($conn, "SELECT * FROM `survey` where del_action='N' and id in(24,19) ORDER BY `id` DESC"); 
 while($dataUser=mysqli_fetch_object($sqlUser)) { ?>
                            <option value="<?=$dataUser->id?>"><?=$dataUser->survey_name?></option>
 <?php } ?>
                       
                        </select>
                    </div>
                    <div class="mb-3">
						<label for="visitDate" class="form-label">Visit Date</label>
						<input type="date" class="form-control" id="visitDate" name="visitDate" required>
					</div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="bg-primary text-white text-center py-3">
        <!--<p>&copy; 2024 Registration Portal. All Rights Reserved.</p>-->
		<p>Technology Partner: Indev Consultancy Pvt Ltd</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
