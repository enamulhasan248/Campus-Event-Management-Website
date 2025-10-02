<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>adminpage</title>

    <style>
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 9999; /* Ensure it stays on top */
        }


      .navbar-collapse {
       background-color: #192F59; /* Matches the navbar color */
       color: white;
       }
      /* Custom Styles */
      .navbar {
        background-color: #192F59; /* Dark blue background */
        height: 80px;
      }
      .navbar-brand {
        color: white !important;
        font-weight: bold;
        font-size: 1.2em;
        position: relative;
      }
      .navbar-nav .nav-link {
        color: white !important;
        font-weight: 500;
        padding: 10px 20px;
        transition: background-color 0.3s ease;
      }
      .navbar-nav .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.15);
      }
      .navbar-toggler-icon {
        filter: invert(1);
      }
      .navbar-nav .nav-item.active .nav-link {
        background-color: rgba(97, 218, 251, 0.2);
      }
      
    </style>

  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-dark">
      
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#" data-page="Events_admin.php"> Admin Page  <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-page="create_event.php">Create Event</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-page="take_attendance.php">Attendance</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Management
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">

            
              <a class="dropdown-item" href="#" data-page="event_wise_registration.php">EventWise Registration</a>
              <a class="dropdown-item" href="#">EventWise Feedback</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Dashboard</a>
            </div>
          </li>
        </ul>
        <div class="d-flex align-items-center" style="padding-right: 40px;">
          <form class="form-inline my-2 my-lg-0 mr-3">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
          </form>

          <form action="login.php" method="get">
            <button class="btn btn-danger my-2 my-sm-0" type="submit">Logout</button>
          </form>
        </div>

        
      </div>
    </nav>

    

    <div id="content" class="container mt-4">
    <!-- Default content load (HomePage.php) -->
    <?php include 'Events_admin.php'; ?>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>


           <script>
        // Attach click listeners to navbar links
document.querySelectorAll('a.nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        let page = this.getAttribute('data-page');
        if (!page) return;

        // Optional: highlight active link
        document.querySelectorAll('a.nav-link').forEach(l => l.classList.remove('active'));
        this.classList.add('active');

        // Fetch the content of the page and load into #content div
        fetch(page)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not OK');
            return response.text();
        })
        .then(html => {
            document.getElementById('content').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('content').innerHTML = '<p class="text-danger">Failed to load page.</p>';
            console.error('Error loading page:', error);
        });
    });
});

// Attach click listeners to dropdown items
document.querySelectorAll('.dropdown-item').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        let page = this.getAttribute('data-page');
        if (!page) return;

        // Optional: highlight active dropdown item
        document.querySelectorAll('.dropdown-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');

        // Fetch the content of the page and load into #content div
        fetch(page)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not OK');
            return response.text();
        })
        .then(html => {
            document.getElementById('content').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('content').innerHTML = '<p class="text-danger">Failed to load page.</p>';
            console.error('Error loading page:', error);
        });
    });
});

    </script>
  </body>
</html>