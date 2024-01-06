<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="dropdown.css">
    <title>Company Dashboard</title>
</head>

<body>
    <div class="dropdown">
        <span class="cool-button animated-button">Sign In</span>
        <div class="dropdown-content">
            <a class="cool-button animated-button" onclick="redirectTo('client')">Client Dashboard</a>
            <a class="cool-button animated-button" onclick="redirectTo('admin')">Admin Dashboard</a>
        </div>
    </div>
    <div>
        <img id="logo" src="images/black_logo.png" alt="Your Logo">
    </div>

    <video id="video-background" autoplay muted loop>
        <source src="https://swe.umbc.edu/~nsubba1/prototypev7/PayrollSystem/images/page.MP4" type="video/mp4">
    </video>

    <div class="container-drop">
        <div class="left-section">
            <div class="section">
                <h4>We set people free to do great work.</h4>
                <p>Avalance Innovation software collects and organizes all the information you gather throughout the
                    employee life cycle,
                    and then helps you use it to achieve great things. Whether you’re hiring, onboarding, preparing
                    compensation, or building culture, Avalance Innovation gives you the tools and insights to focus on
                    your most important asset—your people.</p>
            </div>
            <div><img id="group" src="images/group.png" alt="group"></div>
        </div>

        <div class="right-section">
            <div class="section">
                <h4>Built for SMBs</h4>
                <p>Starting small makes everything easier when it comes to building strong cultures and creating great
                    places to work.
                    That’s why we’re obsessed with crafting solutions for small and medium-sized businesses.</p>
            </div>

            <div class="section">
                <h4>Easy to Set Up, Easy to Use</h4>
                <p>We built Avalance Innovation to be intuitive, clear, and easy to use. People get it immediately, they
                    love using it,
                    and they’ll love you for choosing our software.</p>
            </div>

            <div class="section">
                <h4>Top-Rated Customer Service</h4>
                <p>No one else puts the customer experience at the heart of everything quite like we do. We’re always
                    listening to gain a
                    better understanding of how we can help you succeed.</p>
            </div>
        </div>
    </div>
    <div class="big-section">
        <h3>People Data and Analytics</h3>
        <div class="sectionsg">
            <div class="small-section">
                <h4>Employee Records</h4>
                <p>Manage all of your sensitive people data in one organized, secure database. You’ll never need
                    cumbersome spreadsheets or cluttered paper files again.</p>
            </div>

            <div class="small-section">
                <h4>Mobile App</h4>
                <p>Modern HR pros need software that can keep up with their busy schedules. Our free mobile app gives
                    everyone in
                    your organization easy access to essential BambooHR functions at all times.</p>
            </div>

            <div class="small-section">
                <h4>Workflows and Approvals</h4>
                <p>Bottlenecks and slow approval processes prevent you from making important decisions promptly.
                    Avalanche Innovations comes
                    with pre-built workflows so you can smooth out operations and keep work on track.</p>
            </div>

            <div class="small-section">
                <h4>Reporting and Analytics</h4>
                <p>Avalanche Innovations reporting and analytics makes reporting effortless, so you can create and share
                    reports
                    quickly. And with the
                    information your reports provide, you can make strategic decisions with confidence.</p>
            </div>
        </div>
    </div>

    <script>
    function redirectTo(role) {
        window.location.href = 'signin.php';

    }
    </script>
    <script src="scripts.js"></script>
</body>

</html>