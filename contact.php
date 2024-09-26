<?php include_once('includes/get_header.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Contact Us - Perennial SMS</title>
	<link rel="stylesheet" href="./css/style.css">
</head>

<body>
	<div class="content">
		<section class="contact-us">
			<h1>Contact Us</h1>
			<p>If you have any questions or inquiries, feel free to reach out to us:</p>
			<ul>
				<li><span class="m-icon">email</span><span>support@perennialsms.com</span></li>
				<li><span class="m-icon">phone</span><span>+91 98765 43210</span></li>
				<li><span class="m-icon">location_on</span><span>123, ABC Street, XYZ City, India</span></li>
			</ul>
			<form action="contact_form.php" method="POST" autocomplete="off">
				<label for="name">Name:</label>
				<input type="text" id="name" name="name" required autocomplete="off">

				<label for="email">Email:</label>
				<input type="email" id="email" name="email" required autocomplete="off">

				<label for="message">Message:</label>
				<textarea id="message" name="message" required autocomplete="off"></textarea>

				<button type="submit">Send Message</button>
			</form>
		</section>
	</div>
</body>

</html>
<?php include_once('includes/get_footer.php'); ?>