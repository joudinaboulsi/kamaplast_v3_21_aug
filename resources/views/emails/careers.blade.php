<h1>Career Request from EIDEAL website</h1>
<p><b>Full Name:</b> {{ $fullname }}</p>
<p><b>Date of Birth:</b> {{ $dob }}</p>
<p><b>Position:</b> {{ $position }}</p>
<p><b>Phone:</b> {{ $phone }}</p>
<p><b>Salary:</b> {{ $salary }}</p>
<p><b>Email:</b> {{ $email }}</p>
<p><b>Experience:</b> <?php $message = preg_replace('/\r\n|\r|\n/','<br/>',$msg_client); echo $message; ?></p>
<p>You can find the CV and Photo (if uploaded) attached.</p>