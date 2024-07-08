
$(document).ready(function(){
  $('.slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 7000,
    arrows: true,
    prevArrow: '<button type="button" class="slick-prev">Previous</button>',
    nextArrow: '<button type="button" class="slick-next">Next</button>',
    dots: true,
    pauseOnFocus: false,    // Change 2: Ensure autoplay doesn't stop on focus
    pauseOnDotsHover: false,
    pauseOnHover: false,
    responsive: [
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: false,
        }
      }
    ]
  });

  // Custom function to handle slide content animations
  function handleSlideAnimations() {
    $('.slide').each(function() {
      if ($(this).hasClass('slick-active')) {
        $(this).find('.slide-content').addClass('active');
        $(this).find('img').addClass('active'); // Add active class to image
      } else {
        $(this).find('.slide-content').removeClass('active');
        $(this).find('img').removeClass('active'); // Remove active class from image
      }
    });
  }

  // Initialize slide animations
  handleSlideAnimations();

  // Add event listener for after change of slide
  $('.slider').on('afterChange', function(event, slick, currentSlide){
    handleSlideAnimations();
  });
});



  document.addEventListener("DOMContentLoaded", function() {
    var countersSection = document.getElementById('facts');
    var counters = document.querySelectorAll("h2 strong");
    var countersStarted = false;

    var updateCount = function(counter) {
      var target = +counter.getAttribute("data-to");
      var count = +counter.innerText;
      var increment = target / 200;

      if (count < target) {
        counter.innerText = Math.ceil(count + increment);
        setTimeout(function() { updateCount(counter); }, 10);
      } else {
        counter.innerText = target;
      }
    };

    var startCounters = function() {
      if (!countersStarted) {
        countersStarted = true;
        counters.forEach(function(counter) {
          updateCount(counter);
        });
      }
    };

    var observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          startCounters();
          observer.unobserve(countersSection); // Stop observing after the animation starts
        }
      });
    }, { threshold: 0.5 });

    observer.observe(countersSection);
  });



document.addEventListener("DOMContentLoaded", function() {
  // Simulate loading completion (for demonstration purposes)
  setTimeout(function() {
    // Hide the loader
    document.querySelector('.loader-container').style.display = 'none';
    // Show the main content
    document.getElementById('main-content').style.display = 'block';
    // Optionally, redirect to the main page after a delay
    setTimeout(function() {
      window.location.href = 'index.html'; // Replace with your main page URL
    }, 2000); // Adjust the delay (in milliseconds) as needed
  }, 3000); // Adjust the duration of your loading animation (in milliseconds)
});

