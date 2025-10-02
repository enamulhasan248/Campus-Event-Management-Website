<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>About Us - Campus Event Management</title>
  <style>
    /* Base and font */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f9fafb;
      color: #333;
      line-height: 1.6;
    }

    /* Container to align content nicely with nav container width */
    .container {
      max-width: 1024px;
      margin: 40px auto;
      padding: 0 20px;
    }

    /* Heading styles aligned with nav colors */
    h1, h2, h3 {
      font-weight: 700;
      letter-spacing: 1px;
      color: #192F59;
      margin-bottom: 1rem;
    }
    h1 {
      font-size: 2.5rem;
      margin-bottom: 1.5rem;
      text-align: center;
    }
    h2 {
      font-size: 2rem;
      border-bottom: 3px solid #192F59;
      padding-bottom: 8px;
      margin-bottom: 1.5rem;
    }
    h3 {
      font-size: 1.5rem;
      margin-top: 3rem;
      margin-bottom: 1rem;
    }

    /* Paragraph */
    p {
      font-size: 1.125rem;
      margin-bottom: 1.2em;
      max-width: 850px;
      margin-left: auto;
      margin-right: auto;
    }

    /* Team list */
    .team-list {
      list-style: none;
      padding-left: 0;
      max-width: 400px;
      margin: 0 auto;
    }
    .team-list li {
      font-size: 1.1rem;
      margin-bottom: 0.6rem;
      padding-left: 20px;
      position: relative;
      color: #444;
    }
    .team-list li::before {
      content: "•";
      position: absolute;
      left: 0;
      color: #192F59;
      font-weight: 700;
      font-size: 1.4rem;
      top: 2px;
      line-height: 1;
    }

    /* Footer aligned with nav colors */
    footer {
      text-align: center;
      padding: 25px 15px;
      background-color: #192F59;
      color: white;
      font-size: 0.9rem;
      margin-top: 60px;
    }

    /* Responsive */
    @media (max-width: 600px) {
      .container {
        margin: 20px auto;
        padding: 0 15px;
      }
      h1 {
        font-size: 1.8rem;
      }
      h2 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>About Campus Event Management</h1>

    <section>
      <h2>Our Mission</h2>
      <p>
        Campus Event Management is designed to simplify and enhance the way university events are organized and experienced.  
        We strive to create a seamless platform where students, faculty, and organizers can come together to share knowledge, celebrate culture, and foster community spirit.
      </p>
    </section>

    <section>
      <h2>What We Do</h2>
      <p>
        Our system offers comprehensive tools for scheduling, managing, and promoting campus events.  
        From club meetings to seminars, workshops, and social gatherings, we enable organizers to easily plan and communicate event details.  
        Participants can browse upcoming events, register with ease, and provide valuable feedback to improve future activities.
      </p>
      <p>
        We also provide user-friendly profiles where attendees can track their event history and stay informed about the latest happenings on campus.
      </p>
    </section>

    <section class="team">
      <h3>Meet Our Team</h3>
      <ul class="team-list">
        <li>Enamul Hasan (2022-1-60-149)</li>
        <li>Tanjim Khandoker (2022-1-60-245)</li>
        <li>Mehadi Hasan Shameem (2022-1-60-229)</li>
      </ul>
    </section>
  </div>

  <footer>
    &copy; 2025 Campus Event Management Team. All rights reserved.
  </footer>
</body>
</html>
