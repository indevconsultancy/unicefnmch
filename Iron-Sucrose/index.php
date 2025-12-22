<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
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
        <img src="https://unicef.indevconsultancy.in/mis/img/unicef.png" alt="Logo" style="max-height: 60px;"> <span style="font-size:17px; font-weight:700">आयरन सुक्रोज़ फॉलो-अप कार्यक्रम</span>
    </header>

    <!-- Main Form Section -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
			    <form action="submit.php" method="post" class="p-4 border rounded shadow-sm bg-light" onsubmit="return validateForm(event)">
				<h2 class="text-center" style="font-size:22px;">सहायक पंजीकरण फॉर्म (Facilitator Registration Form)</h2>
				<hr></hr>
                    <div class="mb-3">
                        <label for="facilitatorName" class="form-label">सहायक का नाम (एसएन/जीएनएम/एएनएम)</label>
                        <input type="text" class="form-control" id="facilitatorName" name="facilitatorName" placeholder="अपना नाम दर्ज करें" required>
                    </div>
                    <div class="mb-3">
                        <label for="whatsappNumber" class="form-label">व्हाट्सएप नंबर (WhatsApp)</label>
                        <input type="tel" class="form-control" id="whatsappNumber" name="whatsappNumber" placeholder="10-अंकों वाला व्हाट्सएप नंबर दर्ज करें" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="district" class="form-label">जिला </label>
                        <select class="form-select" id="district" name="district" required>
                            <option value="">-- जिला चुनें --</option>
                            <option value="Gaya">गया</option>
                            <option value="Purnea">पूर्णिया</option>
                        </select>
                    </div>
					<div class="mb-3">
                        <label for="facilityName" class="form-label">स्वास्थ्य केंद्र का नाम</label>
						 <select class="form-select" id="facilityName" name="facilityName" required>
                            <option value="">-- स्वास्थ्य केंद्र--</option>
                        </select>
                       
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">सबमिट करें</button>
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
	 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	 <script>
$(document).ready(function() {
  // Load states
 
  $('#district').change(function() {
    var stateID = $(this).val();
    if (stateID) {
      $.ajax({
        type: "POST",
        url: "get_districts.php",
        data: { district: stateID },
        success: function(data) {
          $('#facilityName').html(data);
        }
      });
    } else {
      $('#facilityName').html('<option value=\"\">-- स्वास्थ्य केंद्र--</option>');
    }
  });
});
</script>
</body>
</html>
