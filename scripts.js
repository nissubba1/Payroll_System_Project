window.addEventListener('scroll', function () {
    var video = document.getElementById('video-background');
    var videoHeight = video.offsetHeight;
    var scrollPosition = window.scrollY;

    // Calculate the distance from the top of the page to the bottom of the video
    var videoBottom = video.offsetTop + videoHeight;

    // Calculate the opacity based on the distance from the top of the page to the bottom of the video
    var opacity = 1 - (scrollPosition / videoBottom);

    // Ensure the opacity is within the range [0, 1]
    opacity = Math.max(0, Math.min(1, opacity));

    // Apply the opacity to the video
    video.style.opacity = opacity;
});
