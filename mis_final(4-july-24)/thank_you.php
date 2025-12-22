<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Done Sucessfully</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    /* Define the animation */
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-50px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    /* Apply the animation to the container */
    .container-fluid.animated {
      animation: slideIn 0.5s ease-in-out;
    }
    /* Center the image and content */
    .centered-content {
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container-fluid mt-4 animated"> <!-- Add the 'animated' class to apply the animation -->
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="centered-content">
          <img src="https://mquad.org/Content/Images/logo-lg.png" alt="image" width="100px;">
          <h2>Payment Done Sucessfully</h2>
          <h3>Congratulations 🎉</h3>
          <p>Thank you for giving subscription to our service! You're now part of our community. </p>
          <a href="mis/subscription-details.php" class="btn btn-primary">Go To Dashboard</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
