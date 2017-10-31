<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>Conceptual Model</title>
	</head>

	<body>
		<header>
			<h1>Conceptual Model</h1>
			<nav>
				<ul>
					<li><a href="index.php">Home</a></li>
					<li>Conceptual Model</li>
					<li><a href="use-cases.php">Use Cases</a></li>
					<li><a href="shannon.php">Shannon Persona</a></li>
					<li><a href="robert.php">Robert Persona</a></li>
					<li><a href="mark.php">Mark Persona</a> </li>
				</ul>
			</nav>
		</header>

		<main>
			<h2>User</h2>
			<ul>
				<li>userId (primary key)</li>
				<li>userBio</li>
				<li>userImage</li>
				<li>userName</li>
				<li>userEmail</li>
				<li>userHash</li>
				<li>userSalt</li>
				<li>firstName</li>
				<li>lastName</li>
				<li>userCreated</li>
			</ul>

			<h2>Hub</h2>
			<ul>
				<li>hubId (primary key)</li>
				<li>hubUserId (foreign key)</li>
				<li>hubLocation</li>
				<li>hubDateCreated</li>
				<li>hubHours</li>
				<li>hubName</li>
				<li>hubImage</li>
			</ul>

			<h2>Reputation</h2>
			<ul>
				<li>repUserId (foreign key)</li>
				<li>repHubId (foreign key)</li>
				<li>rep</li>
				<li>repLevel</li>
			</ul>

			<h2>Manager</h2>
			<ul>
				<li>manageUserId (foreign key)</li>
				<li>manageHubId (foreign key)</li>
			</ul>

			<h2>Item</h2>
			<ul>
				<li>itemName</li>
				<li>itemHubId (foreign key)</li>
				<li>itemAvailability</li>
				<li>itemRequested</li>
			</ul>
		</main>
	</body>
</html>