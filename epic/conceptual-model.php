<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>Conceptual Model</title>
	</head>

	<body>
		<main>
			<h1>Conceptual Model</h1>
			<h2>User</h2>
			<ul>
				<li>userId (primary key)</li>
				<li>userHubs</li>
				<li>userImage</li>
				<li>userName</li>
				<li>userEmail</li>
				<li>userReputation</li>
			</ul>
			<h2>Hub</h2>
			<ul>
				<li>hubId (primary key)</li>
				<li>hubUserId (foreign key)</li>
				<li>hubLocation</li>
				<li>hubItems</li>
				<li>hubDateCreated</li>
				<li>hubHours</li>
				<li>hubName</li>
				<li>hubImage</li>
			</ul>
		</main>
	</body>
</html>