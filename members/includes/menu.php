<?php /* Members Area Menu */ ?>
<?php if ($_SESSION["isofficer"] == "yes") { ?>
<!-- Officer Access Menu -->
				<ul class="menu">
					<li><a href="members/index.php">Home</a></li>
					<li>Edit
						<ul>
							<li><a href="members/albums.php">Albums</a></li>
							<li><a href="members/attendance.php">Attendance</a></li>
							<li><a href="members/edit/canvas.php">Canvas</a></li>
							<li><a href="members/events.php">Events</a></li>
							<li><a href="members/members.php">Members</a></li>
							<li><a href="members/pages.php">Pages</a></li>
							<li><a href="members/posts.php">Posts</a></li>
							<li><a href="members/songs.php">Songs</a></li>
						</ul>
					</li>
					<li>New
						<ul>
							<li><a href="members/new/album.php">Album</a></li>
							<li><a href="members/new/attendance.php">Attendance</a></li>
							<li><a href="members/new/event.php">Event</a></li>
							<li><a href="members/new/member.php">Member</a></li>
							<li><a href="members/new/post.php">Post</a></li>
							<li><a href="members/new/song.php">Song</a></li>
						</ul>
					</li>
					<li>View
						<ul>
							<li><a href="members/contacts.php">Contact List</a></li>
							<li><a href="members/phonebook.php">Phonebook</a></li>
							<li><a href="members/schedule.php">Schedule</a></li>
							<li><a href="members/shoutbox.php">Shoutbox</a></li>
							<li><a href="members/votes.php">Song Votes</a></li>
							<li><a href="members/sitemap.php">Sitemap</a></li>
							<li><a href="members/logs.php">User Logs</a></li>
							<li><a href="members/phpinfo.php">PHP Info</a></li>
						</ul>
					</li>
					<li>Tools
						<ul>
							<li><a href="members/email_contacts.php">Email Contacts</li>
							<li><a href="members/email_rins.php">Email <acronym title="Rensselaer Identification Number">RIN</acronym>s</a></li>
							<li><a href="members/budget.php">Manage Budget</li>
							<li><a href="members/send_txt.php">TXT Members</a></li>
							<li><a href="members/send_email.php">Email Members</a></li>
							<li><a href="members/uploads.php">Uploads</a></li>
							<li><a href="members/websites.php">Websites</a></li>
							<li><a href="cpanel">cPanel X</a></li>
						</ul>
					</li>
					<li><a href="members/actions/admin/logout.php">Logout</a></li>
				</ul>
				
<?php } else if ($_SESSION["isstudent"] == "yes") { ?>
<!-- Members Area Menu -->
				<ul class="menu">
					<li><a href="members/index.php">Home</a></li>
					<li><a href="members/uploads.php">Uploads</a></li>
					<li><a href="members/phonebook.php">Phonebook</a></li>
					<li><a href="members/shoutbox.php">Shoutbox</a></li>
					<li><a href="members/votes.php">Song Votes</a></li>
					<li><a href="members/actions/admin/logout.php">Logout</a></li>
				</ul>
<?php } else { ?>
<!-- Members Area Menu -->
				<ul class="menu">
					<li><a href="members/index.php">Home</a></li>
					<li><a href="members/shoutbox.php">Shoutbox</a></li>
					<li><a href="members/actions/admin/logout.php">Logout</a></li>
				</ul>
<?php } ?>
				<!-- Members Area Main Content -->
