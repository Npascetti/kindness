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
					<li><a href="mark.php">Mark Persona</a></li>
				</ul>
			</nav>
		</header>

		<main>
			<h2>User</h2>
			<ul>
				<li>userId (primary key)</li>
				<li>userActivationToken</li>
				<li>userBio</li>
				<li>userBlacklisted</li>
				<li>userCreated</li>
				<li>userEmail</li>
				<li>userFirstName</li>
				<li>userHash</li>
				<li>userImage</li>
				<li>userLastName</li>
				<li>userName</li>
				<li>userSalt</li>
			</ul>

			<h2>Hub</h2>
			<ul>
				<li>hubId (primary key)</li>
				<li>hubUserId (foreign key)</li>
				<li>hubDateCreated</li>
				<li>hubHours</li>
				<li>hubImage</li>
				<li>hubLocation</li>
				<li>hubName</li>
			</ul>

			<h2>Reputation</h2>
			<ul>
				<li>reputationId (primary key)</li>
				<li>reputationHubId (foreign key)</li>
				<li>reputationUserId (foreign key)</li>
				<li>reputationPositive</li>
			</ul>

			<h2>Level</h2>
			<ul>
				<li>levelId (primary key)</li>
				<li>levelRepId (foreign key)</li>
				<li>levelUserId (foreign key)</li>
				<li>levelNumber</li>
			</ul>

			<h2>Manager</h2>
			<ul>
				<li>managerHubId (foreign key)</li>
				<li>managerUserId (foreign key)</li>
			</ul>

			<h2>Item</h2>
			<ul>
				<li>itemId (primary key)</li>
				<li>itemHubId (foreign key)</li>
				<li>itemManagerId (foreign key)</li>
				<li>itemUserId (foreign key)</li>
				<li>itemAvailability</li>
				<li>itemName</li>
				<li>itemRequested</li>
			</ul>
		</main>
	</body>
</html>