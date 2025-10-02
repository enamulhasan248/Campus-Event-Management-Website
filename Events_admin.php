<?php
include 'partials/_dbconnect.php';  // adjust path as needed

// Fetch all events ordered by newest first
$sql = "SELECT * FROM events ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

$events = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }
}

// Get up to 3 events for the carousel
$carouselEvents = array_slice($events, 0, 3);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bootstrap Demo - Beautified</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
      crossorigin="anonymous"
    />
    <style>
      body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        text-align: center;
      }
      .carousel-container {
        max-width: 1200px;
        margin: 20px auto 20px auto;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgb(0 0 0 / 0.2);
      }
      .carousel-inner img {
        border-radius: 12px;
        object-fit: cover;
        height: 400px;
        width: 100%;
      }
      .card {
        border-radius: 12px;
        box-shadow: 0 3px 12px rgb(0 0 0 / 0.15);
      }
      .card-img-top {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        height: 220px;
        object-fit: cover;
        width: 100%;
      }
   
    </style>
  </head>
  <body>
    <h2>Events List</h2><hr size="3">

    <!-- Event Cards Row Begin -->
    <div class="container">
      <div class="row justify-content-center g-4">
        <?php foreach ($events as $event): ?>
        <div class="col-md-4">
          <div class="card">
            <img
              src="<?php echo htmlspecialchars($event['event_image']); ?>"
              class="card-img-top"
              alt="<?php echo htmlspecialchars($event['title']); ?>"
            />
            <div class="card-body">
              <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
              <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
              <p class="card-text"><strong>Registration Deadline:</strong> 
                <?php 
                  echo !empty($event['eventdate']) ? date("F j, Y", strtotime($event['eventdate'])) : "N/A"; 
                ?>
              </p>
               <a href="edit_event.php?event_id=<?php echo $event['id']; ?>" class="btn btn-primary w-100">EDIT</a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <!-- Event Cards Row End -->

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
